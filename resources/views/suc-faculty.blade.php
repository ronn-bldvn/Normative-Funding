{{-- @extends('layouts.base')

@section('page_title')
    SUC Faculty
@endsection

@section('content')
<div class="">

    <!-- FILTER BAR -->
    <div class="sticky top-0 z-50 flex justify-between items-center h-12 bg-[#BDBDBD] px-6">
        <form method="GET" action="{{ route('suc-faculty') }}" class="flex flex-row gap-3 items-center">

            @foreach($filter_columns as $col)
                <div class="flex items-center gap-3">
                    <label class="font-['Bricolage_Grotesque'] font-extrabold text-xs">{{ $col }}</label>
                    <div class="relative w-40">
                        <select name="{{ $col }}"
                            class="w-40 appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer text-xs p-2">
                            <option class="text-xs" value="All">All</option>
                            @foreach($filter_options[$col] as $val)
                                <option class="text-xs" value="{{ $val }}"
                                    {{ request($col) == $val ? 'selected' : '' }}>
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

            <button type="submit" class="px-4 py-1 bg-white text-black rounded-full text-sm">Apply</button>

            @if(count(request()->query()) > 0)
                <a href="{{ route('suc-faculty') }}" class="px-4 py-1 bg-white text-black rounded-full text-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="mt-6 overflow-x-auto px-6">

        <div class="py-6 sm:py-8 animate-card-in">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-wave text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">{{ $total_faculty }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semibold text-white mt-1">Total University
                                    Income</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">
                                    {{ $tertiary_total }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Total Academic Fees</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-building text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">
                                    {{ $elem_secon_techbo_total}}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Auxiliary &amp; Business
                                    Income</p>
                            </div>
                        </div>
                    </div>
                </div>

        <!-- HERO CARD -->
        <div class="relative w-full max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6
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
                    @if($selected_college && $selected_college !== 'All')
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
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6 mt-8 items-center">
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Tenure Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="tenurePie"></canvas></div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Rank Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="rankPie"></canvas></div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Gender Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="genderPie"></canvas></div>
            </div>

            @if(!$selected_college || $selected_college === 'All')
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Male Faculty Count by College</h3>
                    <div class="h-80"><canvas id="maleByCollege"></canvas></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Female Faculty Count by College</h3>
                    <div class="h-80"><canvas id="femaleByCollege"></canvas></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow my-5 px-6">
                    <h3 class="font-bold mb-2">Gender Distribution per College</h3>
                    <div class="h-80"><canvas id="genderCollegeStacked"></canvas></div>
                </div>
            @endif
        </div>

        <!-- DATA TABLE -->
        <table class="min-w-full bg-white border border-gray-200 my-4">
            <thead class="bg-gray-200">
                <tr>
                    @if(!empty($faculty_data))
                        @foreach(array_keys($faculty_data[0]) as $key)
                            <th class="px-4 py-2 text-left text-sm font-bold">{{ $key }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($faculty_data as $row)
                    <tr class="border-t">
                        @foreach(array_values($row) as $value)
                            <td class="px-4 py-2 text-sm">{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($total_pages > 1)
            @php
                $baseQuery = request()->except('page');
            @endphp
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 my-4">

                <div class="text-sm text-gray-600">
                    Showing
                    <span class="font-semibold">{{ ($page - 1) * $per_page + 1 }}</span>
                    –
                    <span class="font-semibold">{{ ($page - 1) * $per_page + count($faculty_data) }}</span>
                    of
                    <span class="font-semibold">{{ $total_rows }}</span>
                </div>

                <div class="flex items-center gap-1 flex-wrap justify-end">

                    <!-- Prev -->
                    @if($page > 1)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $page - 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Prev</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-sm cursor-not-allowed">Prev</span>
                    @endif

                    <!-- Page window -->
                    @php
                        $start = max(1, $page - 2);
                        $end   = min($total_pages, $page + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">1</a>
                        @if($start > 2)
                            <span class="px-2 py-1 text-sm text-gray-500">…</span>
                        @endif
                    @endif

                    @for($p = $start; $p <= $end; $p++)
                        @if($p === $page)
                            <span class="px-3 py-1 rounded-lg text-sm bg-green-600 text-white font-semibold">{{ $p }}</span>
                        @else
                            <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $p, 'per_page' => $per_page])) }}"
                               class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">{{ $p }}</a>
                        @endif
                    @endfor

                    @if($end < $total_pages)
                        @if($end < $total_pages - 1)
                            <span class="px-2 py-1 text-sm text-gray-500">…</span>
                        @endif
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $total_pages, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">{{ $total_pages }}</a>
                    @endif

                    <!-- Next -->
                    @if($page < $total_pages)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $page + 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Next</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-sm cursor-not-allowed">Next</span>
                    @endif

                </div>
            </div>
        @endif

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = [
        "#009639","#FFD700","#65FF9C","#FFD05F","#39EDFF",
        "#FFE450","#FFB495","#FFC177","#00FFFF","#494949","#E0DA0D",
    ];
    const GenderColor = ['#4285F4', '#FF7BAC'];

    let tenureChart, rankChart, genderChart, genderCollegeChart, maleChart, femaleChart;

    function findSeries(ds, label) {
        return (ds || []).find(d => (d.label || "").toLowerCase() === label.toLowerCase());
    }

    async function loadFacultyPies() {
        try {
            const qs  = window.location.search || "";
            const res = await fetch(`/api/faculty-pie${qs}`);
            if (!res.ok) { console.error("API failed:", res.status); return; }
            const data = await res.json();

            // TENURE
            if (tenureChart) tenureChart.destroy();
            tenureChart = new Chart(document.getElementById("tenurePie"), {
                type: "pie",
                data: { labels: data.tenure?.labels || [], datasets: [{ data: data.tenure?.values || [], backgroundColor: colors, borderColor: "#fff", borderWidth: 2 }] },
                options: { plugins: { legend: { position: "bottom" } }, responsive: true, maintainAspectRatio: false }
            });

            // RANK
            if (rankChart) rankChart.destroy();
            rankChart = new Chart(document.getElementById("rankPie"), {
                type: "pie",
                data: { labels: data.rank?.labels || [], datasets: [{ data: data.rank?.values || [], backgroundColor: colors, borderColor: "#fff", borderWidth: 2 }] },
                options: { plugins: { legend: { position: "bottom" } }, responsive: true, maintainAspectRatio: false }
            });

            // GENDER
            if (genderChart) genderChart.destroy();
            genderChart = new Chart(document.getElementById("genderPie"), {
                type: "doughnut",
                data: { labels: data.gender?.labels || [], datasets: [{ data: data.gender?.values || [], backgroundColor: GenderColor, borderColor: "#fff", borderWidth: 2 }] },
                options: { plugins: { legend: { position: "bottom" } }, cutout: "60%", responsive: true, maintainAspectRatio: false }
            });

            // STACKED gender by college (only rendered when canvases exist)
            const cctx = document.getElementById("genderCollegeStacked");
            if (cctx) {
                if (genderCollegeChart) genderCollegeChart.destroy();
                const labels   = data.gender_by_college?.labels || [];
                const datasets = (data.gender_by_college?.datasets || []).map((ds, i) => ({
                    ...ds, backgroundColor: GenderColor[i % GenderColor.length], borderSkipped: false
                }));
                genderCollegeChart = new Chart(cctx, {
                    type: "bar",
                    data: { labels, datasets },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: "bottom" } }, scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, beginAtZero: true } } }
                });

                // Male by College
                const maleSeries = findSeries(datasets, "Male")?.data || labels.map(() => 0);
                if (maleChart) maleChart.destroy();
                maleChart = new Chart(document.getElementById("maleByCollege"), {
                    type: "bar",
                    data: { labels, datasets: [{ label: "Male", data: maleSeries, backgroundColor: GenderColor[0] }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } } }
                });

                // Female by College
                const femaleSeries = findSeries(datasets, "Female")?.data || labels.map(() => 0);
                if (femaleChart) femaleChart.destroy();
                femaleChart = new Chart(document.getElementById("femaleByCollege"), {
                    type: "bar",
                    data: { labels, datasets: [{ label: "Female", data: femaleSeries, backgroundColor: GenderColor[1] }] },
                    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } } }
                });
            }
        } catch (err) {
            console.error("loadFacultyPies crashed:", err);
        }
    }

    document.addEventListener("DOMContentLoaded", loadFacultyPies);
</script>
@endsection --}}



@extends('layouts.base')

@section('page_title')
    SUC Faculty
@endsection

@section('content')
<div class="">

    <!-- FILTER BAR -->
    <div class="sticky top-0 z-50 flex justify-between items-center h-12 bg-[#BDBDBD] px-6">
        <form method="GET" action="{{ route('suc-faculty') }}" class="flex flex-row gap-3 items-center">

            @foreach($filter_columns as $col)
                @php $param = $filter_param_keys[$col] ?? $col; @endphp

                <div class="flex items-center gap-3">
                    <label class="font-['Bricolage_Grotesque'] font-extrabold text-xs">{{ $col }}</label>
                    <div class="relative w-40">
                        <select name="{{ $param }}"
                            class="w-40 appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer text-xs p-2">
                            <option class="text-xs" value="All">All</option>

                            @foreach(($filter_options[$col] ?? []) as $val)
                                <option class="text-xs" value="{{ $val }}"
                                    {{ request($param) == $val ? 'selected' : '' }}>
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

            <button type="submit" class="px-4 py-1 bg-white text-black rounded-full text-sm">Apply</button>

            @if(count(request()->query()) > 0)
                <a href="{{ route('suc-faculty') }}" class="px-4 py-1 bg-white text-black rounded-full text-sm">Clear</a>
            @endif
        </form>
    </div>

    <div class="mt-6 overflow-x-auto px-6">

        <!-- HERO CARD -->
        <div class="relative w-full max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6
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
        </div>

        <!-- CHARTS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-6 mt-8 items-center">
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Tenure Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="tenurePie"></canvas></div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Rank Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="rankPie"></canvas></div>
            </div>
            <div class="bg-white rounded-xl p-4 shadow">
                <h3 class="font-bold mb-2">Faculty Gender Distribution</h3>
                <div class="h-80 flex items-center justify-center"><canvas id="genderPie"></canvas></div>
            </div>

            @if(!$selected_college)
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Male Faculty Count by College</h3>
                    <div class="h-80"><canvas id="maleByCollege"></canvas></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow">
                    <h3 class="font-bold mb-2">Female Faculty Count by College</h3>
                    <div class="h-80"><canvas id="femaleByCollege"></canvas></div>
                </div>
                <div class="bg-white rounded-xl p-4 shadow my-5 px-6">
                    <h3 class="font-bold mb-2">Gender Distribution per College</h3>
                    <div class="h-80"><canvas id="genderCollegeStacked"></canvas></div>
                </div>
            @endif
        </div>

        <!-- DATA TABLE -->
        <table class="min-w-full bg-white border border-gray-200 my-4">
            <thead class="bg-gray-200">
                <tr>
                    @if(!empty($faculty_data))
                        @foreach(array_keys($faculty_data[0]) as $key)
                            <th class="px-4 py-2 text-left text-sm font-bold">{{ $key }}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($faculty_data as $row)
                    <tr class="border-t">
                        @foreach(array_values($row) as $value)
                            <td class="px-4 py-2 text-sm">{{ $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- PAGINATION -->
        @if($total_pages > 1)
            @php
                $baseQuery = request()->except('page');
            @endphp
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 my-4">

                <div class="text-sm text-gray-600">
                    Showing
                    <span class="font-semibold">{{ ($page - 1) * $per_page + 1 }}</span>
                    –
                    <span class="font-semibold">{{ ($page - 1) * $per_page + count($faculty_data) }}</span>
                    of
                    <span class="font-semibold">{{ $total_rows }}</span>
                </div>

                <div class="flex items-center gap-1 flex-wrap justify-end">

                    {{-- Prev --}}
                    @if($page > 1)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $page - 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Prev</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-sm cursor-not-allowed">Prev</span>
                    @endif

                    {{-- Page window --}}
                    @php
                        $start = max(1, $page - 2);
                        $end   = min($total_pages, $page + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">1</a>
                        @if($start > 2)
                            <span class="px-2 py-1 text-sm text-gray-500">…</span>
                        @endif
                    @endif

                    @for($p = $start; $p <= $end; $p++)
                        @if($p === $page)
                            <span class="px-3 py-1 rounded-lg text-sm bg-green-600 text-white font-semibold">{{ $p }}</span>
                        @else
                            <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $p, 'per_page' => $per_page])) }}"
                               class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">{{ $p }}</a>
                        @endif
                    @endfor

                    @if($end < $total_pages)
                        @if($end < $total_pages - 1)
                            <span class="px-2 py-1 text-sm text-gray-500">…</span>
                        @endif
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $total_pages, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg text-sm bg-gray-100 hover:bg-gray-200">{{ $total_pages }}</a>
                    @endif

                    {{-- Next --}}
                    @if($page < $total_pages)
                        <a href="{{ route('suc-faculty', array_merge($baseQuery, ['page' => $page + 1, 'per_page' => $per_page])) }}"
                           class="px-3 py-1 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Next</a>
                    @else
                        <span class="px-3 py-1 rounded-lg bg-gray-50 text-gray-400 text-sm cursor-not-allowed">Next</span>
                    @endif

                </div>
            </div>
        @endif

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const colors = [
        "#009639","#FFD700","#65FF9C","#FFD05F","#39EDFF",
        "#FFE450","#FFB495","#FFC177","#00FFFF","#494949","#E0DA0D",
    ];
    const GenderColor = ['#4285F4', '#FF7BAC'];

    let tenureChart, rankChart, genderChart, genderCollegeChart, maleChart, femaleChart;

    function findSeries(ds, label) {
        return (ds || []).find(d => (d.label || "").toLowerCase() === label.toLowerCase());
    }

    async function loadFacultyPies() {
        try {
            const qs  = window.location.search || "";
            const res = await fetch(`/api/faculty-pie${qs}`);
            if (!res.ok) { console.error("API failed:", res.status); return; }
            const data = await res.json();

            // TENURE
            if (tenureChart) tenureChart.destroy();
            tenureChart = new Chart(document.getElementById("tenurePie"), {
                type: "pie",
                data: {
                    labels: data.tenure?.labels || [],
                    datasets: [{ data: data.tenure?.values || [], backgroundColor: colors, borderColor: "#fff", borderWidth: 2 }]
                },
                options: { plugins: { legend: { position: "bottom" } }, responsive: true, maintainAspectRatio: false }
            });

            // RANK
            if (rankChart) rankChart.destroy();
            rankChart = new Chart(document.getElementById("rankPie"), {
                type: "pie",
                data: {
                    labels: data.rank?.labels || [],
                    datasets: [{ data: data.rank?.values || [], backgroundColor: colors, borderColor: "#fff", borderWidth: 2 }]
                },
                options: { plugins: { legend: { position: "bottom" } }, responsive: true, maintainAspectRatio: false }
            });

            // GENDER
            if (genderChart) genderChart.destroy();
            genderChart = new Chart(document.getElementById("genderPie"), {
                type: "doughnut",
                data: {
                    labels: data.gender?.labels || [],
                    datasets: [{ data: data.gender?.values || [], backgroundColor: GenderColor, borderColor: "#fff", borderWidth: 2 }]
                },
                options: { plugins: { legend: { position: "bottom" } }, cutout: "60%", responsive: true, maintainAspectRatio: false }
            });

            // STACKED gender by college (only rendered when canvases exist)
            const cctx = document.getElementById("genderCollegeStacked");
            if (cctx) {
                if (genderCollegeChart) genderCollegeChart.destroy();

                const labels = data.gender_by_college?.labels || [];
                const datasets = (data.gender_by_college?.datasets || []).map((ds, i) => ({
                    ...ds,
                    backgroundColor: GenderColor[i % GenderColor.length],
                    borderSkipped: false
                }));

                genderCollegeChart = new Chart(cctx, {
                    type: "bar",
                    data: { labels, datasets },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: "bottom" } },
                        scales: {
                            x: { stacked: true, grid: { display: false } },
                            y: { stacked: true, beginAtZero: true }
                        }
                    }
                });

                // Male by College
                const maleSeries = findSeries(datasets, "Male")?.data || labels.map(() => 0);
                if (maleChart) maleChart.destroy();
                maleChart = new Chart(document.getElementById("maleByCollege"), {
                    type: "bar",
                    data: { labels, datasets: [{ label: "Male", data: maleSeries, backgroundColor: GenderColor[0] }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
                    }
                });

                // Female by College
                const femaleSeries = findSeries(datasets, "Female")?.data || labels.map(() => 0);
                if (femaleChart) femaleChart.destroy();
                femaleChart = new Chart(document.getElementById("femaleByCollege"), {
                    type: "bar",
                    data: { labels, datasets: [{ label: "Female", data: femaleSeries, backgroundColor: GenderColor[1] }] },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
                    }
                });
            }
        } catch (err) {
            console.error("loadFacultyPies crashed:", err);
        }
    }

    document.addEventListener("DOMContentLoaded", loadFacultyPies);
</script>
@endsection
