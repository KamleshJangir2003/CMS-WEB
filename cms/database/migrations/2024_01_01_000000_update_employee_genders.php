<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee;

return new class extends Migration
{
    public function up()
    {
        // Update null/empty gender values to proper male/female distribution
        $employees = Employee::where('user_type', 'employee')
            ->where('is_approved', true)
            ->whereNull('gender')
            ->orWhere('gender', '')
            ->get();

        foreach ($employees as $index => $employee) {
            // Distribute randomly: odd index = male, even index = female
            $gender = ($index % 2 == 0) ? 'male' : 'female';
            $employee->update(['gender' => $gender]);
        }
    }

    public function down()
    {
        // Revert changes if needed
        Employee::where('user_type', 'employee')
            ->update(['gender' => null]);
    }
};