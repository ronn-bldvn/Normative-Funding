<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GraduatesController extends Controller
{
    public function index(Request $request): View
    {
        $viewType     = $request->query('view_type', 'graduate_headcount');
        $studentLevel = $request->query('student_level', 'All');
        $semester     = $request->query('semester', 'All');
        $college      = $request->query('college', 'All');
        $program      = $request->query('program', 'All');

        $filterOptions = $this->getFilterOptions($college, $studentLevel, $semester);

        // Force program to All when college is All
        if ($college === 'All') {
            $program = 'All';
        }

        // If selected program is not in the allowed program list for the selected college, reset it
        if ($program !== 'All' && !in_array($program, $filterOptions['programs'], true)) {
            $program = 'All';
        }

        $payload = $this->buildDashboardData($viewType, $studentLevel, $semester, $college, $program);

        return view('graduates', array_merge($payload, [
            'active_page'      => 'graduates',
            'view_type'        => $viewType,
            'selected_view_type' => $viewType,
            'student_level'    => $studentLevel,
            'semester'         => $semester,
            'selected_college' => $college,
            'selected_program' => $program,
            'colleges'         => $filterOptions['colleges'],
            'programs'         => $filterOptions['programs'],
            'semesters'        => $filterOptions['semesters'],
        ]));
    }

    public function filters(Request $request): JsonResponse
    {
        $college      = $request->query('college', 'All');
        $studentLevel = $request->query('student_level', 'All');
        $semester     = $request->query('semester', 'All');

        return response()->json(
            $this->getFilterOptions($college, $studentLevel, $semester)
        );
    }

    public function dashboard(Request $request): JsonResponse
    {
        $viewType     = $request->query('view_type', 'graduate_headcount');
        $studentLevel = $request->query('student_level', 'All');
        $semester     = $request->query('semester', 'All');
        $college      = $request->query('college', 'All');
        $program      = $request->query('program', 'All');

        $filterOptions = $this->getFilterOptions($college, $studentLevel, $semester);

        if ($college === 'All') {
            $program = 'All';
        }

        if ($program !== 'All' && !in_array($program, $filterOptions['programs'], true)) {
            $program = 'All';
        }

        return response()->json(
            $this->buildDashboardData($viewType, $studentLevel, $semester, $college, $program)
        );
    }

    private function getBaseQuery()
    {
        return DB::table('graduates')
            ->select([
                'student_id',
                'gender',
                'college',
                'program_name',
                'program_major',
                'date_graduated',
                DB::raw("
                    CASE
                        WHEN MONTH(date_graduated) = 2 THEN 'Midyear'
                        ELSE 'Annual'
                    END as derived_semester
                "),
                DB::raw("
                    CASE
                        WHEN LOWER(program_name) LIKE '%master%'
                          OR LOWER(program_name) LIKE '%doctoral%'
                          OR LOWER(program_name) LIKE '%phd%'
                          OR LOWER(program_name) LIKE '%graduate%'
                        THEN 'Postgraduate'
                        ELSE 'Undergraduate'
                    END as derived_student_level
                "),
            ])
            ->whereNotNull('date_graduated');
    }

    private function applyFilters($query, string $studentLevel, string $semester, string $college, string $program)
    {
        if ($studentLevel !== 'All') {
            $query->whereRaw("
                CASE
                    WHEN LOWER(program_name) LIKE '%master%'
                      OR LOWER(program_name) LIKE '%doctoral%'
                      OR LOWER(program_name) LIKE '%phd%'
                      OR LOWER(program_name) LIKE '%graduate%'
                    THEN 'Postgraduate'
                    ELSE 'Undergraduate'
                END = ?
            ", [$studentLevel]);
        }

        if ($semester !== 'All') {
            $query->whereRaw("
                CASE
                    WHEN MONTH(date_graduated) = 2 THEN 'Midyear'
                    ELSE 'Annual'
                END = ?
            ", [$semester]);
        }

        if ($college !== 'All') {
            $query->where('college', $college);
        }

        if ($program !== 'All') {
            $query->where('program_name', $program);
        }

        return $query;
    }

    private function getFilterOptions(string $college = 'All', string $studentLevel = 'All', string $semester = 'All'): array
    {
        $colleges = DB::table('graduates')
            ->whereNotNull('college')
            ->where('college', '!=', '')
            ->distinct()
            ->orderBy('college')
            ->pluck('college')
            ->values()
            ->all();

        $programQuery = DB::table('graduates')
            ->whereNotNull('date_graduated')
            ->whereNotNull('program_name')
            ->where('program_name', '!=', '');

        if ($studentLevel !== 'All') {
            $programQuery->whereRaw("
                CASE
                    WHEN LOWER(program_name) LIKE '%master%'
                      OR LOWER(program_name) LIKE '%doctoral%'
                      OR LOWER(program_name) LIKE '%phd%'
                      OR LOWER(program_name) LIKE '%graduate%'
                    THEN 'Postgraduate'
                    ELSE 'Undergraduate'
                END = ?
            ", [$studentLevel]);
        }

        if ($semester !== 'All') {
            $programQuery->whereRaw("
                CASE
                    WHEN MONTH(date_graduated) = 2 THEN 'Midyear'
                    ELSE 'Annual'
                END = ?
            ", [$semester]);
        }

        if ($college !== 'All') {
            $programQuery->where('college', $college);
        } else {
            return [
                'colleges'       => $colleges,
                'programs'       => [],
                'semesters'      => ['Annual', 'Midyear'],
                'student_levels' => ['Undergraduate', 'Postgraduate'],
                'view_types'     => [
                    ['value' => 'graduate_headcount', 'label' => 'Graduate Headcount'],
                    ['value' => 'demographic_profile', 'label' => 'Demographic Profile'],
                ],
            ];
        }

        $programs = $programQuery
            ->select('program_name')
            ->distinct()
            ->orderBy('program_name')
            ->pluck('program_name')
            ->values()
            ->all();

        return [
            'colleges'       => $colleges,
            'programs'       => $programs,
            'semesters'      => ['Annual', 'Midyear'],
            'student_levels' => ['Undergraduate', 'Postgraduate'],
            'view_types'     => [
                ['value' => 'graduate_headcount', 'label' => 'Graduate Headcount'],
                ['value' => 'demographic_profile', 'label' => 'Demographic Profile'],
            ],
        ];
    }

    private function buildDashboardData(
        string $viewType,
        string $studentLevel,
        string $semester,
        string $college,
        string $program
    ): array {
        // Safety: never allow specific program while college = All
        if ($college === 'All') {
            $program = 'All';
        }

        $query       = $this->applyFilters($this->getBaseQuery(), $studentLevel, $semester, $college, $program);
        $allFiltered = (clone $query)->get();

        $totalGraduates = $allFiltered->count();
        $maleCount      = $allFiltered->filter(fn($r) => in_array(strtolower($r->gender), ['male', 'm']))->count();
        $femaleCount    = $allFiltered->filter(fn($r) => in_array(strtolower($r->gender), ['female', 'f']))->count();

        $undergradCount  = $allFiltered->where('derived_student_level', 'Undergraduate')->count();
        $postgradCount   = $allFiltered->where('derived_student_level', 'Postgraduate')->count();

        $undergradMale   = $allFiltered->where('derived_student_level', 'Undergraduate')
                                       ->filter(fn($r) => in_array(strtolower($r->gender), ['male', 'm']))->count();
        $undergradFemale = $allFiltered->where('derived_student_level', 'Undergraduate')
                                       ->filter(fn($r) => in_array(strtolower($r->gender), ['female', 'f']))->count();
        $postgradMale    = $allFiltered->where('derived_student_level', 'Postgraduate')
                                       ->filter(fn($r) => in_array(strtolower($r->gender), ['male', 'm']))->count();
        $postgradFemale  = $allFiltered->where('derived_student_level', 'Postgraduate')
                                       ->filter(fn($r) => in_array(strtolower($r->gender), ['female', 'f']))->count();

        $dynamicTitle = $this->makeDynamicTitle($viewType, $studentLevel, $semester, $college, $program);
        $groupField   = $college === 'All' ? 'college' : 'program_name';

        // ── Ranking rows (always ungrouped by program for the ranking bar) ───────
        $rankingRows = $this->applyFilters($this->getBaseQuery(), $studentLevel, $semester, $college, 'All')
            ->select($groupField . ' as group_name', DB::raw('COUNT(*) as total'))
            ->whereNotNull($groupField)
            ->where($groupField, '!=', '')
            ->groupBy($groupField)
            ->orderByDesc('total')
            ->get();

        $rankingLabels = $rankingRows->pluck('group_name')->all();
        $rankingValues = $rankingRows->pluck('total')->map(fn($v) => (int) $v)->all();

        // ── Donut rows ────────────────────────────────────────────────────────────
        $donutRows    = $rankingRows;
        $donutTotal   = max(1, $donutRows->sum('total'));
        $donutPercents = $donutRows->map(fn($r) => round(($r->total / $donutTotal) * 100, 1))->values()->all();

        // ── Major chart — only when a specific program is selected ────────────────
        // Shows the breakdown of program_major inside the selected program.
        // If the program has no majors (all NULL / empty), major_chart is null
        // and the blade will fall back to the normal donut.
        $majorChart = null;

        if ($program !== 'All') {
            $majorRows = $this->applyFilters($this->getBaseQuery(), $studentLevel, $semester, $college, $program)
                ->select('program_major as major', DB::raw('COUNT(*) as total'))
                ->whereNotNull('program_major')
                ->where('program_major', '!=', '')
                ->groupBy('program_major')
                ->orderByDesc('total')
                ->get();

            if ($majorRows->isNotEmpty()) {
                $majorTotal = max(1, $majorRows->sum('total'));

                $majorChart = [
                    'title'    => $this->makeMajorDonutTitle($studentLevel, $program),
                    'labels'   => $majorRows->pluck('major')->all(),
                    'values'   => $majorRows->pluck('total')->map(fn($v) => (int) $v)->all(),
                    'percents' => $majorRows->map(fn($r) => round(($r->total / $majorTotal) * 100, 1))->values()->all(),
                ];
            }
        }

        // ── Stacked sex distribution ──────────────────────────────────────────────
        // When a specific program is selected, group by program_major instead;
        // fall back to the normal groupField if there are no recorded majors.
        $sexGroupField  = ($program !== 'All') ? 'program_major' : $groupField;
        $sexProgramFilter = ($program !== 'All') ? $program : 'All';

        $sexRows = $this->applyFilters($this->getBaseQuery(), $studentLevel, $semester, $college, $sexProgramFilter)
            ->select(
                $sexGroupField . ' as group_name',
                DB::raw("SUM(CASE WHEN LOWER(gender) IN ('male','m') THEN 1 ELSE 0 END) as male_count"),
                DB::raw("SUM(CASE WHEN LOWER(gender) IN ('female','f') THEN 1 ELSE 0 END) as female_count"),
                DB::raw("COUNT(*) as total_count")
            )
            ->whereNotNull($sexGroupField)
            ->where($sexGroupField, '!=', '')
            ->groupBy($sexGroupField)
            ->orderBy($sexGroupField)
            ->get();

        // If program is selected but has no majors, fall back to normal program grouping
        if ($program !== 'All' && $sexRows->isEmpty()) {
            $sexRows = $this->applyFilters($this->getBaseQuery(), $studentLevel, $semester, $college, 'All')
                ->select(
                    $groupField . ' as group_name',
                    DB::raw("SUM(CASE WHEN LOWER(gender) IN ('male','m') THEN 1 ELSE 0 END) as male_count"),
                    DB::raw("SUM(CASE WHEN LOWER(gender) IN ('female','f') THEN 1 ELSE 0 END) as female_count"),
                    DB::raw("COUNT(*) as total_count")
                )
                ->whereNotNull($groupField)
                ->where($groupField, '!=', '')
                ->groupBy($groupField)
                ->orderBy($groupField)
                ->get();
        }

        $stackLabels      = [];
        $stackMalePct     = [];
        $stackFemalePct   = [];
        $stackMaleCount   = [];
        $stackFemaleCount = [];

        foreach ($sexRows as $row) {
            $total    = max(1, (int) $row->total_count);
            $male     = (int) $row->male_count;
            $female   = (int) $row->female_count;

            $stackLabels[]      = $row->group_name;
            $stackMaleCount[]   = $male;
            $stackFemaleCount[] = $female;
            $stackMalePct[]     = round(($male   / $total) * 100, 1);
            $stackFemalePct[]   = round(($female / $total) * 100, 1);
        }

        return [
            'page_title_text'    => 'Graduates Overview',
            'dynamic_title'      => $dynamicTitle,
            'selected_view_type' => $viewType,

            'value_boxes' => $viewType === 'graduate_headcount'
                ? [
                    ['title' => 'Total University Graduates',        'value' => $totalGraduates],
                    ['title' => 'Undergraduate Level Graduates',     'value' => $undergradCount],
                    ['title' => 'Postgraduate Level Graduates',      'value' => $postgradCount],
                ]
                : [
                    ['title' => 'Total University Graduates',    'value' => $totalGraduates],
                    ['title' => 'Undergraduate Level Graduates', 'value' => ['male' => $undergradMale, 'female' => $undergradFemale]],
                    ['title' => 'Postgraduate Level Graduates',  'value' => ['male' => $postgradMale,  'female' => $postgradFemale]],
                ],

            'pie_chart' => $this->makePieChart($studentLevel, $maleCount, $femaleCount, $undergradMale, $undergradFemale, $postgradMale, $postgradFemale),

            'donut_chart' => [
                'title'    => $this->makeDonutTitle($studentLevel, $college, $program),
                'labels'   => $donutRows->pluck('group_name')->all(),
                'values'   => $donutRows->pluck('total')->map(fn($v) => (int) $v)->all(),
                'percents' => $donutPercents,
            ],

            // null when no program selected, or when the program has no majors in DB
            'major_chart' => $majorChart,

            'ranking_chart' => [
                'title'        => 'Ranking of Graduates Count by ' . ($college === 'All' ? 'College' : 'Program'),
                'labels'       => $rankingLabels,
                'values'       => $rankingValues,
                'highlight'    => $program !== 'All' ? $program : null,
                'y_axis_label' => $college === 'All' ? 'Colleges' : 'Programs',
                'x_axis_label' => 'Number of Graduates',
            ],

            'stacked_chart' => [
                'title'        => $this->makeStackedTitle($studentLevel, $college, $program, !empty($stackLabels) && $program !== 'All' && $sexGroupField === 'program_major'),
                'labels'       => $stackLabels,
                'male_pct'     => $stackMalePct,
                'female_pct'   => $stackFemalePct,
                'male_count'   => $stackMaleCount,
                'female_count' => $stackFemaleCount,
                'y_axis_label' => $college === 'All'
                    ? 'College'
                    : ($program !== 'All' && !empty($stackLabels) ? 'Major' : 'Program'),
            ],
        ];
    }

    private function makeDynamicTitle(
        string $viewType,
        string $studentLevel,
        string $semester,
        string $college,
        string $program
    ): string {
        if ($viewType !== 'graduate_headcount') {
            return 'Graduates Overview';
        }

        $levelText    = $studentLevel === 'All' ? 'All Levels' : $studentLevel . ' Level';
        $semesterText = $semester === 'All' ? 'All Periods' : $semester;

        if ($program !== 'All') {
            return "Total Graduates: {$program} {$levelText} ({$semesterText})";
        }

        if ($college !== 'All') {
            return "Total Graduates: {$college} {$levelText} ({$semesterText})";
        }

        return "Total Graduates: {$levelText} ({$semesterText})";
    }

    private function makeDonutTitle(string $studentLevel, string $college, string $program): string
    {
        $levelText = $studentLevel === 'All' ? 'All Level' : $studentLevel . ' Level';

        if ($program !== 'All') {
            return "Percentage of {$program} {$levelText} Graduates";
        }

        if ($college !== 'All') {
            return "Percentage of {$college} {$levelText} Graduates";
        }

        return "Percentage of University Graduates by College";
    }

    /**
     * Title for the major breakdown donut shown when a program is selected
     * and that program has recorded majors.
     */
    private function makeMajorDonutTitle(string $studentLevel, string $program): string
    {
        $levelText = $studentLevel === 'All' ? 'All Level' : $studentLevel . ' Level';
        return "Percentage of {$program} {$levelText} Graduates by Major";
    }

    private function makeStackedTitle(
        string $studentLevel,
        string $college,
        string $program,
        bool $hasMajors = false
    ): string {
        if ($college === 'All' && $studentLevel === 'All') {
            return 'Total University Graduates Sex Distribution';
        }

        if ($college === 'All' && $studentLevel === 'Undergraduate') {
            return 'Total Undergraduate Level Graduates Sex Distribution';
        }

        if ($college === 'All' && $studentLevel === 'Postgraduate') {
            return 'Total Postgraduate Level Graduates Sex Distribution';
        }

        $level = $studentLevel === 'All' ? 'All Level' : $studentLevel . ' Level';

        if ($college !== 'All' && $program === 'All') {
            return "{$level} Graduates Sex Distribution of {$college}";
        }

        // Program selected with majors → show "by Major"
        if ($program !== 'All' && $hasMajors) {
            return "{$level} Graduates Sex Distribution of {$program} by Major";
        }

        return "{$level} Graduates Sex Distribution of {$program}";
    }

    /**
     * Build the pie chart data for the Demographic Profile view.
     * Respects the selected student level filter:
     *   - All          → total male / female across all levels
     *   - Undergraduate → undergraduate male / female only
     *   - Postgraduate  → postgraduate male / female only
     */
    private function makePieChart(
        string $studentLevel,
        int $maleCount,
        int $femaleCount,
        int $undergradMale,
        int $undergradFemale,
        int $postgradMale,
        int $postgradFemale
    ): array {
        switch ($studentLevel) {
            case 'Undergraduate':
                return [
                    'title'  => 'Percentage of Undergraduate Level Graduates by Sex',
                    'labels' => ['Male', 'Female'],
                    'values' => [$undergradMale, $undergradFemale],
                ];
            case 'Postgraduate':
                return [
                    'title'  => 'Percentage of Postgraduate Level Graduates by Sex',
                    'labels' => ['Male', 'Female'],
                    'values' => [$postgradMale, $postgradFemale],
                ];
            default: // 'All'
                return [
                    'title'  => 'Percentage of All Graduates by Sex',
                    'labels' => ['Male', 'Female'],
                    'values' => [$maleCount, $femaleCount],
                ];
        }
    }
}
