<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Employee::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@admin.com',
            'phone' => '1234567890',
            'department' => 'IT',
            'password' => Hash::make('admin123'),
            'user_type' => 'admin',
            'is_approved' => true,
        ]);
    }
}