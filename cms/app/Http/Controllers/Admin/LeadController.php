<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Callback;
use App\Models\InterestedCandidate;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::whereDoesntHave('interviews')
                    ->where(function($q) {
                        $q->whereNull('condition_status')
                          ->orWhere('condition_status', '');
                    });
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $leads = $query->orderBy('id', 'desc')->paginate(30);
        return view('auth.admin.leads.index', compact('leads'));
    }

    public function uploadExcel(Request $request)
    {
        try {
            // Enhanced validation
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB
                'role' => 'required|string',
                'platform' => 'nullable|string'
            ], [
                'excel_file.required' => 'Please select a file to upload.',
                'excel_file.mimes' => 'File must be Excel (.xlsx, .xls) or CSV format.',
                'excel_file.max' => 'File size must be less than 10MB.',
                'role.required' => 'Please select a role.'
            ]);

            $file = $request->file('excel_file');
            $defaultRole = $request->role;
            $platform = $request->platform;
            
            // Enhanced file validation
            if (!$file || !$file->isValid()) {
                \Log::error('File upload validation failed', [
                    'file_exists' => $file ? 'yes' : 'no',
                    'is_valid' => $file ? $file->isValid() : 'no file',
                    'error' => $file ? $file->getError() : 'no file'
                ]);
                return response()->json(['success' => false, 'message' => 'File upload failed. Error code: ' . ($file ? $file->getError() : 'No file')]);
            }

            // Check file extension
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            
            \Log::info('File upload details', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension,
                'mime_type' => $mimeType,
                'size' => $file->getSize(),
                'path' => $file->getPathname()
            ]);

            if (!in_array($extension, ['xlsx', 'xls', 'csv'])) {
                return response()->json(['success' => false, 'message' => 'Invalid file format. Please upload Excel or CSV file. Detected: ' . $extension]);
            }

            // Check if file exists and is readable
            if (!file_exists($file->getPathname()) || !is_readable($file->getPathname())) {
                return response()->json(['success' => false, 'message' => 'File is not accessible. Please try again.']);
            }

            // Test database connection before processing
            try {
                \DB::connection()->getPdo();
                \Log::info('Database connection verified for lead upload');
            } catch (\Exception $e) {
                \Log::error('Database connection failed during lead upload', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => 'Database connection failed. Please try again later.']);
            }

            // Load spreadsheet with better error handling
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                \Log::error('PhpSpreadsheet error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Cannot read Excel file: ' . $e->getMessage()]);
            }

            // Check if file has data
            if (empty($rows) || count($rows) < 2) {
                return response()->json(['success' => false, 'message' => 'File is empty or has no data rows. Found ' . count($rows) . ' rows.']);
            }

            $imported = 0;
            $skipped = 0;
            $duplicates = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                if ($index === 1) continue; // Skip header

                $number = isset($row['A']) ? trim($row['A']) : '';
                $name = isset($row['B']) ? trim($row['B']) : '';
                $role = isset($row['C']) ? trim($row['C']) : $defaultRole;

                // Skip empty rows
                if (empty($number) && empty($name)) continue;

                if (empty($number) || empty($name)) {
                    $skipped++;
                    $errors[] = "Row {$index}: Missing name or number";
                    continue;
                }

                if (!is_numeric($number)) {
                    $skipped++;
                    $errors[] = "Row {$index}: Invalid phone number format";
                    continue;
                }

                $number = (string) $number;

                // Check for duplicate mobile number
                if (Lead::where('number', $number)->exists()) {
                    $duplicates++;
                    continue;
                }

                // Save lead with enhanced error handling
                try {
                    $leadData = [
                        'number' => $number,
                        'name' => $name,
                        'role' => $role,
                        'platform' => $platform,
                        'condition_status' => ''
                    ];
                    
                    \Log::info('Attempting to create lead', $leadData);
                    
                    $lead = Lead::create($leadData);
                    
                    if ($lead && $lead->id) {
                        $imported++;
                        \Log::info('Lead created successfully', ['id' => $lead->id, 'name' => $name]);
                    } else {
                        $skipped++;
                        $errors[] = "Row {$index}: Failed to create lead for {$name}";
                        \Log::error('Lead creation returned null or no ID', $leadData);
                    }
                    
                } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                    $duplicates++;
                    \Log::warning('Duplicate lead detected', ['number' => $number, 'name' => $name]);
                } catch (\Exception $e) {
                    $skipped++;
                    $errors[] = "Row {$index}: Database error - {$e->getMessage()}";
                    \Log::error('Lead creation failed', [
                        'row' => $index,
                        'data' => $leadData,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Success response for AJAX
            $message = "Successfully imported {$imported} leads.";
            if ($duplicates > 0) {
                $message .= " Skipped {$duplicates} duplicate mobile numbers.";
            }
            if ($skipped > 0) {
                $message .= " Skipped {$skipped} invalid rows.";
            }
            
            \Log::info('Upload completed', [
                'imported' => $imported,
                'skipped' => $skipped,
                'duplicates' => $duplicates,
                'total_rows' => count($rows) - 1,
                'errors' => $errors
            ]);

            $response = [
                'success' => true, 
                'message' => $message, 
                'count' => $imported,
                'stats' => [
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'duplicates' => $duplicates,
                    'total_processed' => count($rows) - 1
                ]
            ];
            
            if (!empty($errors) && count($errors) <= 10) {
                $response['errors'] = $errors;
            }

            return response()->json($response);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['success' => false, 'message' => 'Validation failed: ' . implode(', ', collect($e->errors())->flatten()->toArray())]);
        } catch (\Exception $e) {
            \Log::error('Excel upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Upload failed: ' . $e->getMessage() . '. Please check server logs for details.']);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        // Enhanced CORS headers for production
        if ($request->ajax()) {
            header('Access-Control-Allow-Origin: https://thekwikster.com');
            header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-CSRF-TOKEN, Authorization');
            header('Access-Control-Allow-Credentials: true');
        }
        
        try {
            // Enhanced database connection check
            if (!\DB::connection()->getPdo()) {
                \Log::error('Database connection lost during status update');
                return response()->json(['success' => false, 'message' => 'Database connection error'], 500);
            }
            
            Log::info('Status update request received', [
                'lead_id' => $id,
                'request_data' => $request->all(),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);
            
            // Validate request
            if (!$request->has('condition_status')) {
                return response()->json(['success' => false, 'message' => 'Status is required']);
            }
            
            $lead = Lead::findOrFail($id);
            $status = $request->condition_status;
            $reason = $request->reason ?? '';
            
            Log::info('Processing status update', [
                'lead_id' => $id,
                'old_status' => $lead->condition_status,
                'new_status' => $status,
                'reason' => $reason
            ]);
            
            // Validate reason for specific statuses
            if (in_array($status, ['Not Interested', 'Call Back', 'Rejected']) && empty($reason)) {
                return response()->json(['success' => false, 'message' => 'Reason is required for this status']);
            }
            
            // Use database transaction for data integrity
            \DB::beginTransaction();
            
            try {
                if ($status === 'Call Back') {
                    // Move to callbacks table
                    Callback::create([
                        'number' => $lead->number,
                        'name' => $lead->name,
                        'role' => $lead->role,
                        'platform' => $lead->platform,
                        'callback_date' => now()->addDay(),
                        'notes' => $reason,
                        'status' => 'call_backs'
                    ]);
                    
                    // Remove from leads
                    $lead->delete();
                    
                    \DB::commit();
                    Log::info('Lead moved to callbacks', ['lead_id' => $id]);
                    return response()->json(['success' => true, 'message' => 'Lead moved to callbacks']);
                } elseif ($status === 'Intrested') {
                    // Save to interested_candidates table
                    InterestedCandidate::updateOrCreate(
                        ['number' => $lead->number],
                        [
                            'name' => $lead->name,
                            'email' => $lead->email,
                            'role' => $lead->role,
                            'platform' => $lead->platform,
                            'resume' => $lead->resume,
                            'status' => 'interested',
                            'interested_at' => now()
                        ]
                    );
                    
                    // Update status in leads table
                    $lead->condition_status = $status;
                    $lead->save();
                    
                    \DB::commit();
                    Log::info('Lead marked as interested', ['lead_id' => $id]);
                    return response()->json(['success' => true, 'message' => 'Candidate marked as interested and saved to database']);
                } else {
                    // Update status and reason in database for all other statuses
                    $lead->condition_status = $status;
                    if (!empty($reason)) {
                        $lead->reason = $reason;
                    }
                    $lead->save();
                    
                    \DB::commit();
                    Log::info('Lead status updated', [
                        'lead_id' => $id,
                        'new_status' => $status,
                        'reason' => $reason
                    ]);
                    
                    return response()->json(['success' => true, 'message' => 'Status updated successfully']);
                }
            } catch (\Exception $dbError) {
                \DB::rollback();
                throw $dbError;
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Lead not found', ['lead_id' => $id]);
            return response()->json(['success' => false, 'message' => 'Lead not found'], 404);
        } catch (\Exception $e) {
            \DB::rollback();
            Log::error('Status update failed', [
                'lead_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()], 500);
        }
    }

    public function interested(Request $request)
    {
        $query = Lead::where('condition_status', 'Intrested');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);
        return view('auth.admin.leads.interested', compact('leads'));
    }

    public function rejected(Request $request)
    {
        $query = Lead::where('condition_status', 'Rejected');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);
        return view('auth.admin.leads.rejected', compact('leads'));
    }

    public function notInterested(Request $request)
    {
        $query = Lead::where('condition_status', 'Not Interested');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);
        return view('auth.admin.leads.not-interested', compact('leads'));
    }

    public function wrongNumber(Request $request)
    {
        $query = Lead::where('condition_status', 'Wrong Number');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%");
            });
        }
        
        $leads = $query->orderBy('updated_at', 'desc')->paginate(15);
        return view('auth.admin.leads.wrong-number', compact('leads'));
    }

    public function showProfile($id)
    {
        $lead = Lead::findOrFail($id);
        return view('auth.admin.leads.profile', compact('lead'));
    }

    public function callbacks(Request $request)
    {
        $query = Callback::where('status', 'call_backs');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('number', 'LIKE', "%{$search}%")
                  ->orWhere('role', 'LIKE', "%{$search}%")
                  ->orWhere('notes', 'LIKE', "%{$search}%");
            });
        }
        
        $callbacks = $query->orderBy('callback_date', 'desc')->paginate(30);

        return view('auth.admin.leads.callbacks', compact('callbacks'));
    }

    public function updateCallback(Request $request, $id)
    {
        $callback = Callback::findOrFail($id);
        $callback->update($request->only(['callback_date', 'notes']));
        
        return response()->json(['success' => true]);
    }

    public function deleteCallback($id)
    {
        $callback = Callback::findOrFail($id);
        $callback->delete();
        
        return response()->json(['success' => true]);
    }

    public function getCallbackCount()
    {
        $count = Callback::count();
        return response()->json(['count' => $count]);
    }

    public function uploadResume(Request $request, $id)
    {
        try {
            $request->validate([
                'resume' => 'required|file|mimes:pdf,doc,docx|max:5120' // 5MB max
            ]);

            $lead = Lead::findOrFail($id);
            
            if ($request->hasFile('resume')) {
                // Delete old resume if exists
                if ($lead->resume && file_exists(public_path('uploads/resumes/' . $lead->resume))) {
                    unlink(public_path('uploads/resumes/' . $lead->resume));
                }
                
                $file = $request->file('resume');
                $filename = time() . '_' . $lead->id . '.' . $file->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path('uploads/resumes'))) {
                    mkdir(public_path('uploads/resumes'), 0755, true);
                }
                
                $file->move(public_path('uploads/resumes'), $filename);
                
                $lead->resume = $filename;
                $lead->save();
                
                return response()->json(['success' => true, 'message' => 'Resume uploaded successfully']);
            }
            
            return response()->json(['success' => false, 'message' => 'No file uploaded']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())]);
        } catch (\Exception $e) {
            \Log::error('Resume upload failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Upload failed. Please try again.']);
        }
    }

    public function viewResume($filename)
    {
        $path = public_path('uploads/resumes/' . $filename);
        
        if (!file_exists($path)) {
            abort(404, 'Resume not found');
        }
        
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline'
        ]);
    }

    public function updateCallbackStatus(Request $request, $id)
    {
        try {
            $callback = Callback::findOrFail($id);
            $newStatus = $request->status;
            $reason = $request->reason ?? $callback->notes;
            
            // Move callback back to leads table with new status
            Lead::create([
                'number' => $callback->number,
                'name' => $callback->name,
                'role' => $callback->role,
                'platform' => $callback->platform,
                'condition_status' => $this->mapCallbackStatusToLeadStatus($newStatus),
                'reason' => $reason
            ]);
            
            // Delete from callbacks table
            $callback->delete();
            
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            Log::error('Callback status update failed', [
                'callback_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Error updating status']);
        }
    }
    
    private function mapCallbackStatusToLeadStatus($callbackStatus)
    {
        $statusMap = [
            'rejected' => 'Rejected',
            'not_interested' => 'Not Interested',
            'wrong_number' => 'Wrong Number',
            'interested' => 'Intrested'
        ];
        
        return $statusMap[$callbackStatus] ?? $callbackStatus;
    }

    public function saveManualLead(Request $request)
    {
        try {
            // Enhanced database connection check
            if (!\DB::connection()->getPdo()) {
                \Log::error('Database connection lost during manual lead save');
                return response()->json(['success' => false, 'message' => 'Database connection error'], 500);
            }
            
            $request->validate([
                'name' => 'required|string|max:255',
                'number' => 'required|string|max:20',
                'role' => 'required|string',
                'platform' => 'nullable|string'
            ]);

            // Check for duplicate mobile number
            if (Lead::where('number', $request->number)->exists()) {
                return response()->json(['success' => false, 'message' => 'This number already exists']);
            }

            // Use database transaction
            \DB::beginTransaction();
            
            try {
                $lead = Lead::create([
                    'name' => $request->name,
                    'number' => $request->number,
                    'role' => $request->role,
                    'platform' => $request->platform,
                    'condition_status' => ''
                ]);
                
                \DB::commit();
                \Log::info('Manual lead saved successfully', ['lead_id' => $lead->id]);
                
                return response()->json(['success' => true, 'message' => 'Lead saved successfully']);
            } catch (\Exception $dbError) {
                \DB::rollback();
                throw $dbError;
            }
            
        } catch (\Exception $e) {
            \Log::error('Manual lead save failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Error saving lead. Please try again.']);
        }
    }
}