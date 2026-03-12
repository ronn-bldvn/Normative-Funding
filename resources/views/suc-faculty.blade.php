@extends('layouts.base')

@section('page_title')
    <h2
        class="font-bricolage text-3xl md:text-4xl font-extrabold text-white leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.2)]">
        Total Faculty
        @if($selected_college)
            of {{ strtoupper($selected_college) }}
        @else
            of the University
        @endif
    </h2>
@endsection

@section('content')
    <div class="">

        <!-- FILTER BAR -->
        <div class="sticky top-0 z-50 flex justify-end items-center h-12 bg-[#BDBDBD] px-6">
            <form id="facultyFilterForm" method="GET" action="{{ route('suc-faculty') }}"
                class="flex flex-row gap-3 items-center">

                @foreach($filter_columns as $col)
                    @php $param = $filter_param_keys[$col] ?? $col; @endphp

                    <div class="flex items-center gap-3">
                        <label class="font-['Bricolage_Grotesque'] font-extrabold text-xs">{{ $col }}</label>
                        <div class="relative w-40">
                            <select name="{{ $param }}"
                                class="w-40 appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer text-xs p-2">
                                <option class="text-xs" value="All">All</option>

                                @foreach(($filter_options[$col] ?? []) as $val)
                                    <option class="text-xs" value="{{ $val }}" {{ request($param) == $val ? 'selected' : '' }}>
                                        {{ $val }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>

        <div class="mt-6 overflow-x-auto px-6">

            @if($total_faculty > 0)

                <!-- KPI CARDS -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mb-3 gap-4 sm:gap-6">
                    <div
                        class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                        <div
                            class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-users text-green-600 text-2xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <p class="font-[inter] text-[32px] md:text-[44px] font-extrabold leading-tight">{{ $total_faculty }}</p>
                            <p class="text-[20px] md:text-[16px] font-[inter] font-semibold text-white mt-1">Total Faculty of the University</p>
                        </div>
                    </div>

                    <div class="relative bg-white rounded-2xl p-6 shadow-md">
                        <div
                            class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-user-graduate text-white text-xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <p class="font-[inter] text-[32px] md:text-[44px] font-bold text-gray-900">
                                {{ $tertiary_total }}</p>
                            <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Total Tertiary Faculty of the University</p>
                        </div>
                    </div>

                    <div class="relative bg-white rounded-2xl p-6 shadow-md">
                        <div
                            class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                            <i class="fa-solid fa-school text-white text-xl"></i>
                        </div>
                        <div class="mt-12 text-right">
                            <p class="font-[inter] text-[32px] md:text-[44px] font-bold text-gray-900">
                                {{ $elem_secon_techbo_total }}</p>
                            <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Total Elem/Second/TechVoc Faculty of the University</p>
                        </div>
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
                        No faculty records match the selected filter criteria. Try adjusting or resetting the filters above.
                    </p>
                    <a href="{{ route('suc-faculty') }}"
                        class="mt-6 inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full shadow transition-colors duration-200">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                        Reset Filters
                    </a>
                </div>

            @endif

        </div>

        @if($total_faculty > 0)
            <!-- CHARTS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 px-6 mt-8 items-center">
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Faculty Tenure Distribution</h3>
                    <div id="tenurePie" class="h-80"></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Faculty Rank Distribution</h3>
                    <div id="rankPie" class="h-80"></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Faculty Sex Distribution</h3>
                    <div id="genderPie" class="h-80"></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow my-5 px-6">
                    <h3 class="font-bold mb-2">Sex Distribution by College</h3>
                    <div id="genderCollegeStacked" class="h-80"></div>
                </div>
            </div>
        @endif

    </div>

    @if($total_faculty > 0)
        <script src="https://cdn.plot.ly/plotly-2.35.2.min.js"></script>
        <script>

            document.addEventListener("DOMContentLoaded", () => {
                const form = document.getElementById("facultyFilterForm");
                if (!form) return;
                form.querySelectorAll("select").forEach(sel => {
                    sel.addEventListener("change", () => form.submit());
                });
            });

            const COLORS = [
                "#009639", "#FFD700", "#65FF9C", "#FFD05F", "#39EDFF",
                "#FFE450", "#FFB495", "#FFC177", "#00FFFF", "#494949", "#E0DA0D",
            ];
            const GENDER_COLORS = ['#4285F4', '#FF7BAC'];

            const PLOTLY_BASE_LAYOUT = {
                paper_bgcolor: 'rgba(0,0,0,0)',
                plot_bgcolor: 'rgba(0,0,0,0)',
                margin: { t: 70, b: 40, l: 10, r: 10 },
                font: { family: 'Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial' },
                legend: {
                    orientation: 'h',
                    x: 0.5,
                    xanchor: 'center',
                    y: 1.15,
                    yanchor: 'bottom'
                },
            };

            const PLOTLY_CONFIG = { responsive: true, displayModeBar: false };

            function sum(arr) {
                return (arr || []).reduce((a, b) => a + (Number(b) || 0), 0);
            }

            /** Build percent labels only for slices >= 3% */
            function buildPieTextArr(values) {
                const total = sum(values);
                if (!total) return values.map(() => '');
                return values.map(v => {
                    const pct = (Number(v) / total) * 100;
                    return pct >= 3 ? `${pct.toFixed(1)}%` : '';
                });
            }

            async function loadFacultyPies() {
                try {
                    const qs = window.location.search || "";
                    const res = await fetch(`/api/faculty-pie${qs}`);
                    if (!res.ok) { console.error("API failed:", res.status); return; }
                    const data = await res.json();

                    // ── TENURE PIE ────────────────────────────────────────────────
                    const tenureValues = data.tenure?.values || [];
                    Plotly.newPlot('tenurePie', [{
                        type: 'pie',
                        labels: data.tenure?.labels || [],
                        values: tenureValues,
                        text: buildPieTextArr(tenureValues),
                        textinfo: 'text',
                        textposition: 'outside',
                        automargin: true,
                        hoverinfo: 'label+value+percent',
                        marker: {
                            colors: COLORS,
                            line: { color: '#fff', width: 2 }
                        },
                        outsidetextfont: { size: 12, color: '#111827' },
                        sort: false,
                        direction: 'clockwise',
                    }], {
                        ...PLOTLY_BASE_LAYOUT,
                        margin: { t: 20, b: 20, l: 80, r: 160 },
                        legend: {
                            orientation: 'v',
                            x: 1.02,
                            xanchor: 'left',
                            y: 0.5,
                            yanchor: 'middle',
                            font: { size: 11 },
                            tracegroupgap: 6,
                        },
                    }, PLOTLY_CONFIG);

                    // ── RANK PIE ──────────────────────────────────────────────────
                    const rankValues = data.rank?.values || [];
                    Plotly.newPlot('rankPie', [{
                        type: 'pie',
                        labels: data.rank?.labels || [],
                        values: rankValues,
                        text: buildPieTextArr(rankValues),
                        textinfo: 'text',
                        textposition: 'outside',
                        automargin: true,
                        hoverinfo: 'label+value+percent',
                        marker: {
                            colors: COLORS,
                            line: { color: '#fff', width: 2 }
                        },
                        outsidetextfont: { size: 11, color: '#111827' },
                        sort: false,
                        direction: 'clockwise',
                    }], {
                        ...PLOTLY_BASE_LAYOUT,
                        margin: { t: 20, b: 20, l: 80, r: 160 },
                        legend: {
                            orientation: 'v',
                            x: 1.02,
                            xanchor: 'left',
                            y: 0.5,
                            yanchor: 'middle',
                            font: { size: 11 },
                            tracegroupgap: 4,
                        },
                    }, PLOTLY_CONFIG);

                    // ── GENDER PIE ────────────────────────────────────────────────
                    const genderValues = data.gender?.values || [];
                    Plotly.newPlot('genderPie', [{
                        type: 'pie',
                        labels: data.gender?.labels || [],
                        values: genderValues,
                        text: buildPieTextArr(genderValues),
                        textinfo: 'text',
                        textposition: 'outside',
                        automargin: true,
                        hoverinfo: 'label+value+percent',
                        marker: {
                            colors: GENDER_COLORS,
                            line: { color: '#fff', width: 2 }
                        },
                        outsidetextfont: { size: 12, color: '#111827' },
                        sort: false,
                        direction: 'clockwise',
                    }], {
                        ...PLOTLY_BASE_LAYOUT,
                        margin: { t: 70, b: 40, l: 40, r: 40 }
                    }, PLOTLY_CONFIG);

                    // ── GENDER BY COLLEGE STACKED BAR ─────────────────────────────
                    const colLabels = data.gender_by_college?.labels || [];
                    const rawDatasets = data.gender_by_college?.datasets || [];

                    const rowTotals = colLabels.map((_, ci) =>
                        rawDatasets.reduce((acc, ds) => acc + (Number(ds.data?.[ci]) || 0), 0)
                    );

                    const barTraces = rawDatasets.map((ds, i) => {
                        const rawVals = ds.data || [];

                        const pctVals = rawVals.map((v, ci) => {
                            const total = rowTotals[ci] || 0;
                            return total ? ((Number(v) / total) * 100) : 0;
                        });

                        return {
                            type: 'bar',
                            name: ds.label || `Series ${i + 1}`,
                            x: colLabels,
                            y: pctVals,
                            marker: {
                                color: GENDER_COLORS[i % GENDER_COLORS.length]
                            },
                            text: pctVals.map(v => v >= 5 ? `${v.toFixed(1)}%` : ''),
                            textposition: 'inside',
                            textangle: 0,
                            insidetextanchor: 'middle',
                            textfont: {
                                size: 11,
                                color: '#fff',
                                family: 'Inter, system-ui'
                            },
                            customdata: rawVals,
                            hovertemplate:
                                '<b>%{x}</b><br>' +
                                '%{fullData.name}: %{customdata} (%{y:.1f}%)<extra></extra>',
                            cliponaxis: false,
                        };
                    });

                    Plotly.newPlot('genderCollegeStacked', barTraces, {
                        ...PLOTLY_BASE_LAYOUT,
                        barmode: 'stack',
                        bargap: 0.25,
                        margin: { t: 0, b: 70, l: 70, r: 20 },
                        legend: {
                            orientation: 'h',
                            x: 0.5,
                            xanchor: 'center',
                            y: 1.18,
                            yanchor: 'bottom'
                        },
                        xaxis: {
                            title: { text: 'Office/Unit', standoff: 8 },
                            tickangle: 0,
                            showgrid: false,
                            tickfont: { size: 11 }
                        },
                        yaxis: {
                            title: { text: 'Percentage', standoff: 8 },
                            range: [0, 100],
                            ticksuffix: '%',
                            showgrid: true,
                            gridcolor: '#f0f0f0',
                            zeroline: false,
                            dtick: 20
                        },
                    }, PLOTLY_CONFIG);

                } catch (err) {
                    console.error("loadFacultyPies crashed:", err);
                }
            }

            document.addEventListener("DOMContentLoaded", loadFacultyPies);
        </script>
    @else
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const form = document.getElementById("facultyFilterForm");
                if (!form) return;
                form.querySelectorAll("select").forEach(sel => {
                    sel.addEventListener("change", () => form.submit());
                });
            });
        </script>
    @endif
@endsection
