<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->enum('result', ['Pending', 'Selected', 'Rejected'])->default('Pending')->after('status');
            $table->text('rejection_reason')->nullable()->after('result');
            $table->decimal('current_ctc', 10, 2)->nullable()->after('rejection_reason');
            $table->decimal('expected_ctc', 10, 2)->nullable()->after('current_ctc');
            $table->decimal('offered_ctc', 10, 2)->nullable()->after('expected_ctc');
            $table->enum('offer_status', ['Pending', 'Accepted', 'Rejected'])->nullable()->after('offered_ctc');
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['result', 'rejection_reason', 'current_ctc', 'expected_ctc', 'offered_ctc', 'offer_status']);
        });
    }
};