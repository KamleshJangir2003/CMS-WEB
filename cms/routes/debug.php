<?php

use Illuminate\Support\Facades\Route;
use App\Models\Employee;

Route::get('/debug-gender', function() {
    // Check current gender distribution
    $employees = Employee::where('user_type', 'employee')
        ->where('is_approved', true)
        ->get(['id', 'first_name', 'last_name', 'gender']);
    
    echo "<h2>Current Employee Gender Status:</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Gender</th></tr>";
    
    foreach($employees as $emp) {
        echo "<tr>";
        echo "<td>{$emp->id}</td>";
        echo "<td>{$emp->first_name} {$emp->last_name}</td>";
        echo "<td>" . ($emp->gender ?: 'NULL') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Set some employees as female manually
    $femaleIds = [2, 4, 6, 8, 10, 12, 14, 16, 18]; // Adjust these IDs
    
    echo "<br><h3>Setting Female Genders:</h3>";
    foreach($femaleIds as $id) {
        $emp = Employee::find($id);
        if($emp && $emp->user_type == 'employee' && $emp->is_approved) {
            $emp->update(['gender' => 'female']);
            echo "Set Employee ID {$id} as female<br>";
        }
    }
    
    // Set remaining as male
    Employee::where('user_type', 'employee')
        ->where('is_approved', true)
        ->whereNotIn('id', $femaleIds)
        ->update(['gender' => 'male']);
    
    echo "<br>Remaining employees set as male";
    
    return "Gender update completed!";
});