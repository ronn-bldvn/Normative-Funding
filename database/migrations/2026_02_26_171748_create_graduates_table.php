<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduates', function (Blueprint $table) {
            // id: bigint(20) UNSIGNED, AUTO_INCREMENT, PRIMARY
            $table->bigIncrements('id');

            // Nullable columns (screenshot shows NULL = Yes for these)
            $table->string('student_id', 50)->nullable();
            $table->string('first_name', 191)->nullable();
            $table->string('last_name', 191)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('program_name', 255)->nullable();
            $table->string('program_major', 255)->nullable();
            $table->string('college', 255)->nullable();

            // int(11) nullable
            $table->integer('date_graduated')->nullable();

            // Indexes shown in screenshot
            $table->index('date_graduated', 'idx_grad_year');
            $table->index('college', 'idx_college');

            // No timestamps (screenshot shows none)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};
