    @extends('layouts.base')

    @section('page_title')
        <h2 id="dashboardTitle"
            class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-white leading-tight break-words">
            {{ $dynamic_title }}
        </h2>
    @endsection

    @section('content')
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
                                            <option value="All"           {{ $student_level === 'All'           ? 'selected' : '' }}>All Student Levels</option>
                                            <option value="Undergraduate" {{ $student_level === 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                            <option value="Postgraduate"  {{ $student_level === 'Postgraduate'  ? 'selected' : '' }}>Postgraduate</option>
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

                <!-- VALUE BOXES -->
                <div id="valueBoxes" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach($value_boxes as $box)
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 min-h-[132px]">
                            <p class="text-sm font-semibold text-slate-500 mb-3">{{ $box['title'] }}</p>
                            @if(is_array($box['value']))
                                <div class="flex items-center gap-8">
                                    <div>
                                        <p class="text-xs text-slate-500">Male</p>
                                        <p class="text-3xl font-extrabold text-slate-900">{{ $box['value']['male'] ?? 0 }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500">Female</p>
                                        <p class="text-3xl font-extrabold text-slate-900">{{ $box['value']['female'] ?? 0 }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-3xl font-extrabold text-slate-900">{{ $box['value'] }}</p>
                            @endif
                        </div>
                    @endforeach
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

            </div>
        </div>

        {{-- ══════════════════════════════════════════════ STYLES ══ --}}
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

        {{-- ══════════════════════════════════════════════ SCRIPTS ══ --}}
        <script src="https://cdn.plot.ly/plotly-2.32.0.min.js"></script>
        <script>

        // ─── College Abbreviation ──────────────────────────────────────────────────
        /**
         * Extracts a short label from a college name using this priority:
         *
         *  1. If the name already contains "(XXX)" → extract and return "XXX"
         *     e.g. "College of Agriculture (CAG)"  →  "CAG"
         *          "Graduate School - Masters"     →  "GS-Masters"  (no parens, falls through)
         *
         *  2. Explicit fallback map for names that have no parenthetical abbreviation
         *     e.g. "Graduate School - Masters", "DOT-UNI"
         *
         *  3. Auto-generate initials from significant words as a last resort
         */
        const FALLBACK_ABBREVIATIONS = {
            'Graduate School - Masters':  'GS-Masters',
            'Graduate School - Doctoral': 'GS-Doctoral',
            'DOT-UNI':                    'DOT-UNI',
        };

        // Degree prefix replacements — longest/most specific patterns first
        const DEGREE_PREFIXES = [
            { pattern: /^Bachelor of Science in\s+/i,         short: 'BS '   },
            { pattern: /^Bachelor of Science\s*/i,            short: 'BS '   },
            { pattern: /^Bachelor of Arts in\s+/i,            short: 'BA '   },
            { pattern: /^Bachelor of Arts\s*/i,               short: 'BA '   },
            { pattern: /^Bachelor of Technology in\s+/i,      short: 'BTech ' },
            { pattern: /^Bachelor of Engineering in\s+/i,     short: 'BEng ' },
            { pattern: /^Master of Science in\s+/i,           short: 'MS '   },
            { pattern: /^Master of Arts in\s+/i,              short: 'MA '   },
            { pattern: /^Master of Business Administration/i,  short: 'MBA'   },
            { pattern: /^Doctor of Philosophy in\s+/i,        short: 'PhD '  },
            { pattern: /^Bachelor of Secondary Education\s*/i, short: 'BS Secondary Education'},
            { pattern: /^Bachelor of Elementary Education\s*/i, short: 'BS Elementary Education'},
            { pattern: /^Bachelor of Technology and Livelihood Education\s*/i, short: 'BS Technology and Livelihood Education'},
            { pattern: /^Bachelor of Physical Education\s*/i, short: 'BS Physical Education'},
            { pattern: /^Bachelor of Early Childhood Education\s*/i, short: 'BS Early Childhood Education'},
            { pattern: /^Bachelor of Culture & Arts Education\s*/i, short: 'BS Culture & Arts Education'},
        ];

        function abbreviateCollege(name) {
            if (!name) return name;

            // 1. Extract abbreviation already embedded in parentheses
            //    e.g. "College of Fisheries (COF)"  →  "COF"
            const parenMatch = name.match(/\(([^)]+)\)\s*$/);
            if (parenMatch) return parenMatch[1].trim();

            // 2. Explicit fallback map for special names without parentheses
            //    e.g. "Graduate School - Masters", "DOT-UNI"
            if (FALLBACK_ABBREVIATIONS[name]) return FALLBACK_ABBREVIATIONS[name];
            const ci = Object.keys(FALLBACK_ABBREVIATIONS)
                .find(k => k.toLowerCase() === name.toLowerCase());
            if (ci) return FALLBACK_ABBREVIATIONS[ci];

            // 3. Shorten degree names by replacing the long prefix
            //    e.g. "Bachelor of Science in Civil Engineering" → "BS Civil Engineering"
            for (const { pattern, short } of DEGREE_PREFIXES) {
                if (pattern.test(name)) {
                    return (short + name.replace(pattern, '')).trim();
                }
            }

            // 4. No match — return name as-is
            return name;
        }

        // ─── Colour Palette ────────────────────────────────────────────────────────
        const PALETTE = [
            '#016531', '#86090A', '#B29A00', '#6D430F', '#0A6DAF',
            '#00FFFF', '#A70062', '#FF0000', '#4b4b4b',
            '#5A0F8A', '#0F6D5A', '#C46A00',
        ];
        const MALE_COLOR   = '#3B82F6';
        const FEMALE_COLOR = '#EC4899';

        // ─── Shared Plotly helpers ─────────────────────────────────────────────────
        const BASE_CONFIG = { responsive: true, displayModeBar: false };

        const BASE_LAYOUT = {
            font:          { family: 'Inter, system-ui, sans-serif', size: 12 },
            paper_bgcolor: 'rgba(0,0,0,0)',
            plot_bgcolor:  'rgba(0,0,0,0)',
        };

        // ─── Server data ───────────────────────────────────────────────────────────
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

        // ─── Value Boxes ───────────────────────────────────────────────────────────
        function renderValueBoxes(boxes) {
            document.getElementById('valueBoxes').innerHTML = boxes.map(box => {
                if (typeof box.value === 'object' && box.value !== null) {
                    return `
                        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 min-h-[132px]">
                            <p class="text-sm font-semibold text-slate-500 mb-3">${box.title}</p>
                            <div class="flex items-center gap-8">
                                <div>
                                    <p class="text-xs text-slate-500">Male</p>
                                    <p class="text-3xl font-extrabold text-slate-900">${box.value.male ?? 0}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Female</p>
                                    <p class="text-3xl font-extrabold text-slate-900">${box.value.female ?? 0}</p>
                                </div>
                            </div>
                        </div>`;
                }
                return `
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 min-h-[132px]">
                        <p class="text-sm font-semibold text-slate-500 mb-3">${box.title}</p>
                        <p class="text-3xl font-extrabold text-slate-900">${box.value ?? 0}</p>
                    </div>`;
            }).join('');
        }

        // ─── Demographic Pie ───────────────────────────────────────────────────────
        function renderDemographicPie(data) {
            if (!data?.labels?.length) return;

            document.getElementById('demographicChartTitle').textContent =
                data.title || 'Percentage of Undergraduate Level Graduates by Sex';

            Plotly.newPlot('demographicPie', [{
                type: 'pie',
                labels: data.labels,
                values: data.values,
                marker: {
                    colors: [MALE_COLOR, FEMALE_COLOR],
                    line: { color: '#fff', width: 2 }
                },
                texttemplate: '<b>%{percent:.1%}</b>',
                textposition: 'outside',
                hovertemplate: '<b>%{label}</b><br>Count: %{value}<br>Share: %{percent:.1%}<extra></extra>',
                pull: 0.03,
            }], {
                ...BASE_LAYOUT,
                margin: { t: 60, b: 20, l: 20, r: 20 },
                legend: {
                    orientation: 'h',
                    x: 0.5, xanchor: 'center',
                    y: 1.12,
                    font: { size: 13, color: '#374151' },
                    itemsizing: 'constant',
                },
            }, BASE_CONFIG);
        }

        // ─── Headcount Donut ───────────────────────────────────────────────────────
        function renderHeadcountDonut(data) {
            if (!data?.labels?.length) return;

            document.getElementById('donutChartTitle').textContent =
                data.title || 'Graduate Distribution';

            const shortLabels = data.labels.map(abbreviateCollege);

            const programColors = data.program_colors || {};
            const isCollegeSelected = initialData.selected_college && initialData.selected_college !== 'All';

            const colors = data.labels.map((label, i) => {
                if (isCollegeSelected) return programColors[label] || PALETTE[i % PALETTE.length];
                return PALETTE[i % PALETTE.length]; // all-colleges view always uses PALETTE
            });

            console.log(data.program_colors)

            Plotly.newPlot('headcountDonut', [{
                type: 'pie',
                labels: shortLabels,
                values: data.values,
                customdata: data.labels,
                hole: 0.58,
                marker: {
                    colors: colors,
                    line: { color: '#fff', width: 2 }
                },
                texttemplate: '<b>%{percent:.1%}</b>',
                textposition: 'inside',
                hovertemplate:
                    '<b>%{customdata}</b><br>' +
                    'Count: %{value}<br>' +
                    'Share: %{percent:.1%}<extra></extra>',
            }], {
                ...BASE_LAYOUT,
                margin: { t: 10, b: 10, l: 10, r: 160 },
                legend: {
                    orientation: 'v',
                    x: 1.02,
                    xanchor: 'left',
                    y: 0.5,
                    yanchor: 'middle'
                },
            }, BASE_CONFIG);
        }

        // ─── Ranking Bar ───────────────────────────────────────────────────────────
        function renderRankingBar(data) {
            if (!data?.labels?.length) return;

            document.getElementById('rankingChartTitle').textContent =
                data.title || 'Ranking of Graduates Count by College/Department';

            const programColors = data.program_colors || {};
            const isCollegeSelected = initialData.selected_college && initialData.selected_college !== 'All';

            const rows = data.labels
                .map((full, i) => ({
                    short: abbreviateCollege(full),
                    full,
                    val: data.values[i],
                    highlight: data.highlight && full === data.highlight,
                }))
                .sort((a, b) => a.val - b.val);

            const colors = rows.map((row, i) => {
                if (row.highlight) return '#F59E0B';
                if (isCollegeSelected) return programColors[row.full] || PALETTE[i % PALETTE.length];
                return PALETTE[(rows.length - 1 - i) % PALETTE.length]; // ← reversed
            });

            Plotly.newPlot('rankingBar', [{
                    type: 'bar',
                    orientation: 'h',
                    x: rows.map(d => d.val),
                    y: rows.map(d => d.short),
                    hovertext: rows.map(d => `<b>${d.full}</b><br>Graduates: ${d.val}`),
                    hovertemplate: '%{hovertext}<extra></extra>',
                    text: rows.map(d => d.val),
                    textposition: 'outside',
                    cliponaxis: false,
                    marker: {
                        color: colors,
                        line: { color: 'transparent' }
                    },
                }],{
                ...BASE_LAYOUT,
                margin: { t: 10, b: 50, l: 10, r: 50 },
                xaxis: {
                    title: {
                        text: data.x_axis_label || 'Number of Graduates',
                        font: { size: 12 }
                    },
                    gridcolor: '#f1f5f9',
                    zeroline: false
                },
                yaxis: {
                    automargin: true,
                    tickfont: { size: 12, color: '#374151' }
                },
                showlegend: false
            }, BASE_CONFIG);
        }



        // ─── Stacked Sex Bar ───────────────────────────────────────────────────────
        function renderStackedSexBar(data) {
            if (!data?.labels?.length) return;

            document.getElementById('stackedChartTitle').textContent =
                data.title || 'Graduates Sex Distribution';

            const shortLabels = data.labels.map(abbreviateCollege);

            // Rich customdata object for tooltips
            const cd = data.labels.map((l, i) => ({
                full:        l,
                malePct:     data.male_pct[i],
                femalePct:   data.female_pct[i],
                maleCount:   data.male_count[i],
                femaleCount: data.female_count[i],
            }));

            const maleTrace = {
                type: 'bar',
                name: 'Male',
                orientation: 'h',
                x: data.male_pct,
                y: shortLabels,
                customdata: cd,
                text: data.male_pct.map(v => v > 4 ? `${v}%` : ''),
                textposition: 'inside',
                textfont: { color: '#fff', size: 11 },
                marker: { color: MALE_COLOR },
                hovertemplate:
                    '<b>%{customdata.full}</b><br>' +
                    'Male: %{customdata.malePct}% (%{customdata.maleCount})<br>' +
                    'Female: %{customdata.femalePct}% (%{customdata.femaleCount})' +
                    '<extra></extra>',
            };

            const femaleTrace = {
                type: 'bar',
                name: 'Female',
                orientation: 'h',
                x: data.female_pct,
                y: shortLabels,
                customdata: cd,
                text: data.female_pct.map(v => v > 4 ? `${v}%` : ''),
                textposition: 'inside',
                textfont: { color: '#fff', size: 11 },
                marker: { color: FEMALE_COLOR },
                hovertemplate:
                    '<b>%{customdata.full}</b><br>' +
                    'Male: %{customdata.malePct}% (%{customdata.maleCount})<br>' +
                    'Female: %{customdata.femalePct}% (%{customdata.femaleCount})' +
                    '<extra></extra>',
            };

            Plotly.newPlot('stackedSexBar', [maleTrace, femaleTrace], {
                ...BASE_LAYOUT,
                barmode: 'stack',
                margin: { t: 50, b: 50, l: 10, r: 30 },
                legend: {
                    orientation: 'h',
                    x: 0.5, xanchor: 'center',
                    y: 1.06,
                    font: { size: 13, color: '#374151' },
                    itemsizing: 'constant',
                },
                xaxis: {
                    title:     { text: 'Percentage (%)', font: { size: 12 } },
                    range:     [0, 100],
                    gridcolor: '#f1f5f9',
                    zeroline:  false,
                },
                yaxis: {
                    title:      { text: data.y_axis_label || 'College / Department', font: { size: 12 } },
                    automargin: true,
                    tickfont:   { size: 12, color: '#374151' },
                },
            }, BASE_CONFIG);
        }

        // ─── Main Render ───────────────────────────────────────────────────────────
        function renderDashboard(data) {
            document.getElementById('dashboardTitle').textContent = data.dynamic_title;
            renderValueBoxes(data.value_boxes);

            const isDemographic = data.selected_view_type === 'demographic_profile';
            document.getElementById('demographicSection').classList.toggle('hidden', !isDemographic);
            document.getElementById('headcountSection').classList.toggle('hidden', isDemographic);

            if (isDemographic) {
                renderDemographicPie(data.pie_chart);
            } else {
                // When a specific program is selected AND major data is available,
                // show the major breakdown in the donut instead of program distribution.
                const isProgramSelected = data.selected_program && data.selected_program !== 'All';
                const donutData = (isProgramSelected && data.major_chart?.labels?.length)
                    ? data.major_chart
                    : data.donut_chart;

                renderHeadcountDonut(donutData);
                renderRankingBar(data.ranking_chart);
                renderStackedSexBar(data.stacked_chart);
            }
        }

        // ─── Init ──────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            renderDashboard(initialData);

            const form         = document.getElementById('graduatesFilterForm');
            const college      = document.getElementById('college');
            const program      = document.getElementById('program');
            const viewType     = document.getElementById('view_type');
            const studentLevel = document.getElementById('student_level');
            const semester     = document.getElementById('semester');

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
