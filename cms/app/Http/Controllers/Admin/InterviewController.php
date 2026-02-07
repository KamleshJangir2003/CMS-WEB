<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class InterviewController extends Controller
{
    public function index()
    {
        $interviews = Interview::with('lead')->orderBy('interview_date', 'desc')->paginate(10);
        return view('auth.admin.interviews.index', compact('interviews'));
    }

    public function create(Request $request)
    {
        $lead = null;
        $nextRound = null;
        
        if ($request->has('lead_id')) {
            $lead = Lead::findOrFail($request->lead_id);
            
            // If this is for next round, determine the round
            if ($request->has('next_round')) {
                if ($request->next_round == 'Manager') {
                    $nextRound = 'Manager';
                } else {
                    $lastInterview = Interview::where('lead_id', $lead->id)
                        ->where('result', 'Selected')
                        ->orderBy('created_at', 'desc')
                        ->first();
                        
                    if ($lastInterview) {
                        $nextRounds = [
                            'HR' => 'Manager',
                            'Manager' => 'Final'
                        ];
                        $nextRound = $nextRounds[$lastInterview->interview_round] ?? null;
                    }
                }
            }
        }
        
        $leads = Lead::all();
        $interviewers = [
            'Amit (HR)',
            'Neha (Tech Lead)',
            'Raj (Manager)',
            'Priya (Senior Developer)',
            'Vikash (Team Lead)'
        ];
        
        return view('auth.admin.interviews.create', compact('lead', 'leads', 'interviewers', 'nextRound'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lead_id' => 'required|exists:leads,id',
            'interview_round' => 'required|in:HR,Technical,Manager,Final',
            'interview_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'interviewer' => 'required|string',
            'interview_mode' => 'required|in:Online,Offline',
            'meeting_platform' => 'required_if:interview_mode,Online|in:Google Meet,Zoom,Teams',
            'instructions' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($request->lead_id);
        
        $interview = Interview::create([
            'lead_id' => $request->lead_id,
            'candidate_name' => $lead->name,
            'candidate_email' => $lead->email,
            'job_role' => $lead->role,
            'interview_round' => $request->interview_round,
            'interview_date' => $request->interview_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'interviewer' => $request->interviewer,
            'interview_mode' => $request->interview_mode,
            'meeting_platform' => $request->meeting_platform,
            'meeting_link' => $request->meeting_link,
            'instructions' => $request->instructions,
            'email_candidate' => $request->has('email_candidate'),
            'email_interviewer' => $request->has('email_interviewer'),
            'whatsapp_notification' => $request->has('whatsapp_notification'),
        ]);

        // Send notifications
        $this->sendNotifications($interview, $lead);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview scheduled successfully!');
    }

    public function show(Interview $interview)
    {
        $interview->load('lead');
        return view('auth.admin.interviews.show', compact('interview'));
    }

    public function edit(Interview $interview)
    {
        $leads = Lead::all();
        $interviewers = [
            'Amit (HR)',
            'Neha (Tech Lead)',
            'Raj (Manager)',
            'Priya (Senior Developer)',
            'Vikash (Team Lead)'
        ];
        
        return view('auth.admin.interviews.edit', compact('interview', 'leads', 'interviewers'));
    }

    public function update(Request $request, Interview $interview)
    {
        $request->validate([
            'interview_round' => 'required|in:HR,Technical,Manager,Final',
            'interview_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'interviewer' => 'required|string',
            'interview_mode' => 'required|in:Online,Offline',
            'meeting_platform' => 'required_if:interview_mode,Online|in:Google Meet,Zoom,Teams',
            'instructions' => 'nullable|string',
        ]);

        $interview->update([
            'interview_round' => $request->interview_round,
            'interview_date' => $request->interview_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'interviewer' => $request->interviewer,
            'interview_mode' => $request->interview_mode,
            'meeting_platform' => $request->meeting_platform,
            'meeting_link' => $request->meeting_link,
            'instructions' => $request->instructions,
            'email_candidate' => $request->has('email_candidate'),
            'email_interviewer' => $request->has('email_interviewer'),
            'whatsapp_notification' => $request->has('whatsapp_notification'),
        ]);

        return redirect()->route('admin.interviews.index')->with('success', 'Interview updated successfully!');
    }

    public function destroy(Interview $interview)
    {
        $interview->delete();
        return redirect()->route('admin.interviews.index')->with('success', 'Interview deleted successfully!');
    }

    public function generateMeetingLink(Request $request)
    {
        $platform = $request->platform;
        
        if ($platform === 'Google Meet') {
            // Generate real Google Meet link
            $meetingId = $this->generateGoogleMeetLink();
            return response()->json(['link' => $meetingId]);
        }
        
        // For other platforms, generate dummy links
        $meetingId = uniqid();
        $links = [
            'Zoom' => "https://zoom.us/j/{$meetingId}",
            'Teams' => "https://teams.microsoft.com/l/meetup-join/{$meetingId}"
        ];
        
        return response()->json(['link' => $links[$platform] ?? '']);
    }

    private function generateGoogleMeetLink()
    {
        // Generate a random meeting ID similar to Google Meet format
        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $meetingId = '';
        for ($i = 0; $i < 10; $i++) {
            $meetingId .= $chars[rand(0, 25)];
        }
        
        // Format: xxx-xxxx-xxx
        $formatted = substr($meetingId, 0, 3) . '-' . substr($meetingId, 3, 4) . '-' . substr($meetingId, 7, 3);
        return "https://meet.google.com/{$formatted}";
    }

    private function sendNotifications($interview, $lead)
    {
        // Send Email to Candidate
        if ($interview->email_candidate && $lead->email) {
            $this->sendEmailToCandidate($interview, $lead);
        }

        // Send Email to Interviewer
        if ($interview->email_interviewer) {
            $this->sendEmailToInterviewer($interview, $lead);
        }

        // Send WhatsApp Notification
        if ($interview->whatsapp_notification && $lead->number) {
            $this->sendWhatsAppNotification($interview, $lead);
        }
    }

    private function sendEmailToCandidate($interview, $lead)
    {
        try {
            Mail::send('emails.interview-candidate', compact('interview', 'lead'), function ($message) use ($lead, $interview) {
                $message->to($lead->email, $lead->name)
                        ->subject('Interview Scheduled - ' . $interview->job_role);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send email to candidate: ' . $e->getMessage());
        }
    }

    private function sendEmailToInterviewer($interview, $lead)
    {
        try {
            // Get interviewer email (you might want to store this in database)
            $interviewerEmails = [
                'Amit (HR)' => 'hr@kwikster.com',
                'Neha (Tech Lead)' => 'neha@kwikster.com',
                'Raj (Manager)' => 'raj@kwikster.com',
                'Priya (Senior Developer)' => 'priya@kwikster.com',
                'Vikash (Team Lead)' => 'vikash@kwikster.com'
            ];
            
            $interviewerEmail = $interviewerEmails[$interview->interviewer] ?? 'admin@kwikster.com';
            
            Mail::send('emails.interview-interviewer', compact('interview', 'lead'), function ($message) use ($interviewerEmail, $interview) {
                $message->to($interviewerEmail)
                        ->subject('Interview Assigned - ' . $interview->candidate_name);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send email to interviewer: ' . $e->getMessage());
        }
    }

    private function sendWhatsAppNotification($interview, $lead)
    {
        try {
            $message = "🎯 *Interview Scheduled*\n\n";
            $message .= "📅 Date: " . date('d M Y', strtotime($interview->interview_date)) . "\n";
            $message .= "⏰ Time: {$interview->start_time} - {$interview->end_time}\n";
            $message .= "👤 Interviewer: {$interview->interviewer}\n";
            $message .= "💼 Role: {$interview->job_role}\n";
            $message .= "🔄 Round: {$interview->interview_round}\n";
            
            if ($interview->meeting_link) {
                $message .= "🔗 Meeting Link: {$interview->meeting_link}\n";
            }
            
            if ($interview->instructions) {
                $message .= "📝 Instructions: {$interview->instructions}\n";
            }
            
            $message .= "\nGood luck! 🍀";
            
            // Using WhatsApp Business API or third-party service
            // You can integrate with services like Twilio, WhatsApp Business API, etc.
            $this->sendWhatsAppMessage($lead->number, $message);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }

    private function sendWhatsAppMessage($phoneNumber, $message)
    {
        // Example using a WhatsApp API service
        // Replace with your actual WhatsApp API credentials
        try {
            Http::post('https://api.whatsapp.com/send', [
                'phone' => '91' . $phoneNumber,
                'text' => $message,
                'apikey' => env('WHATSAPP_API_KEY', 'your-api-key')
            ]);
        } catch (\Exception $e) {
            \Log::error('WhatsApp API error: ' . $e->getMessage());
        }
    }

    public function updateResult(Request $request, Interview $interview)
    {
        $request->validate([
            'result' => 'required|in:Selected,Rejected',
            'rejection_reason' => 'required_if:result,Rejected'
        ]);

        $interview->update([
            'result' => $request->result,
            'rejection_reason' => $request->rejection_reason,
            'status' => 'Completed'
        ]);

        $lead = $interview->lead;
        
        if ($request->result == 'Rejected') {
            // Update lead status to rejected
            $lead->update([
                'status' => 'Rejected',
                'rejection_reason' => $request->rejection_reason,
                'final_result' => 'Rejected'
            ]);
            
            return response()->json([
                'success' => true, 
                'message' => 'Candidate rejected. Process completed.',
                'final_status' => 'Rejected'
            ]);
        }
        
        // If selected, check round and proceed accordingly
        if ($request->result == 'Selected') {
            if ($interview->interview_round == 'HR') {
                // HR round passed, schedule manager round
                $lead->update(['status' => 'HR Selected']);
                
                return response()->json([
                    'success' => true,
                    'message' => 'HR round passed. Ready for Manager round.',
                    'next_action' => 'schedule_manager_round'
                ]);
            }
            
            if ($interview->interview_round == 'Manager') {
                // Manager round completed - final decision
                $lead->update([
                    'status' => 'Selected',
                    'final_result' => 'Selected'
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Candidate selected! Process completed.',
                    'final_status' => 'Selected'
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function makeOffer(Request $request, Interview $interview)
    {
        $request->validate([
            'current_ctc' => 'nullable|numeric',
            'expected_ctc' => 'nullable|numeric',
            'offered_ctc' => 'required|numeric'
        ]);

        $interview->update([
            'current_ctc' => $request->current_ctc,
            'expected_ctc' => $request->expected_ctc,
            'offered_ctc' => $request->offered_ctc,
            'offer_status' => 'Pending'
        ]);

        return response()->json(['success' => true]);
    }

    public function completeInterview(Interview $interview)
    {
        $interview->update(['status' => 'Completed']);
        return response()->json(['success' => true]);
    }
}