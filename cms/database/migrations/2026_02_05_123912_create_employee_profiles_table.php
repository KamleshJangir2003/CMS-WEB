<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();

            // Relation with employees table
            $table->unsignedBigInteger('employee_id');

            // Personal Details
            $table->string('full_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('dob');
            $table->string('contact_number');
            $table->string('guardian_number');
            $table->string('gender');

            // Address Details
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode');

            // Previous Company
            $table->string('last_company_name');
            $table->decimal('last_salary_in_hand', 10, 2);
            $table->decimal('last_salary_ctc', 10, 2);
            $table->string('uan_number');

            // Bank Details
            $table->string('bank_name');
            $table->string('ifsc_code');
            $table->string('bank_account_number');

            // Selfie
            $table->string('selfie');

            $table->timestamps();

            // Foreign key
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
