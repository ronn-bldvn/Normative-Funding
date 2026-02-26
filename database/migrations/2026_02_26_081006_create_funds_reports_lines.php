<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allotment & Expenditure lines (Form G-1 and G-2)
        // Each row = one function (INSTRUCTION, RESEARCH, etc.) with GAA / SUC / Combined columns
        Schema::create('fund_report_ae_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_report_id')->constrained('fund_reports')->cascadeOnDelete();
            $table->string('function_name');        // INSTRUCTION, RESEARCH, TOTAL ALLOTMENTS, etc.
            $table->string('sub_function')->nullable(); // 5.1 Administration, 5.2 Auxiliary Services, etc.
            $table->boolean('is_total')->default(false); // marks the TOTAL row

            // GAA (Fund 101)
            $table->decimal('gaa_ps', 18, 2)->default(0);
            $table->decimal('gaa_mooe', 18, 2)->default(0);
            $table->decimal('gaa_co', 18, 2)->default(0);
            $table->decimal('gaa_total', 18, 2)->default(0);

            // SUC Income (Fund 164)
            $table->decimal('suc_ps', 18, 2)->default(0);
            $table->decimal('suc_mooe', 18, 2)->default(0);
            $table->decimal('suc_co', 18, 2)->default(0);
            $table->decimal('suc_total', 18, 2)->default(0);

            // Combined (GAA + SUC)
            $table->decimal('combined_ps', 18, 2)->default(0);
            $table->decimal('combined_mooe', 18, 2)->default(0);
            $table->decimal('combined_co', 18, 2)->default(0);
            $table->decimal('combined_total', 18, 2)->default(0);

            $table->timestamps();

            $table->index('fund_report_id');
        });

        // Income lines (Form H)
        Schema::create('fund_report_income_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_report_id')->constrained('fund_reports')->cascadeOnDelete();
            $table->string('account_name');          // Tuition, Laboratory, Certification, etc.
            // Which column this item belongs to (tuition / miscellaneous / other_income)
            $table->enum('fund_source', ['tuition', 'miscellaneous', 'other_income']);
            $table->decimal('amount', 18, 2)->default(0);
            $table->boolean('is_grand_total')->default(false);
            $table->timestamps();

            $table->index(['fund_report_id', 'fund_source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fund_report_income_lines');
        Schema::dropIfExists('fund_report_ae_lines');
    }
};
