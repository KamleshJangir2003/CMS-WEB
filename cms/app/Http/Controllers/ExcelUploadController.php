<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Lead;

class ExcelUploadController extends Controller
{
    public function uploadExcel(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv',
                'role' => 'required|string'
            ]);

            $file = $request->file('excel_file');
            $role = $request->input('role');
            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Skip header row
            array_shift($rows);
            
            $count = 0;
            $duplicates = 0;
            
            foreach ($rows as $row) {
                if (empty($row[0])) continue; // Skip empty rows
                
                $name = trim($row[0] ?? '');
                $email = trim($row[1] ?? '');
                $phone = trim($row[2] ?? '');
                $company = trim($row[3] ?? '');
                
                // Check for duplicates by phone or email
                $exists = Lead::where('phone', $phone)
                    ->orWhere('email', $email)
                    ->exists();
                    
                if ($exists) {
                    $duplicates++;
                    continue;
                }
                
                Lead::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'company' => $company,
                    'role' => $role,
                    'status' => 'new',
                    'condition_status' => 'New',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $count++;
            }
            
            $message = "Successfully imported {$count} leads.";
            if ($duplicates > 0) {
                $message .= " Skipped {$duplicates} duplicates.";
            }
            
            return response()->json([
                'success' => true,
                'count' => $count,
                'duplicates' => $duplicates,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}