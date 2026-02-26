<?php

namespace App\Console\Commands;

use App\Models\FundReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Import funds Excel file into the database.
 *
 * Install the required package first:
 *   composer require phpoffice/phpspreadsheet
 *
 * Then run:
 *   php artisan funds:import /path/to/.xlsx  #depends on the name of the file
 *   php artisan funds:import /path/to/.xlsx --fresh   # truncates tables first
 */
class ImportFundsXlsx extends Command
{
    protected $signature = 'funds:import {file : Path to the funds.xlsx file} {--fresh : Truncate all fund tables before importing}';
    protected $description = 'Import funds Excel (Form G-1, G-2, Form H) into the database';

    // ─── Column indices in the Excel sheet (0-based) ──────────────────────────
    // Allotment / Expenditure columns
    private const AE_COLS = [
        'function'     => 1,   // main function label
        'sub_function' => 2,   // sub-function label (for 5.x rows)
        'gaa_ps'       => 3,
        'gaa_mooe'     => 4,
        'gaa_co'       => 5,
        'gaa_total'    => 6,
        'suc_ps'       => 8,
        'suc_mooe'     => 9,
        'suc_co'       => 10,
        'suc_total'    => 11,
        'combined_ps'  => 13,
        'combined_mooe'=> 14,
        'combined_co'  => 15,
        'combined_total'=> 16,
    ];

    // Income (Form H) columns
    private const INC_COLS = [
        'account'       => 2,   // item label
        'tuition'       => 3,
        'miscellaneous' => 4,
        'other_income'  => 5,
    ];

    public function handle(): int
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return self::FAILURE;
        }

        if ($this->option('fresh')) {
            $this->warn('Truncating fund tables…');
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('fund_report_income_lines')->truncate();
            DB::table('fund_report_ae_lines')->truncate();
            DB::table('fund_reports')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $this->info("Loading: $file");
        $spreadsheet = IOFactory::load($file);

        $years = [];
        foreach ($spreadsheet->getSheetNames() as $name) {
            if (preg_match('/^\d{4}$/', trim($name))) {
                $years[] = trim($name);
            }
        }

        if (empty($years)) {
            $this->error('No year sheets found (expecting sheets named like "2024", "2023", …)');
            return self::FAILURE;
        }

        foreach ($years as $year) {
            $this->info("Processing year: $year");
            $sheet = $spreadsheet->getSheetByName($year);
            $rows  = $sheet->toArray(null, true, true, false); // 0-based numeric rows

            $this->importAllotment($year, $rows);
            $this->importExpenditure($year, $rows);
            $this->importIncome($year, $rows);
        }

        $this->info('Import complete.');
        return self::SUCCESS;
    }

    // ─── Allotment (Form G-1: rows 10-21) ────────────────────────────────────

    private function importAllotment(string $year, array $rows): void
    {
        $report = FundReport::firstOrCreate(['year' => (int)$year, 'type' => 'allotment']);
        DB::table('fund_report_ae_lines')->where('fund_report_id', $report->id)->delete();

        $lines = $this->parseAESection($rows, dataStartRow: 10, totalLabel: 'TOTAL ALLOTMENTS');
        $this->insertAELines($report->id, $lines);

        $this->line("  ✓ Allotment ({$year}): " . count($lines) . ' rows');
    }

    // ─── Expenditure (Form G-2: rows 38-49) ──────────────────────────────────

    private function importExpenditure(string $year, array $rows): void
    {
        $report = FundReport::firstOrCreate(['year' => (int)$year, 'type' => 'expenditure']);
        DB::table('fund_report_ae_lines')->where('fund_report_id', $report->id)->delete();

        $lines = $this->parseAESection($rows, dataStartRow: 38, totalLabel: 'TOTAL EXPENDITURES');
        $this->insertAELines($report->id, $lines);

        $this->line("  ✓ Expenditure ({$year}): " . count($lines) . ' rows');
    }

    /**
     * Generic parser for both Allotment and Expenditure sections.
     * Reads rows until it finds the "TOTAL…" row (inclusive).
     */
    private function parseAESection(array $rows, int $dataStartRow, string $totalLabel): array
    {
        $lines = [];
        $currentFunction = null;

        for ($i = $dataStartRow; $i < count($rows); $i++) {
            $row = $rows[$i];

            $colFn  = trim((string)($row[self::AE_COLS['function']] ?? ''));
            $colSub = trim((string)($row[self::AE_COLS['sub_function']] ?? ''));
            $col0   = trim((string)($row[0] ?? ''));

            // Skip separator / empty rows
            if ($colFn === '' && $colSub === '' && $col0 === '') continue;
            if (str_contains($colFn, '====') || str_contains($colSub, '====')) continue;
            if (str_contains($colFn, 'NOTE:') || str_contains($colFn, 'CERTIFIED')) continue;
            if (str_contains($colFn, 'ORIGINAL DATA') || str_contains($colFn, 'DATA KEYED')) continue;
            if (str_contains($colFn, 'FORM') && str_contains(strtoupper($col0), 'FORM')) break; // next section

            // Check for total row
            $isTotal = str_contains(strtoupper($colFn), strtoupper($totalLabel));

            // Determine function / sub-function label
            $functionName = '';
            $subFunction  = null;

            if ($colFn !== '' && !str_contains($colFn, 'OTHERS')) {
                $functionName = strtoupper($colFn);
                $currentFunction = $functionName;
            } elseif ($colFn === '' && $colSub !== '') {
                // Sub-function row (5.1 Administration, etc.)
                $functionName = $currentFunction ?? 'OTHERS';
                $subFunction  = $colSub;
            } elseif (str_contains($colFn, 'OTHERS')) {
                $functionName = 'OTHERS';
                $currentFunction = 'OTHERS';
            }

            if ($functionName === '' && !$isTotal) continue;
            if ($isTotal) $functionName = strtoupper($totalLabel);

            $lines[] = [
                'function_name'  => $functionName,
                'sub_function'   => $subFunction,
                'is_total'       => $isTotal,
                'gaa_ps'         => $this->num($row, 'gaa_ps'),
                'gaa_mooe'       => $this->num($row, 'gaa_mooe'),
                'gaa_co'         => $this->num($row, 'gaa_co'),
                'gaa_total'      => $this->num($row, 'gaa_total'),
                'suc_ps'         => $this->num($row, 'suc_ps'),
                'suc_mooe'       => $this->num($row, 'suc_mooe'),
                'suc_co'         => $this->num($row, 'suc_co'),
                'suc_total'      => $this->num($row, 'suc_total'),
                'combined_ps'    => $this->num($row, 'combined_ps'),
                'combined_mooe'  => $this->num($row, 'combined_mooe'),
                'combined_co'    => $this->num($row, 'combined_co'),
                'combined_total' => $this->num($row, 'combined_total'),
            ];

            if ($isTotal) break;
        }

        return $lines;
    }

    private function insertAELines(int $reportId, array $lines): void
    {
        $now = now();
        $chunks = array_chunk($lines, 100);
        foreach ($chunks as $chunk) {
            DB::table('fund_report_ae_lines')->insert(
                array_map(fn($l) => array_merge($l, [
                    'fund_report_id' => $reportId,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]), $chunk)
            );
        }
    }

    // ─── Income (Form H: rows 65 onward) ─────────────────────────────────────

    private function importIncome(string $year, array $rows): void
    {
        $report = FundReport::firstOrCreate(['year' => (int)$year, 'type' => 'suc_income']);
        DB::table('fund_report_income_lines')->where('fund_report_id', $report->id)->delete();

        $lines = $this->parseIncomeSection($rows);
        $this->insertIncomeLines($report->id, $lines);

        $this->line("  ✓ Income ({$year}): " . count($lines) . ' rows');
    }

    /**
     * Parse Form H (income) section.
     *
     * Column layout:
     *   col 2: account name
     *   col 3: Tuition & Misc Fees amount
     *   col 4: Miscellaneous amount
     *   col 5: Other Income amount
     *
     * The "GRAND TOTAL FOR SUC" row (col 0 contains it) is stored with is_grand_total=true.
     */
    private function parseIncomeSection(array $rows): array
    {
        // Find the Form H header row dynamically
        $startRow = null;
        foreach ($rows as $i => $row) {
            $col0 = strtoupper(trim((string)($row[0] ?? '')));
            if (str_contains($col0, 'FORM H') || str_contains($col0, 'STATEMENT OF INCOME')) {
                $startRow = $i + 5; // skip header rows
                break;
            }
        }

        if ($startRow === null) {
            $this->warn('  Could not find Form H section.');
            return [];
        }

        $lines = [];
        $tc = self::INC_COLS;

        for ($i = $startRow; $i < count($rows); $i++) {
            $row    = $rows[$i];
            $col0   = trim((string)($row[0] ?? ''));
            $colAcc = trim((string)($row[$tc['account']] ?? ''));

            // Grand total row
            if (str_contains(strtoupper($col0), 'GRAND TOTAL')) {
                // Store each non-zero column as a separate grand-total line
                $tuitAmt  = $this->rawNum($row[$tc['tuition']]);
                $miscAmt  = $this->rawNum($row[$tc['miscellaneous']]);
                $otherAmt = $this->rawNum($row[$tc['other_income']]);

                if ($tuitAmt > 0) {
                    $lines[] = ['account_name' => 'GRAND TOTAL', 'fund_source' => 'tuition',       'amount' => $tuitAmt,  'is_grand_total' => true];
                }
                if ($miscAmt > 0) {
                    $lines[] = ['account_name' => 'GRAND TOTAL', 'fund_source' => 'miscellaneous', 'amount' => $miscAmt,  'is_grand_total' => true];
                }
                if ($otherAmt > 0) {
                    $lines[] = ['account_name' => 'GRAND TOTAL', 'fund_source' => 'other_income',  'amount' => $otherAmt, 'is_grand_total' => true];
                }
                break;
            }

            // Skip separator / empty / note rows
            if ($colAcc === '') continue;
            if (str_contains($colAcc, '====')) continue;
            if (str_contains(strtoupper($colAcc), 'NOTE:')) continue;
            if (str_contains(strtoupper($colAcc), 'SUC CAMPUS')) continue;
            if (str_contains(strtoupper($colAcc), 'TUITION')) continue; // header label
            if (str_contains(strtoupper($colAcc), 'OTHER REVENUE')) continue; // group header

            $tuitAmt  = $this->rawNum($row[$tc['tuition']]);
            $miscAmt  = $this->rawNum($row[$tc['miscellaneous']]);
            $otherAmt = $this->rawNum($row[$tc['other_income']]);

            // Determine fund_source from which column has a value
            if ($tuitAmt > 0) {
                $lines[] = ['account_name' => $colAcc, 'fund_source' => 'tuition',       'amount' => $tuitAmt,  'is_grand_total' => false];
            }
            if ($miscAmt > 0) {
                $lines[] = ['account_name' => $colAcc, 'fund_source' => 'miscellaneous', 'amount' => $miscAmt,  'is_grand_total' => false];
            }
            if ($otherAmt > 0) {
                $lines[] = ['account_name' => $colAcc, 'fund_source' => 'other_income',  'amount' => $otherAmt, 'is_grand_total' => false];
            }
        }

        return $lines;
    }

    private function insertIncomeLines(int $reportId, array $lines): void
    {
        $now    = now();
        $chunks = array_chunk($lines, 100);
        foreach ($chunks as $chunk) {
            DB::table('fund_report_income_lines')->insert(
                array_map(fn($l) => array_merge($l, [
                    'fund_report_id' => $reportId,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]), $chunk)
            );
        }
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function num(array $row, string $key): float
    {
        return $this->rawNum($row[self::AE_COLS[$key]] ?? null);
    }

    private function rawNum(mixed $val): float
    {
        if ($val === null || $val === '' || str_contains((string)$val, '=')) return 0.0;
        $clean = preg_replace('/[^\d.\-]/', '', (string)$val);
        return $clean === '' ? 0.0 : (float)$clean;
    }
}
