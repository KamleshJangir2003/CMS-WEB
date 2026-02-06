<?php

// database/migrations/xxxx_create_employee_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'aadhar_card',
                'pan_card',
                'marksheet_10th',
                'marksheet_12th',
                'graduation',
                'post_graduation',
                'passbook',
                'joining_letter',
                'salary_slips',
            ]);
            $table->string('document_name');
            $table->string('file_path');
            $table->string('file_size')->nullable();
            $table->string('file_extension');
            $table->enum('status', ['pending', 'uploaded', 'verified', 'expired', 'rejected'])->default('pending');
            $table->date('expiry_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_bank_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->enum('account_type', ['savings', 'current']);
            $table->string('passbook_file')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_bank_details');
    }
};
