<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin already exists
        $adminExists = Employee::where('email', 'admin@kwikster.com')->exists();

        if (! $adminExists) {
            // Create Super Admin
            Employee::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'email' => 'admin@kwikster.com',
                'phone' => '+91 9876543210',
                'department' => 'Administration',
                'password' => Hash::make('Admin@123'), // Strong password
                'user_type' => 'admin',
                'is_approved' => true,
            ]);

            $this->command->info('Super Admin created successfully!');
            $this->command->info('Email: admin@kwikster.com');
            $this->command->info('Password: Admin@123');
        } else {
            $this->command->info('Admin user already exists!');
        }

        // Create some test employees (optional)
        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@kwikster.com',
                'phone' => '+91 9876543211',
                'department' => 'IT',
                'password' => Hash::make('Employee@123'),
                'user_type' => 'employee',
                'is_approved' => true,
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@kwikster.com',
                'phone' => '+91 9876543212',
                'department' => 'HR',
                'password' => Hash::make('Employee@123'),
                'user_type' => 'employee',
                'is_approved' => true,
            ],
        ];

        foreach ($employees as $employee) {
            if (! Employee::where('email', $employee['email'])->exists()) {
                Employee::create($employee);
            }
        }

        $this->command->info('Test employees created!');
        $this->command->info('Employee Email: john.doe@kwikster.com or jane.smith@kwikster.com');
        $this->command->info('Employee Password: Employee@123');
    }
}
