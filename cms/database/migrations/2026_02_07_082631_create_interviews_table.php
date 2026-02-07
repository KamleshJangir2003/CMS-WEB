<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->string('job_role');
            $table->enum('interview_round', ['HR', 'Technical', 'Manager', 'Final']);
            $table->date('interview_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('interviewer');
            $table->enum('interview_mode', ['Online', 'Offline'])->default('Online');
            $table->enum('meeting_platform', ['Google Meet', 'Zoom', 'Teams'])->nullable();
            $table->string('meeting_link')->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('email_candidate')->default(true);
            $table->boolean('email_interviewer')->default(true);
            $table->boolean('whatsapp_notification')->default(false);
            $table->enum('status', ['Scheduled', 'Completed', 'Cancelled', 'Rescheduled'])->default('Scheduled');
            $table->timestamps();
            
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};