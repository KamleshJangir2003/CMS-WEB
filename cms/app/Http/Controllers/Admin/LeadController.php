<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::orderBy('id', 'desc')->get();
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
        $lead->condition_status = $request->condition_status;
        $lead->save();
        
        return response()->json(['success' => true]);
    }

    public function showProfile($id)
    {
        $lead = Lead::findOrFail($id);
        return view('auth.admin.leads.profile', compact('lead'));
    }
}