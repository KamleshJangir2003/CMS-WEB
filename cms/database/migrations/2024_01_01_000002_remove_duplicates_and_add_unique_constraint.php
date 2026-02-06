<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Remove duplicate entries, keeping only the first occurrence
        DB::statement("
            DELETE l1 FROM leads l1
            INNER JOIN leads l2 
            WHERE l1.id > l2.id AND l1.number = l2.number
        ");
        
        // Add unique constraint
        Schema::table('leads', function (Blueprint $table) {
            $table->unique('number');
        });
    }

    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropUnique(['number']);
        });
    }
};