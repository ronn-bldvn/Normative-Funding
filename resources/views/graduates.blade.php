@extends('layouts.base')

@section('page_title')
    <h2 id="dashboardTitle"
        class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-white leading-tight break-words">
        {{ $dynamic_title }}
    </h2>
@endsection

@section('content')
    @php
        $firstBoxValue = $value_boxes[0]['value'] ?? 0;
        $total_graduates = is_array($firstBoxValue)
            ? (($firstBoxValue['male'] ?? 0) + ($firstBoxValue['female'] ?? 0))
            : (int) $firstBoxValue;
        $has_data = $total_graduates > 0;
    @endphp

    <div class="w-full bg-slate-50 min-h-screen">

        <!-- TOP BAR -->
        <div class="sticky top-0 z-30 bg-[#BDBDBD] px-4 lg:px-6 py-4 shadow-sm">
            <div class="flex flex-col gap-4">
                <div>
                    <form method="GET" action="{{ route('graduates') }}" id="graduatesFilterForm" class="w-full xl:w-auto">
                        <div class="bg-white/90 rounded-2xl border border-slate-200 shadow-sm p-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">

                                <!-- View Type -->
                                <div>
                                    <label for="view_type" class="block text-xs font-semibold text-slate-600 mb-1">View Type</label>
                                    <select name="view_type" id="view_type" class="filter-select">
                                        <option value="graduate_headcount" {{ $selected_view_type === 'graduate_headcount' ? 'selected' : '' }}>Graduate Headcount</option>
                                        <option value="demographic_profile" {{ $selected_view_type === 'demographic_profile' ? 'selected' : '' }}>Demographic Profile</option>
                                    </select>
                                </div>

                                <!-- Student Level -->
                                <div>
                                    <label for="student_level" class="block text-xs font-semibold text-slate-600 mb-1">Student Level</label>
                                    <select name="student_level" id="student_level" class="filter-select">
                                        <option value="All"           {{ $student_level === 'All' ? 'selected' : '' }}>All Student Levels</option>
                                        <option value="Undergraduate" {{ $student_level === 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                        <option value="Postgraduate"  {{ $student_level === 'Postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                    </select>
                                </div>

                                <!-- Semester -->
                                <div>
                                    <label for="semester" class="block text-xs font-semibold text-slate-600 mb-1">Semester</label>
                                    <select name="semester" id="semester" class="filter-select">
                                        <option value="All" {{ $semester === 'All' ? 'selected' : '' }}>All Semesters</option>
                                        @foreach($semesters as $sem)
                                            <option value="{{ $sem }}" {{ $semester === $sem ? 'selected' : '' }}>{{ $sem }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- College -->
                                <div>
                                    <label for="college" class="block text-xs font-semibold text-slate-600 mb-1">College</label>
                                    <select name="college" id="college" class="filter-select">
                                        <option value="All" {{ $selected_college === 'All' ? 'selected' : '' }}>All Colleges</option>
                                        @foreach($colleges as $c)
                                            <option value="{{ $c }}" {{ $selected_college === $c ? 'selected' : '' }}>{{ $c }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if($selected_college === 'All')
                                    <input type="hidden" name="program" value="All">
                                @endif

                                <!-- Program -->
                                <div>
                                    <label for="program" class="block text-xs font-semibold text-slate-600 mb-1">Program</label>
                                    <select name="program" id="program" class="filter-select"
                                        {{ $selected_college === 'All' ? 'disabled' : '' }}>
                                        <option value="All" {{ $selected_program === 'All' ? 'selected' : '' }}>All Programs</option>
                                        @foreach($programs as $p)
                                            <option value="{{ $p }}" {{ $selected_program === $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="px-4 lg:px-6 py-6 space-y-6">

            @if($has_data)

                <!-- VALUE BOXES -->
                <div id="valueBoxes" class="py-6 sm:py-8 animate-card-in">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        @foreach($value_boxes as $index => $box)
                            @php
                                $isFirst = $index === 0;

                                $icons = [
                                    'fa-solid fa-chart-line',
                                    'fa-solid fa-users',
                                    'fa-solid fa-user-graduate',
                                    'fa-solid fa-building-columns',
                                    'fa-solid fa-layer-group',
                                    'fa-solid fa-circle-info',
                                ];

                                $icon = $icons[$index % count($icons)];
                            @endphp

                            <div class="relative {{ $isFirst ? 'bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg' : 'bg-white shadow-md text-gray-900' }} rounded-2xl p-6">

                                <div class="absolute top-4 left-4 w-12 h-12 {{ $isFirst ? 'bg-white/90' : 'bg-green-500' }} rounded-lg flex items-center justify-center">
                                    <i class="{{ $icon }} {{ $isFirst ? 'text-green-600' : 'text-white' }} text-xl"></i>
                                </div>

                                <div class="mt-12 text-right">
                                    @if(is_array($box['value']))
                                        <div class="grid grid-cols-2 gap-4 text-right">
                                            <div>
                                                <p class="text-xs {{ $isFirst ? 'text-white/80' : 'text-slate-500' }}">Male</p>
                                                <p class="font-[inter] text-[32px] md:text-[44px] font-extrabold leading-tight">
                                                    {{ $box['value']['male'] ?? 0 }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-xs {{ $isFirst ? 'text-white/80' : 'text-slate-500' }}">Female</p>
                                                <p class="font-[inter] text-[32px] md:text-[44px] font-extrabold leading-tight">
                                                    {{ $box['value']['female'] ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="font-[inter] text-[32px] md:text-[44px] font-extrabold leading-tight">
                                            {{ $box['value'] }}
                                        </p>
                                    @endif

                                    <p class="text-[20px] md:text-[16px] font-[inter] font-semibold {{ $isFirst ? 'text-white mt-1' : 'text-gray-500 mt-1' }}">
                                        {{ $box['title'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- DEMOGRAPHIC PROFILE VIEW -->
                <div id="demographicSection" class="{{ $selected_view_type === 'demographic_profile' ? '' : 'hidden' }}">
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 id="demographicChartTitle" class="text-base font-bold mb-4">
                            {{ $pie_chart['title'] ?? 'Percentage of Undergraduate Level Graduates by Sex' }}
                        </h3>
                        <div id="demographicPie" style="height:420px;"></div>
                    </div>
                </div>

                <!-- HEADCOUNT VIEW -->
                <div id="headcountSection" class="{{ $selected_view_type === 'graduate_headcount' ? '' : 'hidden' }} space-y-6">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                            <h3 id="donutChartTitle" class="text-base font-bold mb-4">
                                {{ $donut_chart['title'] ?? 'Graduate Distribution' }}
                            </h3>
                            <div id="headcountDonut" style="height:420px;"></div>
                        </div>

                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                            <h3 id="rankingChartTitle" class="text-base font-bold mb-4">
                                {{ $ranking_chart['title'] ?? 'Ranking of Graduates Count by College/Department' }}
                            </h3>
                            <div id="rankingBar" style="height:420px;"></div>
                        </div>

                    </div>

                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                        <h3 id="stackedChartTitle" class="text-base font-bold mb-4">
                            {{ $stacked_chart['title'] ?? 'Graduates Sex Distribution' }}
                        </h3>
                        <div id="stackedSexBar" style="height:520px;"></div>
                    </div>
                </div>

            @else

                <!-- NO DATA STATE -->
                <div class="flex flex-col items-center justify-center py-24 px-6 text-center">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-6 shadow-inner">
                        <i class="fa-solid fa-filter-circle-xmark text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="font-['Bricolage_Grotesque'] text-2xl font-extrabold text-gray-700 mb-2">
                        No Data Found
                    </h3>
                    <p class="text-gray-400 text-sm max-w-sm">
                        No graduate records match the selected filter criteria. Try adjusting or resetting the filters above.
                    </p>
                    <a href="{{ route('graduates') }}"
                        class="mt-6 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow transition-colors duration-200">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        Reset Filters
                    </a>
                </div>

            @endif

        </div>
    </div>

    <style>
        .filter-select {
            width: 100%;
            min-width: 0;
            height: 42px;
            border-radius: 9999px;
            background: #f8fafc;
            border: 1px solid #dbe2ea;
            color: #111827;
            font-size: 13px;
            font-weight: 600;
            padding: 0 14px;
            outline: none;
            box-shadow: 0 1px 2px rgba(0,0,0,.04);
            transition: all .2s ease;
        }
        .filter-select:focus {
            border-color: #94a3b8;
            box-shadow: 0 0 0 3px rgba(148,163,184,.20);
            background: #fff;
        }
        #program { min-width: 220px; }
        @media (max-width: 1279px) { #program { min-width: 0; } }
    </style>

    @if($has_data)
        <script src="https://cdn.plot.ly/plotly-2.32.0.min.js"></script>
        <script>
            const FALLBACK_ABBREVIATIONS = {
                'Graduate School - Masters':  'GS-Masters',
                'Graduate School - Doctoral': 'GS-Doctoral',
                'DOT-UNI':                    'DOT-UNI',
            };

            const DEGREE_PREFIXES = [
                { pattern: /^Bachelor of Science in\s+/i, short: 'BS ' },
                { pattern: /^Bachelor of Science\s*/i, short: 'BS ' },
                { pattern: /^Bachelor of Arts in\s+/i, short: 'BA ' },
                { pattern: /^Bachelor of Arts\s*/i, short: 'BA ' },
                { pattern: /^Bachelor of Technology in\s+/i, short: 'BTech ' },
                { pattern: /^Bachelor of Engineering in\s+/i, short: 'BEng ' },
                { pattern: /^Master of Science in\s+/i, short: 'MS ' },
                { pattern: /^Master of Arts in\s+/i, short: 'MA ' },
                { pattern: /^Master of Business Administration\s*/i, short: 'MBA' },
                { pattern: /^Doctor of Philosophy in\s+/i, short: 'PhD ' },
                { pattern: /^Bachelor of Secondary Education\s*/i, short: 'BS Secondary Education' },
                { pattern: /^Bachelor of Elementary Education\s*/i, short: 'BS Elementary Education' },
                { pattern: /^Bachelor of Technology and Livelihood Education\s*/i, short: 'BS Technology and Livelihood Education' },
                { pattern: /^Bachelor of Physical Education\s*/i, short: 'BS Physical Education' },
                { pattern: /^Bachelor of Early Childhood Education\s*/i, short: 'BS Early Childhood Education' },
                { pattern: /^Bachelor of Culture\s*&\s*Arts Education\s*/i, short: 'BS Culture & Arts Education' },
            ];

            const PALETTE = [
                '#016531', '#86090A', '#B29A00', '#6D430F', '#0A6DAF',
                '#00FFFF', '#A70062', '#FF0000', '#4b4b4b',
                '#5A0F8A', '#0F6D5A', '#C46A00',
            ];

            const MALE_COLOR   = '#3B82F6';
            const FEMALE_COLOR = '#EC4899';

            const BASE_CONFIG = { responsive: true, displayModeBar: false };

            const BASE_LAYOUT = {
                font: { family: 'Inter, system-ui, sans-serif', size: 12 },
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)',
            };

            const initialData = {{ Js::from([
                'selected_view_type' => $selected_view_type,
                'dynamic_title'      => $dynamic_title,
                'value_boxes'        => $value_boxes,
                'pie_chart'          => $pie_chart,
                'donut_chart'        => $donut_chart,
                'major_chart'        => $major_chart ?? null,
                'ranking_chart'      => $ranking_chart,
                'stacked_chart'      => $stacked_chart,
                'selected_college'   => $selected_college,
                'selected_program'   => $selected_program,
            ]) }};

            function shortenDegreeName(name) {
                if (!name) return name;
                let cleaned = String(name).trim();
                for (const { pattern, short } of DEGREE_PREFIXES) {
                    if (pattern.test(cleaned)) {
                        return (short + cleaned.replace(pattern, '')).trim();
                    }
                }
                return cleaned;
            }

            function abbreviateProgram(name) {
                if (!name) return name;
                let cleaned = String(name).trim();
                cleaned = cleaned.replace(/\s*\((DOT-Uni|DOT UNI|GS-Masters|GS-Doctoral)\)\s*$/i, '');
                return shortenDegreeName(cleaned);
            }

            function abbreviateCollege(name) {
                if (!name) return name;
                let cleaned = String(name).trim();
                const parenMatch = cleaned.match(/\(([^)]+)\)\s*$/);
                if (parenMatch) return parenMatch[1].trim();
                if (FALLBACK_ABBREVIATIONS[cleaned]) return FALLBACK_ABBREVIATIONS[cleaned];
                const ci = Object.keys(FALLBACK_ABBREVIATIONS)
                    .find(k => k.toLowerCase() === cleaned.toLowerCase());
                if (ci) return FALLBACK_ABBREVIATIONS[ci];
                return cleaned;
            }

            function getLabelFormatter(data, mode = 'auto') {
                const isCollegeSelected = initialData.selected_college && initialData.selected_college !== 'All';
                const isProgramSelected = initialData.selected_program && initialData.selected_program !== 'All';
                if (mode === 'college') return abbreviateCollege;
                if (mode === 'program') return abbreviateProgram;
                if (!isCollegeSelected) return abbreviateCollege;
                if (isProgramSelected) return abbreviateProgram;
                return abbreviateProgram;
            }

            function renderDemographicPie(data) {
                if (!data?.labels?.length) return;
                document.getElementById('demographicChartTitle').textContent =
                    data.title || 'Percentage of Undergraduate Level Graduates by Sex';
                Plotly.newPlot('demographicPie', [{
                    type: 'pie',
                    labels: data.labels,
                    values: data.values,
                    marker: { colors: [MALE_COLOR, FEMALE_COLOR], line: { color: '#fff', width: 2 } },
                    texttemplate: '<b>%{percent:.1%}</b>',
                    textposition: 'outside',
                    hovertemplate: '<b>%{label}</b><br>Count: %{value}<br>Share: %{percent:.1%}<extra></extra>',
                    pull: 0.03,
                }], {
                    ...BASE_LAYOUT,
                    margin: { t: 60, b: 20, l: 20, r: 20 },
                    legend: {
                        orientation: 'h', x: 0.5, xanchor: 'center', y: 1.12,
                        font: { size: 13, color: '#374151' }, itemsizing: 'constant',
                    },
                }, BASE_CONFIG);
            }

            function renderHeadcountDonut(data) {
                if (!data?.labels?.length) return;
                document.getElementById('donutChartTitle').textContent =
                    data.title || 'Graduate Distribution';
                const formatter = getLabelFormatter(data);
                const shortLabels = data.labels.map(formatter);
                const programColors = data.program_colors || {};
                const isCollegeSelected = initialData.selected_college && initialData.selected_college !== 'All';
                const colors = data.labels.map((label, i) => {
                    if (isCollegeSelected) return programColors[label] || PALETTE[i % PALETTE.length];
                    return PALETTE[i % PALETTE.length];
                });
                Plotly.newPlot('headcountDonut', [{
                    type: 'pie',
                    labels: shortLabels,
                    values: data.values,
                    customdata: data.labels,
                    hole: 0.58,
                    marker: { colors: colors, line: { color: '#fff', width: 2 } },
                    texttemplate: '<b>%{percent:.1%}</b>',
                    textposition: 'inside',
                    hovertemplate: '<b>%{customdata}</b><br>Count: %{value}<br>Share: %{percent:.1%}<extra></extra>',
                }], {
                    ...BASE_LAYOUT,
                    margin: { t: 10, b: 10, l: 10, r: 160 },
                    legend: { orientation: 'v', x: 1.02, xanchor: 'left', y: 0.5, yanchor: 'middle' },
                }, BASE_CONFIG);
            }

            function renderRankingBar(data) {
                if (!data?.labels?.length) return;
                document.getElementById('rankingChartTitle').textContent =
                    data.title || 'Ranking of Graduates Count by College/Department';
                const programColors = data.program_colors || {};
                const isCollegeSelected = initialData.selected_college && initialData.selected_college !== 'All';
                const formatter = isCollegeSelected ? abbreviateProgram : abbreviateCollege;
                const rows = data.labels
                    .map((full, i) => ({
                        short: formatter(full),
                        full,
                        val: data.values[i],
                        highlight: data.highlight && full === data.highlight,
                    }))
                    .sort((a, b) => a.val - b.val);
                const colors = rows.map((row, i) => {
                    if (row.highlight) return '#F59E0B';
                    if (isCollegeSelected) return programColors[row.full] || PALETTE[i % PALETTE.length];
                    return PALETTE[(rows.length - 1 - i) % PALETTE.length];
                });
                Plotly.newPlot('rankingBar', [{
                    type: 'bar',
                    orientation: 'h',
                    x: rows.map(d => d.val),
                    y: rows.map(d => d.short),
                    customdata: rows.map(d => d.full),
                    hovertemplate: '<b>%{customdata}</b><br>Graduates: %{x}<extra></extra>',
                    text: rows.map(d => d.val),
                    textposition: 'outside',
                    cliponaxis: false,
                    marker: { color: colors, line: { color: 'transparent' } },
                }], {
                    ...BASE_LAYOUT,
                    margin: { t: 10, b: 50, l: 140, r: 50 },
                    xaxis: {
                        title: { text: data.x_axis_label || 'Number of Graduates', font: { size: 12 } },
                        gridcolor: '#f1f5f9', zeroline: false
                    },
                    yaxis: { automargin: true, tickfont: { size: 12, color: '#374151' } },
                    showlegend: false
                }, BASE_CONFIG);
            }

            function renderStackedSexBar(data) {
                if (!data?.labels?.length) return;
                document.getElementById('stackedChartTitle').textContent =
                    data.title || 'Graduates Sex Distribution';
                const formatter = getLabelFormatter(data);
                const shortLabels = data.labels.map(formatter);
                const cd = data.labels.map((l, i) => ({
                    full: l,
                    malePct: data.male_pct[i],
                    femalePct: data.female_pct[i],
                    maleCount: data.male_count[i],
                    femaleCount: data.female_count[i],
                }));
                const maleTrace = {
                    type: 'bar', name: 'Male', orientation: 'h',
                    x: data.male_pct, y: shortLabels, customdata: cd,
                    text: data.male_pct.map(v => v > 4 ? `${v}%` : ''),
                    textposition: 'inside', textfont: { color: '#fff', size: 11 },
                    marker: { color: MALE_COLOR },
                    hovertemplate: '<b>%{customdata.full}</b><br>Male: %{customdata.malePct}% (%{customdata.maleCount})<br>Female: %{customdata.femalePct}% (%{customdata.femaleCount})<extra></extra>',
                };
                const femaleTrace = {
                    type: 'bar', name: 'Female', orientation: 'h',
                    x: data.female_pct, y: shortLabels, customdata: cd,
                    text: data.female_pct.map(v => v > 4 ? `${v}%` : ''),
                    textposition: 'inside', textfont: { color: '#fff', size: 11 },
                    marker: { color: FEMALE_COLOR },
                    hovertemplate: '<b>%{customdata.full}</b><br>Male: %{customdata.malePct}% (%{customdata.maleCount})<br>Female: %{customdata.femalePct}% (%{customdata.femaleCount})<extra></extra>',
                };
                Plotly.newPlot('stackedSexBar', [maleTrace, femaleTrace], {
                    ...BASE_LAYOUT,
                    barmode: 'stack',
                    margin: { t: 50, b: 50, l: 140, r: 30 },
                    legend: {
                        orientation: 'h', x: 0.5, xanchor: 'center', y: 1.06,
                        font: { size: 13, color: '#374151' }, itemsizing: 'constant',
                    },
                    xaxis: {
                        title: { text: 'Percentage (%)', font: { size: 12 } },
                        range: [0, 100], gridcolor: '#f1f5f9', zeroline: false,
                    },
                    yaxis: {
                        title: { text: data.y_axis_label || 'College / Department', font: { size: 12 } },
                        automargin: true, tickfont: { size: 12, color: '#374151' },
                    },
                }, BASE_CONFIG);
            }

            function renderDashboard(data) {
                document.getElementById('dashboardTitle').textContent = data.dynamic_title;
                const isDemographic = data.selected_view_type === 'demographic_profile';
                document.getElementById('demographicSection').classList.toggle('hidden', !isDemographic);
                document.getElementById('headcountSection').classList.toggle('hidden', isDemographic);
                if (isDemographic) {
                    renderDemographicPie(data.pie_chart);
                } else {
                    const isProgramSelected = data.selected_program && data.selected_program !== 'All';
                    const donutData = (isProgramSelected && data.major_chart?.labels?.length)
                        ? data.major_chart
                        : data.donut_chart;
                    renderHeadcountDonut(donutData);
                    renderRankingBar(data.ranking_chart);
                    renderStackedSexBar(data.stacked_chart);
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                renderDashboard(initialData);
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('graduatesFilterForm');
            const college = document.getElementById('college');
            const program = document.getElementById('program');
            const viewType = document.getElementById('view_type');
            const studentLevel = document.getElementById('student_level');
            const semester = document.getElementById('semester');

            function syncProgramState() {
                const isAll = college.value === 'All';
                if (isAll) {
                    program.value = 'All';
                    program.setAttribute('disabled', 'disabled');
                    program.classList.add('opacity-60', 'cursor-not-allowed');
                } else {
                    program.removeAttribute('disabled');
                    program.classList.remove('opacity-60', 'cursor-not-allowed');
                }
            }

            syncProgramState();
            college.addEventListener('change', syncProgramState);

            [viewType, studentLevel, semester, college, program].forEach(el => {
                el.addEventListener('change', () => {
                    if (college.value === 'All') program.value = 'All';
                    form.submit();
                });
            });
        });
    </script>
@endsection
