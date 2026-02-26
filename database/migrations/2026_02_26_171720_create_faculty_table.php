<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty', function (Blueprint $table) {
            // NOTE: Your screenshot shows NO id column and NO timestamps.
            // So we will match that exactly.

            $table->string('faculty_name', 255);          // NOT NULL
            $table->string('faculty_rank', 255);          // NOT NULL
            $table->string('college', 4);                 // NOT NULL
            $table->string('tenured_status', 255);        // NOT NULL
            $table->string('gender', 2);                  // NOT NULL
            $table->string('teaching_cat', 255);          // NOT NULL

            // No indexes (screenshot shows "No index defined!")
            // No timestamps (screenshot shows none)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty');
    }
};
