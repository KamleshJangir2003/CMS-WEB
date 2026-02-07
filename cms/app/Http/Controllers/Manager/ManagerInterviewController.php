<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Interview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerInterviewController extends Controller
{
    public function index()
    {
        $managerName = Auth::user()->first_name;
        
        $interviews = Interview::with('lead')
            ->where('interviewer', 'like', '%' . $managerName . '%')
            ->orderBy('interview_date', 'desc')
            ->paginate(10);
            
        return view('auth.manager.interviews.index', compact('interviews'));
    }

    public function updateResult(Request $request, Interview $interview)
    {
        if (!str_contains($interview->interviewer, Auth::user()->first_name)) {
            abort(403, 'You can only update interviews assigned to you.');
        }

        $request->validate([
            'result' => 'required|in:Selected,Rejected',
            'rejection_reason' => 'required_if:result,Rejected'
        ]);

        $interview->update([
            'result' => $request->result,
            'rejection_reason' => $request->rejection_reason,
            'status' => 'Completed'
        ]);

        return response()->json(['success' => true]);
    }

    public function dashboard()
    {
        $managerName = Auth::user()->first_name;
        
        $stats = [
            'totalInterviews' => Interview::where('interviewer', 'like', '%' . $managerName . '%')->count(),
            'pendingInterviews' => Interview::where('interviewer', 'like', '%' . $managerName . '%')
                ->where('status', 'Scheduled')->count(),
            'completedInterviews' => Interview::where('interviewer', 'like', '%' . $managerName . '%')
                ->where('status', 'Completed')->count(),
            'selectedCandidates' => Interview::where('interviewer', 'like', '%' . $managerName . '%')
                ->where('result', 'Selected')->count(),
        ];

        $upcomingInterviews = Interview::with('lead')
            ->where('interviewer', 'like', '%' . $managerName . '%')
            ->where('interview_date', '>=', now()->toDateString())
            ->where('status', 'Scheduled')
            ->orderBy('interview_date')
            ->limit(5)
            ->get();

        return view('auth.manager.dashboard', compact('stats', 'upcomingInterviews'));
    }
}