<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fund_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->enum('type', ['allotment', 'expenditure', 'suc_income']);
            $table->timestamps();

            $table->unique(['year', 'type']);
            $table->index('year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_reports');
    }
};
