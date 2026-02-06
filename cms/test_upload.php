<?php
// Simple test script to check Excel upload functionality
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "Testing Excel upload functionality...\n\n";

// Check if PhpSpreadsheet is working
try {
    echo "✓ PhpSpreadsheet loaded successfully\n";
    
    // Create a simple test Excel file
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Add test data
    $sheet->setCellValue('A1', 'Number');
    $sheet->setCellValue('B1', 'Name');
    $sheet->setCellValue('A2', '9876543210');
    $sheet->setCellValue('B2', 'Test User');
    $sheet->setCellValue('A3', '9876543211');
    $sheet->setCellValue('B3', 'Another User');
    
    // Save test file
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $testFile = 'test_leads.xlsx';
    $writer->save($testFile);
    
    echo "✓ Test Excel file created: $testFile\n";
    
    // Try to read the file back
    $loadedSpreadsheet = IOFactory::load($testFile);
    $worksheet = $loadedSpreadsheet->getActiveSheet();
    $rows = $worksheet->toArray(null, true, true, true);
    
    echo "✓ File read successfully\n";
    echo "Rows found: " . count($rows) . "\n";
    
    foreach ($rows as $index => $row) {
        echo "Row $index: A=" . ($row['A'] ?? 'empty') . ", B=" . ($row['B'] ?? 'empty') . "\n";
    }
    
    // Clean up
    unlink($testFile);
    echo "\n✓ Test completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}