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
            
            foreach ($rows as $row) {
                if (empty($row[0])) continue; // Skip empty rows
                
                $number = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $role = trim($row[2] ?? 'Unknown');
                
                // Skip empty data
                if (empty($number) || empty($name)) continue;
                
                // No duplicate check - import all
                Lead::create([
                    'number' => $number,
                    'name' => $name,
                    'role' => $role,
                    'condition_status' => 'Not Interested'
                ]);
                
                $count++;
            }
            
            $message = "Successfully imported {$count} leads.";
            
            return response()->json([
                'success' => true,
                'count' => $count,
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