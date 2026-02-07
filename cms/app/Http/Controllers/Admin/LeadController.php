<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Callback;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function index()
    {
        // Only show leads with no status or empty status
        $leads = Lead::whereDoesntHave('interviews')
                    ->where(function($query) {
                        $query->whereNull('condition_status')
                              ->orWhere('condition_status', '')
                              ->orWhere('condition_status', 'Not Interested');
                    })
                    ->orderBy('id', 'desc')
                    ->get();
        return view('auth.admin.leads.index', compact('leads'));
    }

    public function uploadExcel(Request $request)
    {
        try {
            // Enhanced validation
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // Increased to 10MB
            ], [
                'excel_file.required' => 'Please select a file to upload.',
                'excel_file.mimes' => 'File must be Excel (.xlsx, .xls) or CSV format.',
                'excel_file.max' => 'File size must be less than 10MB.'
            ]);

            $file = $request->file('excel_file');
            
            // Enhanced file validation
            if (!$file || !$file->isValid()) {
                \Log::error('File upload validation failed', [
                    'file_exists' => $file ? 'yes' : 'no',
                    'is_valid' => $file ? $file->isValid() : 'no file',
                    'error' => $file ? $file->getError() : 'no file'
                ]);
                return redirect()->back()->with('error', 'File upload failed. Error code: ' . ($file ? $file->getError() : 'No file'));
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
                return redirect()->back()->with('error', 'Invalid file format. Please upload Excel or CSV file. Detected: ' . $extension);
            }

            // Check if file exists and is readable
            if (!file_exists($file->getPathname()) || !is_readable($file->getPathname())) {
                return redirect()->back()->with('error', 'File is not accessible. Please try again.');
            }

            // Load spreadsheet with better error handling
            try {
                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);
            } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                \Log::error('PhpSpreadsheet error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Cannot read Excel file: ' . $e->getMessage());
            }

            // Check if file has data
            if (empty($rows) || count($rows) < 2) {
                return redirect()->back()->with('error', 'File is empty or has no data rows. Found ' . count($rows) . ' rows.');
            }

            $imported = 0;
            $skipped = 0;
            $duplicates = 0;

            foreach ($rows as $index => $row) {
                if ($index === 1) continue; // Skip header

                $number = isset($row['A']) ? trim($row['A']) : '';
                $name = isset($row['B']) ? trim($row['B']) : '';
                $role = isset($row['C']) ? trim($row['C']) : 'Unknown';

                // Skip empty rows
                if (empty($number) && empty($name)) continue;

                if (empty($number) || empty($name)) {
                    $skipped++;
                    continue;
                }

                if (!is_numeric($number)) {
                    $skipped++;
                    continue;
                }

                $number = (string) $number;

                // Check for duplicate mobile number
                if (Lead::where('number', $number)->exists()) {
                    $duplicates++;
                    continue;
                }

                // Save lead
                try {
                    Lead::create([
                        'number' => $number,
                        'name' => $name,
                        'role' => $role,
                        'condition_status' => 'Not Interested'
                    ]);
                    
                    $imported++;
                } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                    $duplicates++;
                } catch (\Exception $e) {
                    $skipped++;
                }
            }

            // Success message
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
                'total_rows' => count($rows) - 1
            ]);

            return redirect()->back()->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Excel upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $status = $request->condition_status;
        
        if ($status === 'Call Back') {
            // Move to callbacks table
            Callback::create([
                'number' => $lead->number,
                'name' => $lead->name,
                'role' => $lead->role,
                'callback_date' => now()->addDay(),
            ]);
            
            // Remove from leads
            $lead->delete();
            
            return response()->json(['success' => true, 'message' => 'Lead moved to callbacks']);
        } else {
            // Update status in database for all other statuses
            $lead->condition_status = $status;
            $lead->save();
            
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        }
    }

    public function interested()
    {
        $leads = Lead::where('condition_status', 'Intrested')
                    ->orderBy('updated_at', 'desc')
                    ->get();
        return view('auth.admin.leads.interested', compact('leads'));
    }

    public function rejected()
    {
        $leads = Lead::where('condition_status', 'Rejected')
                    ->orderBy('updated_at', 'desc')
                    ->get();
        return view('auth.admin.leads.rejected', compact('leads'));
    }

    public function showProfile($id)
    {
        $lead = Lead::findOrFail($id);
        return view('auth.admin.leads.profile', compact('lead'));
    }

    public function callbacks()
    {
        $callbacks = Callback::orderBy('callback_date', 'asc')->get();
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
}