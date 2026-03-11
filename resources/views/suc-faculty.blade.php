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

            <!-- HERO CARD -->
            {{-- <div class="relative w-full max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6
                    bg-gradient-to-br from-[#007a2f] via-[#009539] to-[#00b347]
                    rounded-2xl p-8 overflow-hidden
                    shadow-[0_20px_60px_rgba(0,100,30,0.5),0_4px_12px_rgba(0,0,0,0.3),0_0_0_1px_rgba(255,255,255,0.08)]
                    animate-card-in">

                <div class="absolute -top-24 -right-16 w-72 h-72 rounded-full border-[36px] border-white/[0.055] pointer-events-none"></div>
                <div class="absolute -bottom-14 left-16 w-44 h-44 rounded-full border-[28px] border-white/[0.04] pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_60%_50%_at_50%_-10%,rgba(255,255,255,0.12),transparent)] pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_40%_40%_at_100%_100%,rgba(0,0,0,0.15),transparent)] pointer-events-none"></div>

                <div class="relative z-10 shrink-0 animate-fade-up-1 group">
                    <img src="{{ asset('images/school 1.png') }}" alt="CLSU Seal"
                        class="w-28 h-28 md:w-36 md:h-36 object-contain drop-shadow-[0_6px_16px_rgba(0,0,0,0.3)] transition-transform duration-300 group-hover:scale-105 group-hover:-rotate-2" />
                </div>

                <div class="relative z-10 flex flex-col items-center text-center flex-1 animate-fade-up-2">
                    <h2 class="font-bricolage text-3xl md:text-4xl font-extrabold text-white leading-tight drop-shadow-[0_2px_8px_rgba(0,0,0,0.2)]">
                        Total Faculty<br>
                        @if($selected_college)
                            of {{ strtoupper($selected_college) }}
                        @else
                            of the University
                        @endif
                    </h2>
                    <div class="w-12 h-0.5 bg-white/30 rounded-full my-4"></div>
                    <div class="relative">
                        <span class="font-['anton'] text-4xl md:text-5xl text-white leading-none tracking-wide drop-shadow-[0_4px_20px_rgba(0,0,0,0.25)] mb-2">
                            {{ $total_faculty }}
                        </span>
                        <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-3/5 h-[3px] bg-gradient-to-r from-transparent via-white/50 to-transparent rounded-full"></div>
                    </div>
                </div>

                <div class="relative z-10 flex flex-col gap-3 shrink-0 w-full md:w-auto animate-fade-up-3">
                    <div class="group relative bg-white/[0.97] rounded-2xl px-5 py-4 min-w-[170px] shadow-[0_4px_16px_rgba(0,0,0,0.15),inset_0_1px_0_rgba(255,255,255,0.9)] transition-all duration-200 hover:-translate-x-1 hover:scale-[1.02] hover:shadow-xl overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#009539] to-[#00c44f] rounded-t-2xl"></div>
                        <p class="text-[0.62rem] font-semibold tracking-widest uppercase text-[#009539] mb-0.5">Tertiary</p>
                        <p class="font-anton text-4xl text-[#0f1a12] leading-none">{{ $tertiary_total }}</p>
                        <p class="text-[0.6rem] text-gray-400 mt-1">College-level faculty</p>
                    </div>
                    <div class="group relative bg-white/[0.97] rounded-2xl px-5 py-4 min-w-[170px] shadow-[0_4px_16px_rgba(0,0,0,0.15),inset_0_1px_0_rgba(255,255,255,0.9)] transition-all duration-200 hover:-translate-x-1 hover:scale-[1.02] hover:shadow-xl overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-[3px] bg-gradient-to-r from-[#009539] to-[#00c44f] rounded-t-2xl"></div>
                        <p class="text-[0.62rem] font-semibold tracking-widest uppercase text-[#009539] mb-0.5">Elem / Second / Tech-Voc</p>
                        <p class="font-anton text-4xl text-[#0f1a12] leading-none">{{ $elem_secon_techbo_total }}</p>
                        <p class="text-[0.6rem] text-gray-400 mt-1">Basic &amp; vocational ed.</p>
                    </div>
                </div>
            </div> --}}

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 mb-3 gap-4 sm:gap-6">
                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-wave text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[32px] md:text-[44px] font-extrabold leading-tight">{{ $total_faculty }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semibold text-white mt-1">Total Faculty of the University</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
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
                                <i class="fa-solid fa-building text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[32px] md:text-[44px] font-bold text-gray-900">
                                    {{ $elem_secon_techbo_total}}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Total Elem/Second/TechVoc Faculty of the University</p>
                            </div>
                        </div>
                    </div>
                </div>

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

        </div>
    </div>

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
                    margin: { t: 20, b: 20, l: 80, r: 160 },  // room for right-side legend
                    legend: {
                        orientation: 'v',       // vertical so labels don't wrap
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
                    margin: { t: 20, b: 20, l: 80, r: 160 },  // more right room for legend
                    legend: {
                        orientation: 'v',        // vertical legend
                        x: 1.02,                 // push to the right of the chart
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

                // total per college/office-unit
                const rowTotals = colLabels.map((_, ci) =>
                    rawDatasets.reduce((acc, ds) => acc + (Number(ds.data?.[ci]) || 0), 0)
                );

                // const barTraces = rawDatasets.map((ds, i) => {
                //     const rawVals = ds.data || [];

                //     // convert to percentage so each bar is always full (100%)
                //     const pctVals = rawVals.map((v, ci) => {
                //         const total = rowTotals[ci] || 0;
                //         return total ? ((Number(v) / total) * 100) : 0;
                //     });

                //     return {
                //         type: 'bar',
                //         name: ds.label || `Series ${i + 1}`,
                //         x: colLabels,
                //         y: pctVals,
                //         marker: {
                //             color: GENDER_COLORS[i % GENDER_COLORS.length]
                //         },
                //         text: pctVals.map(v => v >= 5 ? `${v.toFixed(1)}%` : ''),
                //         textposition: 'inside',
                //         insidetextanchor: 'middle',
                //         textfont: {
                //             size: 11,
                //             color: '#fff',
                //             family: 'Inter, system-ui'
                //         },
                //         customdata: rawVals,
                //         hovertemplate:
                //             '<b>%{x}</b><br>' +
                //             '%{fullData.name}: %{customdata} (%{y:.1f}%)<extra></extra>',
                //         cliponaxis: false,
                //     };
                // });

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
                    textangle: 0, // force horizontal text inside bars
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
                        title: {
                            text: 'Office/Unit',
                            standoff: 8
                        },
                        tickangle: 0,
                        showgrid: false,
                        tickfont: { size: 11 }
                    },
                    yaxis: {
                        title: {
                            text: 'Percentage',
                            standoff: 8
                        },
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

        /** Returns #111827 for bright backgrounds, #FFFFFF for dark */
        function contrastFor(hex) {
            if (!hex) return '#111827';
            hex = String(hex).replace('#', '');
            if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
            const n = parseInt(hex, 16);
            const r = (n >> 16) & 255, g = (n >> 8) & 255, b = n & 255;
            return (r * 299 + g * 587 + b * 114) / 1000 > 150 ? '#111827' : '#FFFFFF';
        }

        document.addEventListener("DOMContentLoaded", loadFacultyPies);
    </script>
@endsection
