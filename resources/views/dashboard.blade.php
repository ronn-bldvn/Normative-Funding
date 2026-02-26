@extends('layouts.base')

@section('page_title')
    Normative Funding Breakdown
@endsection

@section('content')
    <div class="w-full">

        <!-- TOP BAR -->
        <div class="sticky top-0 z-30 bg-[#BDBDBD] px-4 lg:px-6 py-3 lg:h-12">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">

                <h2 class="text-lg lg:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black leading-tight">
                    @if($filter_type === 'allotment')
                        Allotment Statement ({{ $year }})
                    @elseif($filter_type === 'expenditure')
                        Expenditure Statement ({{ $year }})
                    @elseif($filter_type === 'suc_income')
                        SUC Income ({{ $year }})
                    @else
                        University Financial Overview ({{ $year }})
                    @endif
                </h2>

                <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:gap-6">
                    <div class="font-['Bricolage_Grotesque'] font-extrabold text-sm lg:text-base lg:mr-5">Filters:</div>

                    <!-- Year Filter -->
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <label for="year_filter"
                            class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">Year:</label>
                        <div class="relative w-full lg:w-32">
                            <select name="year_filter" id="year_filter" onchange="updateFilters()"
                                class="w-full appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer py-1 px-4">
                                @foreach($suc_years as $y)
                                    <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                                @if(empty($suc_years))
                                    <option>No Sheets Found</option>
                                @endif
                            </select>
                            <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Type Filter -->
                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <label for="type_filter"
                            class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">Type:</label>
                        <div class="relative w-full lg:w-32">
                            <select name="type_filter" id="type_filter" onchange="updateFilters()" class="w-full appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer py-1 px-4">

                                <option value="all" {{ $filter_type === 'all' ? 'selected' : '' }}>All</option>
                                <option value="allotment" {{ $filter_type === 'allotment' ? 'selected' : '' }}>Allotment</option>
                                <option value="expenditure" {{ $filter_type === 'expenditure' ? 'selected' : '' }}>Expenditure</option>
                                <option value="suc_income" {{ $filter_type === 'suc_income' ? 'selected' : '' }}>SUC Income</option>

                            </select>
                            <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
                                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-4 sm:px-6 lg:px-8">

            <!-- ============================================================ -->
            <!-- SUC INCOME SECTION -->
            <!-- ============================================================ -->
            <div id="suc_income_section" class="{{ !in_array($filter_type, ['all', 'suc_income']) ? 'hidden' : '' }}">
                <div class="py-6 sm:py-8 animate-card-in">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-money-bill-wave text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">{{ $income['grand_total_income'] }}</p>
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
                                    {{ $income['tuition_misc_fee'] }}</p>
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
                                    {{ $income['miscellaneous'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Auxiliary &amp; Business
                                    Income</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-circle-plus text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">
                                    {{ $income['other_income'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Other Business Income</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INCOME CHARTS -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Total University
                                Income Breakdown</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]">
                                <canvas id="mainPieChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Total Academic Fees
                                Breakdown</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]">
                                <canvas id="tuitionPieChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col w-full lg:max-w-2xl">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Other Business Income Breakdown</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]">
                                <canvas id="otherIncomePieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- ALLOTMENT SECTION -->
            <!-- ============================================================ -->
            <div id="allotment_section" class="{{ !in_array($filter_type, ['all', 'allotment']) ? 'hidden' : '' }}">
                <div class="py-6 sm:py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">

                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                    {{ $allotment['combined_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semiboldtext-white">Total University
                                    Allotment</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-landmark text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="text-3xl md:text-2xl font-bold text-gray-900">{{ $allotment['gaa_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">GAA Allotment</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-arrow-trend-up text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{ $allotment['suc_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">SUC Income Allotment</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ALLOTMENT CHARTS -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Distribution by Funding Source</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]"><canvas id="allotmentPieChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Total Allotment by Expense Class
                            </h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]"><canvas
                                    id="allotmentCategoryChart"></canvas></div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">GAA Allotment by Expense Class</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]"><canvas id="allotmentGAAChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">SUC Income Allotment by Expense
                                Class</h3>
                            <div class="relative w-full h-64 sm:h-80 lg:h-[450px]"><canvas id="allotmentSUCChart"></canvas>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col lg:col-span-2">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Total Allotment by Institutional
                                Function</h3>
                            <div class="relative w-full h-72 sm:h-96 lg:h-[450px]"><canvas
                                    id="allotmentFunctionChart"></canvas></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- EXPENDITURE SECTION -->
            <!-- ============================================================ -->

            <div id="expenditure_section" class="{{ !in_array($filter_type, ['all', 'expenditure']) ? 'hidden' : '' }}">
                <div class="py-6 sm:py-8">
                    {{-- <h2 class="text-xl sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-gray-800 mb-6">
                        Expenditure Statement
                    </h2> --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">

                        <div class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-arrow-trend-down text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                    {{ $expenditure['combined_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semibold  text-white">Total University Expenditure
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-receipt text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{ $expenditure['gaa_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">GAA Expenditure</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{ $expenditure['suc_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">SUC Income Expenditure</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- EXPENDITURE CHARTS -->
                 <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                    {{-- <h2
                        class="text-lg sm:text-2xl font-['Bricolage_Grotesque'] font-extrabold text-black mb-6 sm:mb-10 text-center uppercase">
                        Expenditure Breakdown ({{ $year }})
                    </h2> --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                        <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Distribution of Total Expenditures</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditurePieChart"></canvas></div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Total Expenditure by Expense Class</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditureCategoryChart"></canvas></div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">GAA Expenditure by Expense Class</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditureGAAChart"></canvas></div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">SUC Income Expenditure by Expense Class</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditureSUCChart"></canvas></div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col lg:col-span-2">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Total Expenditure by Institutional Function</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditureFunctionChart"></canvas></div>
                        </div>
                        {{-- <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col lg:col-span-2">
                            <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Expenditure Comparison:
                                GAA vs SUC Income by Function</h3>
                            <div class="relative h-[450px] w-full"><canvas id="expenditureComparisonChart"></canvas></div>
                        </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;

        Chart.register(ChartDataLabels);

        Chart.defaults.font.family = 'Inter';

        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw(chart) {
                if (chart.config.type !== 'doughnut') return;

                const { ctx, chartArea } = chart;
                const dataset = chart.data.datasets[0];
                const total = dataset.data.reduce((a, b) => a + b, 0);

                ctx.save();

                const centerX = (chartArea.left + chartArea.right) / 2;
                const centerY = (chartArea.top + chartArea.bottom) / 2;

                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';

                // Label
                ctx.font = 'bold 12px Inter';
                ctx.fillStyle = '#6b7280';
                ctx.fillText('Total', centerX, centerY - 12);

                // Amount
                ctx.font = 'bold 16px Inter';
                ctx.fillStyle = '#111827';
                ctx.fillText('₱' + total.toLocaleString(), centerX, centerY + 12);

                ctx.restore();
            }
        };

        Chart.register(centerTextPlugin);

        const selectedYear = @json($year);
        const filterType = @json($filter_type);

        const sucYears = @json($suc_years_chart ?? []);
        const sucTotals = @json($suc_totals ?? []);

        const chartColors = [
            '#007B3E', '#FFD700', '#39EDFF', '#FFE450', '#FFB495',
            '#FFC177', '#FFA8F7', '#00FFFF', '#E5E5E5', '#E06B0D', '#567F13', '#1A5F30',
        ];

        // ---------- helpers ----------
        const $ = (id) => document.getElementById(id);

        function updateFilters() {
            const year = $('year_filter')?.value;
            const type = $('type_filter')?.value ?? 'all';
            window.location.href = `/?year=${encodeURIComponent(year)}&type=${encodeURIComponent(type)}`;
        }
        window.updateFilters = updateFilters;

        function n(v) {
            v = Number(v);
            return Number.isFinite(v) ? v : 0;
        }

        function normalizeItems(items) {
            return (items || [])
                .map(i => ({ name: i.name, value: n(i.value) }))
                .filter(i => i.value > 0);
        }

        function toggleChartCard(canvasId, show) {
            const canvas = $(canvasId);
            if (!canvas) return;
            const card = canvas.closest('.bg-gray-50') || canvas.parentElement;
            if (card) card.classList.toggle('hidden', !show);
        }

        function pieOptions() {
            return {
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 12,
                            font: { size: 11 }
                        }
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        font: {
                            weight: 'bold',
                            size: 11
                        },

                        // 🔥 Dynamic text color
                        color: function(context) {
                            const bgColor = context.dataset.backgroundColor[context.dataIndex];

                            // Remove rgba() if present
                            let r, g, b;

                            if (bgColor.startsWith('#')) {
                                r = parseInt(bgColor.substr(1, 2), 16);
                                g = parseInt(bgColor.substr(3, 2), 16);
                                b = parseInt(bgColor.substr(5, 2), 16);
                            } else if (bgColor.startsWith('rgb')) {
                                const rgb = bgColor.match(/\d+/g);
                                r = parseInt(rgb[0]);
                                g = parseInt(rgb[1]);
                                b = parseInt(rgb[2]);
                            }

                            const brightness = (r * 299 + g * 587 + b * 114) / 1000;

                            return brightness < 128 ? '#ffffff' : '#000000';
                        },

                        formatter: (value, context) => {
                            const data = context.chart.data.datasets[0].data;
                            const total = data.reduce((a, b) => a + b, 0);
                            const percentage = total ? (value / total) * 100 : 0;

                            return percentage >= 3
                                ? percentage.toFixed(1) + '%'
                                : '';
                        }
                    }
                }
            };
        }

        function makeChart(canvasId, type, labels, values, colors) {
            const el = $(canvasId);
            if (!el) return null;

            if (!labels.length || !values.length) {
                toggleChartCard(canvasId, false);
                return null;
            }

            toggleChartCard(canvasId, true);

            return new Chart(el, {
                type,
                data: {
                    labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: '#fff',
                        borderWidth: 2,
                        hoverOffset: 8,
                    }]
                },
                options: pieOptions()
            });
        }

        async function fetchJson(url) {
            const res = await fetch(url);
            if (!res.ok) throw new Error(`Request failed: ${res.status} ${url}`);
            return res.json();
        }

        // ---------- SUC INCOME ----------
        async function loadIncomeCharts() {
            const data = await fetchJson(`/api/income-data?year=${encodeURIComponent(selectedYear)}`);

            // main pie = combine tuition + other (top4 + others)
            let allItems = [
                ...(data.breakdown?.tuition_details || []),
                ...(data.breakdown?.other_income_details || [])
            ];
            allItems = normalizeItems(allItems).sort((a, b) => b.value - a.value);

            if (allItems.length > 4) {
                const top4 = allItems.slice(0, 4);
                const othersTotal = allItems.slice(4).reduce((s, i) => s + i.value, 0);
                if (othersTotal > 0) top4.push({ name: 'Others', value: othersTotal });
                allItems = top4;
            }

            makeChart(
                'mainPieChart',
                'pie',
                allItems.map(i => i.name),
                allItems.map(i => i.value),
                chartColors
            );

            const tuitionItems = normalizeItems(data.breakdown?.tuition_details);
            makeChart(
                'tuitionPieChart',
                'pie',
                tuitionItems.map(i => i.name),
                tuitionItems.map(i => i.value),
                chartColors
            );

            const otherItems = normalizeItems(data.breakdown?.other_income_details);
            makeChart(
                'otherIncomePieChart',
                'pie',
                otherItems.map(i => i.name),
                otherItems.map(i => i.value),
                chartColors
            );

            // optional line chart
            if ($('sucIncomeLineChart')) {
                new Chart($('sucIncomeLineChart'), {
                    type: 'line',
                    data: {
                        labels: sucYears,
                        datasets: [{
                            label: 'Total SUC Income',
                            data: sucTotals,
                            tension: 0.4,
                            borderWidth: 3,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.2)',
                            fill: true,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        plugins: {
                            tooltip: {
                                callbacks: { label: ctx => ' ₱' + Number(ctx.raw || 0).toLocaleString() }
                            }
                        },
                        scales: {
                            y: { ticks: { callback: v => '₱' + Number(v).toLocaleString() } }
                        }
                    }
                });
            }
        }

        // ---------- ALLOTMENT ----------
        async function loadAllotmentCharts() {
            const data = await fetchJson(`/api/allotment-data?year=${encodeURIComponent(selectedYear)}`);

            makeChart(
                'allotmentPieChart',
                'pie',
                ['GAA Allotment', 'SUC Income Allotment'],
                [n(data.gaa?.total), n(data.suc_income?.total)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            const totalPS = n(data.gaa?.ps) + n(data.suc_income?.ps);
            const totalMOOE = n(data.gaa?.mooe) + n(data.suc_income?.mooe);
            const totalCO = n(data.gaa?.co) + n(data.suc_income?.co);

            makeChart(
                'allotmentCategoryChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [totalPS, totalMOOE, totalCO],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            makeChart(
                'allotmentGAAChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(data.gaa?.ps), n(data.gaa?.mooe), n(data.gaa?.co)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            makeChart(
                'allotmentSUCChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(data.suc_income?.ps), n(data.suc_income?.mooe), n(data.suc_income?.co)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            // function stacked bar
            const rows = (data.breakdown || [])
                .map(r => ({
                    fn: String(r.function || '').trim(),
                    gaa: n(r.gaa_total),
                    suc: n(r.suc_total)
                }))
                .filter(r => (r.gaa + r.suc) > 0);

            if (!$('allotmentFunctionChart')) return;
            if (!rows.length) { toggleChartCard('allotmentFunctionChart', false); return; }
            toggleChartCard('allotmentFunctionChart', true);

            new Chart($('allotmentFunctionChart'), {
                type: 'bar',
                data: {
                    labels: rows.map(r => r.fn),
                    datasets: [
                        { label: 'GAA Allotment', data: rows.map(r => r.gaa), backgroundColor: '#007B3E', borderWidth: 1 },
                        { label: 'SUC Income Allotment', data: rows.map(r => r.suc), backgroundColor: '#FFD700', borderWidth: 1 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            ticks: {
                                stepSize: 50_000_000,
                                callback: function(value) {
                                    if (value >= 1_000_000_000) {
                                        return '₱' + (value / 1_000_000_000) + 'B';
                                    } else if (value >= 1_000_000) {
                                        return '₱' + (value / 1_000_000) + 'M';
                                    } else if (value >= 1_000) {
                                        return '₱' + (value / 1_000) + 'K';
                                    } else {
                                        return '₱' + value;
                                    }
                                }
                            }
                        }
                    },
                    plugins: {
    datalabels: {
        color: '#111',
        anchor: 'end',
        align: 'end',
        font: {
            weight: 'bold',
            size: 12
        },
        formatter: function(value, context) {
            const chart = context.chart;
            const dataIndex = context.dataIndex;

            let total = 0;
            chart.data.datasets.forEach(dataset => {
                total += dataset.data[dataIndex] || 0;
            });

            // Show only on top dataset
            if (context.datasetIndex === chart.data.datasets.length - 1) {
                return '₱' + total.toLocaleString('en-US');
            }

            return '';
        }
    }
}
                }
            });

            return data; 
        }

        // ---------- EXPENDITURE ----------
        async function loadExpenditureCharts() {
            const expData = await fetchJson(`/api/expenditure-data?year=${encodeURIComponent(selectedYear)}`);

            makeChart(
                'expenditurePieChart',
                'pie',
                ['GAA Expenditure', 'SUC Income Expenditure'],
                [n(expData.gaa?.total), n(expData.suc_income?.total)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            const ePS = n(expData.gaa?.ps) + n(expData.suc_income?.ps);
            const eMOOE = n(expData.gaa?.mooe) + n(expData.suc_income?.mooe);
            const eCO = n(expData.gaa?.co) + n(expData.suc_income?.co);

            makeChart(
                'expenditureCategoryChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [ePS, eMOOE, eCO],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            makeChart(
                'expenditureGAAChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(expData.gaa?.ps), n(expData.gaa?.mooe), n(expData.gaa?.co)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            makeChart(
                'expenditureSUCChart',
                'doughnut',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(expData.suc_income?.ps), n(expData.suc_income?.mooe), n(expData.suc_income?.co)],
                ['#007B3E', '#FFD700', '#39EDFF']
            );

            const rows = (expData.breakdown || [])
                .map(r => ({
                    fn: String(r.function || '').trim(),
                    gaa: n(r.gaa_total),
                    suc: n(r.suc_total)
                }))
                .filter(r => (r.gaa + r.suc) > 0);

            if (!rows.length) {
                toggleChartCard('expenditureFunctionChart', false);
                toggleChartCard('expenditureComparisonChart', false);
                return;
            }

            // stacked
            if ($('expenditureFunctionChart')) {
                toggleChartCard('expenditureFunctionChart', true);
                new Chart($('expenditureFunctionChart'), {
                    type: 'bar',
                    data: {
                        labels: rows.map(r => r.fn),
                        datasets: [
                            { label: 'GAA Expenditure', data: rows.map(r => r.gaa), backgroundColor: '#007B3E' },
                            { label: 'SUC Income Expenditure', data: rows.map(r => r.suc), backgroundColor: '#FFD700' }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,

                        scales: {
                            x: { stacked: true },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        if (value >= 1_000_000_000) {
                                            return '₱' + (value / 1_000_000_000) + 'B';
                                        } else if (value >= 1_000_000) {
                                            return '₱' + (value / 1_000_000) + 'M';
                                        } else if (value >= 1_000) {
                                            return '₱' + (value / 1_000) + 'K';
                                        } else {
                                            return '₱' + value;
                                        }
                                    }
                                }
                            }
                        },
                        plugins: {
    datalabels: {
        color: '#111',
        anchor: 'end',
        align: 'end',
        font: {
            weight: 'bold',
            size: 12
        },
        formatter: function(value, context) {
            const chart = context.chart;
            const dataIndex = context.dataIndex;

            let total = 0;
            chart.data.datasets.forEach(dataset => {
                total += dataset.data[dataIndex] || 0;
            });

            // Show only on top dataset
            if (context.datasetIndex === chart.data.datasets.length - 1) {
                return '₱' + total.toLocaleString('en-US');
            }

            return '';
        }
    }
}
                    }
                });
            }

            // comparison (not stacked)
            if ($('expenditureComparisonChart')) {
                toggleChartCard('expenditureComparisonChart', true);
                new Chart($('expenditureComparisonChart'), {
                    type: 'bar',
                    data: {
                        labels: rows.map(r => r.fn),
                        datasets: [
                            { label: 'GAA Expenditure', data: rows.map(r => r.gaa), backgroundColor: '#ef4444' },
                            { label: 'SUC Income Expenditure', data: rows.map(r => r.suc), backgroundColor: '#f97316' }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => '₱' + Number(v).toLocaleString() }
                            }
                        }
                    }
                });
            }
        }

        // ---------- boot ----------
        (async function init() {
            try {
                if (filterType === 'all' || filterType === 'suc_income') {
                    await loadIncomeCharts();
                }

                if (filterType === 'all' || filterType === 'allotment') {
                    await loadAllotmentCharts();
                }

                if (filterType === 'all' || filterType === 'expenditure') {
                    await loadExpenditureCharts();
                }
            } catch (e) {
                console.error(e);
            }
        })();
    </script>
@endsection
