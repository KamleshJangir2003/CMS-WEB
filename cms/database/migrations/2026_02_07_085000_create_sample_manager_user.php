<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;

return new class extends Migration
{
    public function up(): void
    {
        // Create a sample manager user
        Employee::create([
            'first_name' => 'Raj',
            'last_name' => 'Manager',
            'email' => 'raj@kwikster.com',
            'password' => Hash::make('password123'),
            'phone' => '9876543210',
            'department' => 'Management',
            'user_type' => 'manager',
            'is_approved' => true,
        ]);
    }

    public function down(): void
    {
        Employee::where('email', 'raj@kwikster.com')->delete();
    }
};