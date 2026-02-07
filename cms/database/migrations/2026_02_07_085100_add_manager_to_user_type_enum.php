<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE employees MODIFY COLUMN user_type ENUM('admin', 'employee', 'client', 'manager') DEFAULT 'employee'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE employees MODIFY COLUMN user_type ENUM('admin', 'employee', 'client') DEFAULT 'employee'");
    }
};