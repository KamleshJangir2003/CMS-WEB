<?php

// database/migrations/xxxx_create_shifts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('shift_type', ['Day', 'Night']);
            $table->date('shift_date');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->decimal('total_hours', 5, 2);
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled'])->default('Scheduled');
            $table->string('assigned_by');
            $table->foreignId('created_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->timestamps();

            // Add composite unique constraint to prevent duplicate shifts
            $table->unique(['employee_id', 'shift_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shifts');
    }
};
