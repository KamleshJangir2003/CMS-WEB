<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE employee_documents MODIFY COLUMN document_type ENUM(
            'aadhar_card',
            'pan_card', 
            'marksheet_10th',
            'marksheet_12th',
            'graduation',
            'diploma',
            'post_graduation',
            'passbook',
            'joining_letter',
            'salary_slips'
        )");
    }

    public function down()
    {
        DB::statement("ALTER TABLE employee_documents MODIFY COLUMN document_type ENUM(
            'aadhar_card',
            'pan_card',
            'marksheet_10th', 
            'marksheet_12th',
            'graduation',
            'post_graduation',
            'passbook',
            'joining_letter',
            'salary_slips'
        )");
    }
};