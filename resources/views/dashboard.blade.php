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
                    @if($filter_type === 'allotment_expenditure')
                        Budget Utilization ({{ $year }})
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
                            class="font-['Bricolage_Grotesque'] font-extrabold text-sm whitespace-nowrap">Allotment
                            Type:</label>
                        <div class="relative w-full lg:w-32">
                            <select name="type_filter" id="type_filter" onchange="updateFilters()"
                                class="w-full appearance-none rounded-full bg-gray-100 text-center shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-300 cursor-pointer py-1 px-4">

                                {{-- <option value="all" {{ $filter_type==='all' ? 'selected' : '' }}>All</option> --}}
                                {{-- <option value="allotment" {{ $filter_type==='allotment' ? 'selected' : '' }}>Allotment
                                </option> --}}
                                {{-- <option value="expenditure" {{ $filter_type==='expenditure' ? 'selected' : '' }}>
                                    Expenditure</option> --}}
                                <option value="suc_income" {{ $filter_type === 'suc_income' ? 'selected' : '' }}>SUC Income
                                </option>
                                <option value="allotment_expenditure" {{ $filter_type === 'allotment_expenditure' ? 'selected' : '' }}>Budget Utilization</option>
                                {{-- <option value="expenditure" {{ $filter_type==='expenditure' ? 'selected' : '' }}>
                                    Expenditure</option>
                                <option value="allotment" {{ $filter_type==='allotment' ? 'selected' : '' }}>Allotment
                                </option>
                                <option value="allotment" {{ $filter_type==='allotment' ? 'selected' : '' }}>Allotment +
                                    Expenditure</option> --}}

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
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                    {{ $income['grand_total_income'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semibold text-white mt-1">Total
                                    University
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
                                    {{ $income['tuition_misc_fee'] }}
                                </p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Total Academic Fees
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-building text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">
                                    {{ $income['miscellaneous'] }}
                                </p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Auxiliary &amp;
                                    Business
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
                                    {{ $income['other_income'] }}
                                </p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">Other Business Income
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INCOME CHARTS -->
                <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col" style="overflow: hidden;">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Total University
                                Income Breakdown</h3>
                            <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                <div id="mainPieChart" class="w-full h-full"></div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Total Academic Fees
                                Breakdown</h3>
                            <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                <div id="tuitionPieChart" class="w-full h-full"></div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col w-full lg:max-w-2xl">
                            <h3 class="font-['inter'] text-sm font-bold text-gray-700 mb-4 text-center">Other Business
                                Income Breakdown</h3>
                            <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                <div id="otherIncomePieChart" class="w-full h-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- ALLOTMENT and EXPENDITURE SECTION -->
            <!-- ============================================================ -->

            <div id="budget_utilization_section"
                class="{{ !in_array($filter_type, ['all', 'allotment_expenditure']) ? 'hidden' : '' }}">
                {{-- Value Cards --}}
                <div class="py-6 sm:py-8">
                    {{-- <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-wallet text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 flex flex-col items-end text-right text-white space-y-3">
                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $allotment['combined_total'] }}
                                    </p>
                                    <span class="text-sm md:text-base font-medium opacity-90">
                                        Total University Allotment
                                    </span>
                                </div>

                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $expenditure['combined_total'] }}
                                    </p>
                                    <span class="text-sm md:text-base font-medium opacity-90">
                                        Total University Expenditure
                                    </span>
                                </div>

                                <div class="pt-1">
                                    <p class="font-[Inter] text-[18px] md:text-[20px] font-semibold leading-tight">
                                        Total University Budget Utilization
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-landmark text-white text-xl"></i>
                            </div>
                            <div class="mt-12 flex flex-col items-end text-right text-black space-y-3">
                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $allotment['gaa_total']  }}
                                    </p>
                                    <span class="text-sm md:text-base font-medium opacity-90">
                                        Total University GAA Allotment
                                    </span>
                                </div>

                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $expenditure['gaa_total']}}
                                    </p>
                                    <span class="text-sm md:text-base font-medium opacity-90">
                                        Total University GAA Expenditure
                                    </span>
                                </div>

                                <div class="pt-1">
                                    <p class="font-[Inter] text-[18px] md:text-[20px] font-semibold leading-tight">
                                        Total University Budget Utilization (GAA)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-arrow-trend-up text-white text-xl"></i>
                            </div>
                            <div class="mt-12 flex flex-col items-end text-right text-black space-y-3">
                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $allotment['suc_total']  }}
                                    </p>
                                    <span class="text-xs md:text-sm font-medium text-gray-500">
                                        Total University SUC Income Allotment
                                    </span>
                                </div>

                                <div>
                                    <p class="font-[Inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                        {{ $expenditure['suc_total']}}
                                    </p>
                                    <span class="text-sm md:text-base font-medium opacity-90">
                                        Total University SUC Income Expenditure
                                    </span>
                                </div>

                                <div class="pt-1">
                                    <p class="font-[Inter] text-[18px] md:text-[20px] font-semibold leading-tight">
                                        Total University Budget Utilization (SUC Income)
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    {{-- othe ui for value cards --}}
                    {{-- allotment --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 mb-4">

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
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{
                                    $allotment['suc_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">SUC Income Allotment
                                </p>
                            </div>
                        </div>
                    </div>
                    {{-- expenditure --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">

                        <div
                            class="relative bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-white/90 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-arrow-trend-down text-green-600 text-2xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-extrabold leading-tight">
                                    {{ $expenditure['combined_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] font-semibold  text-white">Total
                                    University Expenditure
                                </p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-receipt text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{
                                    $expenditure['gaa_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">GAA Expenditure</p>
                            </div>
                        </div>

                        <div class="relative bg-white rounded-2xl p-6 shadow-md">
                            <div
                                class="absolute top-4 left-4 w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-credit-card text-white text-xl"></i>
                            </div>
                            <div class="mt-12 text-right">
                                <p class="font-[inter] text-[20px] md:text-[24px] font-bold text-gray-900">{{
                                    $expenditure['suc_total'] }}</p>
                                <p class="text-[20px] md:text-[16px] font-[inter] text-gray-500 mt-1">SUC Income Expenditure
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- charts --}}
                    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 lg:p-8 mb-8">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

                            {{-- Pie Chart --}}
                            {{-- Allotment --}}
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col"
                                style="overflow: hidden;">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Distribution by Funding Source
                                    (GAA)</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="allotmentPieChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            {{-- Expenditure --}}
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col"
                                style="overflow: hidden;">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Distribution of Total
                                    Expenditures (GAA)</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="expenditurePieChart" class="w-full h-full"></div>
                                </div>
                            </div>

                            {{-- Donut Chart --}}
                            {{-- Allotment --}}
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Total GAA Allotment by Expense
                                    Class
                                </h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="allotmentCategoryChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            {{-- Expediture --}}
                            <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Total GAA Expenditure by
                                    Expense Class</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="expenditureCategoryChart" class="w-full h-full"></div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">GAA Allotment by Expense Class
                                </h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="allotmentGAAChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">GAA Expenditure by Expense
                                    Class</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="expenditureGAAChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">SUC Income Allotment by Expense
                                    Class</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="allotmentSUCChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">SUC Income Expenditure by
                                    Expense Class</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="expenditureSUCChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col lg:col-span-2">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">
                                    Budget Utilization by Institutional Function
                                </h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="budgetUtilizationFunctionChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            {{-- <div class="bg-gray-50 rounded-xl p-4 sm:p-6 shadow-inner flex flex-col lg:col-span-2">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center">Total Allotment by
                                    Institutional
                                    Function</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="allotmentFunctionChart" class="w-full h-full"></div>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl p-6 shadow-inner flex flex-col lg:col-span-2">
                                <h3 class="text-sm font-bold text-gray-700 mb-4 text-center ">Total Expenditure by
                                    Institutional Function</h3>
                                <div class="relative w-full min-h-[420px] sm:min-h-[460px]" style="overflow: hidden;">
                                    <div id="expenditureFunctionChart" class="w-full h-full"></div>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.plot.ly/plotly-2.35.2.min.js"></script>

    <script>
        const selectedYear = @json($year);
        const filterType = @json($filter_type);

        const sucYears = @json($suc_years_chart ?? []);
        const sucTotals = @json($suc_totals ?? []);

        const chartColors = [
            '#007B3E', '#FFD700', '#39EDFF', '#FFE450', '#FFB495',
            '#FFC177', '#FFA8F7', '#00FFFF', '#E5E5E5', '#E06B0D',
            '#567F13', '#1A5F30'
        ];

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

        function toggleChartCard(chartId, show) {
            const el = $(chartId);
            if (!el) return;
            const card = el.closest('.bg-gray-50') || el.parentElement;
            if (card) card.classList.toggle('hidden', !show);
        }

        function peso(v) {
            return '₱' + Number(v || 0).toLocaleString('en-US');
        }

        function compactPeso(v) {
            v = Number(v || 0);
            if (v >= 1_000_000_000) return '₱' + (v / 1_000_000_000).toFixed(1).replace(/\.0$/, '') + 'B';
            if (v >= 1_000_000) return '₱' + (v / 1_000_000).toFixed(1).replace(/\.0$/, '') + 'M';
            if (v >= 1_000) return '₱' + (v / 1_000).toFixed(1).replace(/\.0$/, '') + 'K';
            return '₱' + v.toLocaleString('en-US');
        }

        function safeResize(id) {
            const el = $(id);
            if (!el) return;

            requestAnimationFrame(() => {
                Plotly.Plots.resize(el);
            });

            setTimeout(() => {
                Plotly.Plots.resize(el);
            }, 250);
        }

        function baseLayout(title = '') {
            return {
                title: title ? {
                    text: title,
                    font: {
                        family: 'Inter, sans-serif',
                        size: 14,
                        color: '#1f2937'
                    },
                    x: 0.5,
                    xanchor: 'center',
                    y: 0.98
                } : undefined,
                margin: {
                    t: 30,
                    r: 30,
                    b: 30,
                    l: 30
                },
                paper_bgcolor: 'transparent',
                plot_bgcolor: 'transparent',
                font: {
                    family: 'Inter, sans-serif',
                    color: '#111827',
                    size: 12
                },
                legend: {
                    orientation: 'h',
                    x: 0.5,
                    xanchor: 'center',
                    y: 1.08,
                    yanchor: 'bottom',
                    font: {
                        size: 11
                    }
                },
                autosize: true
            };
        }

        function renderPie(chartId, labels, values, colors, hole = 0, showPercent = true) {
            const el = $(chartId);
            if (!el) return;

            const cleanData = (labels || []).map((label, i) => ({
                label,
                value: n(values?.[i])
            })).filter(item => item.value > 0);

            const cleanLabels = cleanData.map(i => i.label);
            const cleanValues = cleanData.map(i => i.value);

            const total = cleanValues.reduce((a, b) => a + b, 0);

            if (!cleanLabels.length || !cleanValues.length || total <= 0) {
                Plotly.purge(chartId);
                toggleChartCard(chartId, false);
                return;
            }

            toggleChartCard(chartId, true);

            const trace = {
                type: 'pie',
                labels: cleanLabels,
                values: cleanValues,
                hole,
                sort: false,
                direction: 'clockwise',
                marker: {
                    colors: colors.slice(0, cleanLabels.length),
                    line: { color: '#ffffff', width: 2 }
                },
                textinfo: showPercent ? 'percent' : 'none',
                texttemplate: showPercent ? '%{percent:.1%}' : '',
                textposition: 'outside',
                automargin: true,
                hovertemplate: '<b>%{label}</b><br>%{value:,.2f}<br>%{percent}<extra></extra>',
                outsidetextfont: {
                    family: 'Inter, sans-serif',
                    size: 11,
                    color: '#111827'
                },
                pull: 0,
                domain: { x: [0.10, 0.90], y: [0.02, 0.72] }
            };

            const layout = {
                ...baseLayout(),
                margin: {
                    t: 5,
                    r: 5,
                    b: 5,
                    l: 5
                },
                showlegend: total > 0,
                legend: {
                    orientation: 'h',
                    x: 0.5,
                    xanchor: 'center',
                    y: 1.08,
                    yanchor: 'top',
                    font: {
                        size: 12
                    }
                },
                uniformtext: {
                    minsize: 10,
                    mode: 'hide'
                },
                annotations: hole > 0 && total > 0 ? [
                    {
                        x: 0.5,
                        y: 0.40,
                        xref: 'paper',
                        yref: 'paper',
                        text: '<b>Total</b>',
                        showarrow: false,
                        font: {
                            size: 13,
                            color: '#6b7280',
                            family: 'Inter, sans-serif'
                        }
                    },
                    {
                        x: 0.5,
                        y: 0.31,
                        xref: 'paper',
                        yref: 'paper',
                        text: `<b>${peso(total)}</b>`,
                        showarrow: false,
                        font: {
                            size: 16,
                            color: '#111827',
                            family: 'Inter, sans-serif'
                        }
                    }
                ] : []
            };

            Plotly.newPlot(chartId, [trace], layout, {
                responsive: true,
                displayModeBar: false
            });

            safeResize(chartId);
        }

        function renderStackedBar(chartId, labels, seriesAName, seriesAData, seriesBName, seriesBData, colorA, colorB) {
            const el = $(chartId);
            if (!el) return;

            if (!labels.length) {
                toggleChartCard(chartId, false);
                return;
            }

            toggleChartCard(chartId, true);

            const totals = labels.map((_, i) => n(seriesAData[i]) + n(seriesBData[i]));

            const trace1 = {
                type: 'bar',
                name: seriesAName,
                x: labels,
                y: seriesAData,
                marker: { color: colorA },
                hovertemplate: '<b>%{x}</b><br>' + seriesAName + ': %{y:,.2f}<extra></extra>'
            };

            const trace2 = {
                type: 'bar',
                name: seriesBName,
                x: labels,
                y: seriesBData,
                marker: { color: colorB },
                hovertemplate: '<b>%{x}</b><br>' + seriesBName + ': %{y:,.2f}<extra></extra>'
            };

            const totalLabels = {
                type: 'scatter',
                mode: 'text',
                x: labels,
                y: totals,
                text: totals.map(v => `<b>${compactPeso(v)}</b>`),
                textposition: 'top center',
                textfont: {
                    family: 'Inter, sans-serif',
                    size: 11,
                    color: '#111827'
                },
                hoverinfo: 'skip',
                showlegend: false
            };

            const layout = {
                ...baseLayout(),
                barmode: 'stack',
                margin: {
                    t: 30,
                    r: 40,
                    b: 80,
                    l: 80
                },
                legend: {
                    orientation: 'h',
                    x: 0.5,
                    xanchor: 'center',
                    y: 1.05,
                    yanchor: 'bottom',
                    font: { size: 11 }
                },
                xaxis: {
                    tickangle: -18,
                    automargin: true
                },
                yaxis: {
                    rangemode: 'tozero',
                    automargin: true,
                    tickformat: '.3s',
                    tickprefix: '₱'
                }
            };

            Plotly.newPlot(chartId, [trace1, trace2, totalLabels], layout, {
                responsive: true,
                displayModeBar: false
            });
            safeResize(chartId);
        }

        // function renderCombinedBudgetStackedBar(chartId, rows) {
        //     const el = $(chartId);
        //     if (!el) return;

        //     const cleanRows = (rows || []).filter(r =>
        //         n(r.gaa_allotment) > 0 ||
        //         n(r.suc_allotment) > 0 ||
        //         n(r.gaa_expenditure) > 0 ||
        //         n(r.suc_expenditure) > 0
        //     );

        //     if (!cleanRows.length) {
        //         Plotly.purge(chartId);
        //         toggleChartCard(chartId, false);
        //         return;
        //     }

        //     toggleChartCard(chartId, true);

        //     const labels = cleanRows.map(r => r.fn);
        //     const gaaAllotment = cleanRows.map(r => n(r.gaa_allotment));
        //     const sucAllotment = cleanRows.map(r => n(r.suc_allotment));
        //     const gaaExpenditure = cleanRows.map(r => n(r.gaa_expenditure));
        //     const sucExpenditure = cleanRows.map(r => n(r.suc_expenditure));

        //     const totals = cleanRows.map(r =>
        //         n(r.gaa_allotment) +
        //         n(r.suc_allotment) +
        //         n(r.gaa_expenditure) +
        //         n(r.suc_expenditure)
        //     );

        //     const maxTotal = Math.max(...totals, 0);

        //     const traces = [
        //         {
        //             type: 'bar',
        //             name: 'GAA Allotment',
        //             x: labels,
        //             y: gaaAllotment,
        //             marker: { color: '#007B3E' },
        //             hovertemplate: '<b>%{x}</b><br>GAA Allotment: %{y:,.2f}<extra></extra>'
        //         },
        //         {
        //             type: 'bar',
        //             name: 'SUC Income Allotment',
        //             x: labels,
        //             y: sucAllotment,
        //             marker: { color: '#FFD700' },
        //             hovertemplate: '<b>%{x}</b><br>SUC Income Allotment: %{y:,.2f}<extra></extra>'
        //         },
        //         {
        //             type: 'bar',
        //             name: 'GAA Expenditure',
        //             x: labels,
        //             y: gaaExpenditure,
        //             marker: { color: '#39EDFF' },
        //             hovertemplate: '<b>%{x}</b><br>GAA Expenditure: %{y:,.2f}<extra></extra>'
        //         },
        //         {
        //             type: 'bar',
        //             name: 'SUC Income Expenditure',
        //             x: labels,
        //             y: sucExpenditure,
        //             marker: { color: '#FFB495' },
        //             hovertemplate: '<b>%{x}</b><br>SUC Income Expenditure: %{y:,.2f}<extra></extra>'
        //         },
        //         {
        //             type: 'scatter',
        //             mode: 'text',
        //             x: labels,
        //             y: totals.map(v => v + (maxTotal * 0.03)),
        //             text: totals.map(v => `<b>${compactPeso(v)}</b>`),
        //             textposition: 'top center',
        //             textfont: {
        //                 family: 'Inter, sans-serif',
        //                 size: 11,
        //                 color: '#111827'
        //             },
        //             hoverinfo: 'skip',
        //             showlegend: false
        //         }
        //     ];

        //     const layout = {
        //         ...baseLayout(),
        //         barmode: 'stack',
        //         margin: {
        //             t: 50,
        //             r: 30,
        //             b: 100,
        //             l: 80
        //         },
        //         legend: {
        //             orientation: 'h',
        //             x: 0.5,
        //             xanchor: 'center',
        //             y: 1.1,
        //             yanchor: 'bottom',
        //             font: { size: 11 }
        //         },
        //         xaxis: {
        //             tickangle: -20,
        //             automargin: true
        //         },
        //         yaxis: {
        //             rangemode: 'tozero',
        //             automargin: true,
        //             tickformat: '.3s',
        //             tickprefix: '₱'
        //         }
        //     };

        //     Plotly.newPlot(chartId, traces, layout, {
        //         responsive: true,
        //         displayModeBar: false
        //     });

        //     safeResize(chartId);
        // }

        function renderLine(chartId, labels, values, lineName) {
            const el = $(chartId);
            if (!el) return;

            if (!labels.length || !values.length) {
                toggleChartCard(chartId, false);
                return;
            }

            toggleChartCard(chartId, true);

            const trace = {
                type: 'scatter',
                mode: 'lines+markers',
                name: lineName,
                x: labels,
                y: values,
                line: {
                    color: '#16a34a',
                    width: 3,
                    shape: 'spline'
                },
                marker: {
                    size: 8,
                    color: '#16a34a'
                },
                fill: 'tozeroy',
                fillcolor: 'rgba(22,163,74,0.18)',
                hovertemplate: '<b>%{x}</b><br>' + peso('%{y}') + '<extra></extra>'
            };

            const layout = {
                ...baseLayout(),
                margin: { t: 50, r: 30, b: 50, l: 70 },
                xaxis: { automargin: true },
                yaxis: {
                    rangemode: 'tozero',
                    tickprefix: '₱',
                    tickformat: ','
                }
            };

            Plotly.newPlot(chartId, [trace], layout, {
                responsive: true,
                displayModeBar: false,
            });
            safeResize(chartId);
        }

        async function fetchJson(url) {
            const res = await fetch(url);
            if (!res.ok) throw new Error(`Request failed: ${res.status} ${url}`);
            return res.json();
        }

        // ---------- SUC INCOME ----------
        async function loadIncomeCharts() {
            const data = await fetchJson(`/api/income-data?year=${encodeURIComponent(selectedYear)}`);

            let allItems = [
                ...(data.breakdown?.tuition_details || []),
                ...(data.breakdown?.other_income_details || [])
            ];

            allItems = normalizeItems(allItems)
                .sort((a, b) => b.value - a.value);

            if (allItems.length > 4) {
                const top4 = allItems.slice(0, 4);
                const othersTotal = allItems.slice(4).reduce((s, i) => s + i.value, 0);

                if (othersTotal > 0) {
                    top4.push({ name: 'Others', value: othersTotal });
                }

                allItems = top4;
            }

            allItems = allItems.sort((a, b) => {
                if (a.name === 'Others') return 1;
                if (b.name === 'Others') return -1;
                return b.value - a.value;
            });

            renderPie(
                'mainPieChart',
                allItems.map(i => i.name),
                allItems.map(i => i.value),
                chartColors,
                0,
                true
            );

            const tuitionItems = normalizeItems(data.breakdown?.tuition_details);
            renderPie(
                'tuitionPieChart',
                tuitionItems.map(i => i.name),
                tuitionItems.map(i => i.value),
                chartColors,
                0,
                true
            );

            const otherItems = normalizeItems(data.breakdown?.other_income_details);
            renderPie(
                'otherIncomePieChart',
                otherItems.map(i => i.name),
                otherItems.map(i => i.value),
                chartColors,
                0,
                true
            );

            if ($('sucIncomeLineChart')) {
                renderLine('sucIncomeLineChart', sucYears, sucTotals, 'Total SUC Income');
            }
        }

        // ---------- ALLOTMENT ----------
        async function loadAllotmentCharts() {
            const data = await fetchJson(`/api/allotment-data?year=${encodeURIComponent(selectedYear)}`);

            renderPie(
                'allotmentPieChart',
                ['GAA Allotment', 'SUC Income Allotment'],
                [n(data.gaa?.total), n(data.suc_income?.total)],
                ['#007B3E', '#FFD700'],
                0,
                true
            );

            const totalPS = n(data.gaa?.ps) + n(data.suc_income?.ps);
            const totalMOOE = n(data.gaa?.mooe) + n(data.suc_income?.mooe);
            const totalCO = n(data.gaa?.co) + n(data.suc_income?.co);

            renderPie(
                'allotmentCategoryChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [totalPS, totalMOOE, totalCO],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            renderPie(
                'allotmentGAAChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(data.gaa?.ps), n(data.gaa?.mooe), n(data.gaa?.co)],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            renderPie(
                'allotmentSUCChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(data.suc_income?.ps), n(data.suc_income?.mooe), n(data.suc_income?.co)],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            // const rows = (data.breakdown || [])
            //     .map(r => ({
            //         fn: String(r.function || '').trim(),
            //         gaa: n(r.gaa_total),
            //         suc: n(r.suc_total)
            //     }))
            //     .filter(r => (r.gaa + r.suc) > 0);

            // renderStackedBar(
            //     'allotmentFunctionChart',
            //     rows.map(r => r.fn),
            //     'GAA Allotment',
            //     rows.map(r => r.gaa),
            //     'SUC Income Allotment',
            //     rows.map(r => r.suc),
            //     '#007B3E',
            //     '#FFD700'
            // );

            return data;
        }

        // ---------- EXPENDITURE ----------
        async function loadExpenditureCharts() {
            const expData = await fetchJson(`/api/expenditure-data?year=${encodeURIComponent(selectedYear)}`);

            renderPie(
                'expenditurePieChart',
                ['GAA Expenditure', 'SUC Income Expenditure'],
                [n(expData.gaa?.total), n(expData.suc_income?.total)],
                ['#007B3E', '#FFD700'],
                0,
                true
            );

            const ePS = n(expData.gaa?.ps) + n(expData.suc_income?.ps);
            const eMOOE = n(expData.gaa?.mooe) + n(expData.suc_income?.mooe);
            const eCO = n(expData.gaa?.co) + n(expData.suc_income?.co);

            renderPie(
                'expenditureCategoryChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [ePS, eMOOE, eCO],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            renderPie(
                'expenditureGAAChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(expData.gaa?.ps), n(expData.gaa?.mooe), n(expData.gaa?.co)],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            renderPie(
                'expenditureSUCChart',
                ['Personal Services (PS)', 'Maintenance and Other Operating Expenses (MOOE)', 'Capital Outlay (CO)'],
                [n(expData.suc_income?.ps), n(expData.suc_income?.mooe), n(expData.suc_income?.co)],
                ['#007B3E', '#FFD700', '#39EDFF'],
                0.55,
                true
            );

            // const rows = (expData.breakdown || [])
            //     .map(r => ({
            //         fn: String(r.function || '').trim(),
            //         gaa: n(r.gaa_total),
            //         suc: n(r.suc_total)
            //     }))
            //     .filter(r => (r.gaa + r.suc) > 0);

            // renderStackedBar(
            //     'expenditureFunctionChart',
            //     rows.map(r => r.fn),
            //     'GAA Expenditure',
            //     rows.map(r => r.gaa),
            //     'SUC Income Expenditure',
            //     rows.map(r => r.suc),
            //     '#007B3E',
            //     '#FFD700'
            // );

            return expData;
        }

        window.addEventListener('resize', () => {
            [
                'mainPieChart',
                'tuitionPieChart',
                'otherIncomePieChart',
                'allotmentPieChart',
                'allotmentCategoryChart',
                'allotmentGAAChart',
                'allotmentSUCChart',
                // 'allotmentFunctionChart',
                'expenditurePieChart',
                'expenditureCategoryChart',
                'expenditureGAAChart',
                'expenditureSUCChart',
                // 'expenditureFunctionChart',
                'sucIncomeLineChart',
                'budgetUtilizationFunctionChart',
            ].forEach(id => {
                if ($(id)) Plotly.Plots.resize($(id));
            });
        });

        function renderBudgetComparisonBar(chartId, rows) {
            const el = $(chartId);
            if (!el) return;

            const cleanRows = (rows || []).filter(r => {
                return (n(r.gaa_allotment) + n(r.suc_allotment)) > 0 ||
                    (n(r.gaa_expenditure) + n(r.suc_expenditure)) > 0;
            });

            if (!cleanRows.length) {
                Plotly.purge(chartId);
                toggleChartCard(chartId, false);
                return;
            }

            toggleChartCard(chartId, true);

            // Only include rows that actually have allotment/expenditure data
            const allotRows = cleanRows.filter(r => n(r.gaa_allotment) + n(r.suc_allotment) > 0);
            const expRows = cleanRows.filter(r => n(r.gaa_expenditure) + n(r.suc_expenditure) > 0);

            // Build multicategory x-arrays only for rows with data
            const xA = [allotRows.map(r => wrapLabel(r.fn)), allotRows.map(() => '\u200B')];
            const xE = [expRows.map(r => wrapLabel(r.fn)), expRows.map(() => '\u200C')];

            const gaaA = allotRows.map(r => n(r.gaa_allotment) > 0 ? n(r.gaa_allotment) : null);
            const sucA = allotRows.map(r => n(r.suc_allotment) > 0 ? n(r.suc_allotment) : null);
            const gaaE = expRows.map(r => n(r.gaa_expenditure) > 0 ? n(r.gaa_expenditure) : null);
            const sucE = expRows.map(r => n(r.suc_expenditure) > 0 ? n(r.suc_expenditure) : null);

            const allotTotals = allotRows.map(r => n(r.gaa_allotment) + n(r.suc_allotment));
            const expTotals = expRows.map(r => n(r.gaa_expenditure) + n(r.suc_expenditure));

            const maxY = Math.max(...allotTotals, ...expTotals, 0);

            const traces = [];

            if (gaaA.some(v => v !== null)) {
                traces.push({
                    type: 'bar', name: 'GAA Allotment',
                    x: xA, y: gaaA,
                    marker: { color: '#007B3E' },
                    hovertemplate: '<b>%{x[0]}</b><br>GAA Allotment: %{y:,.2f}<extra></extra>'
                });
            }
            if (sucA.some(v => v !== null)) {
                traces.push({
                    type: 'bar', name: 'SUC Income Allotment',
                    x: xA, y: sucA,
                    marker: { color: '#FFD700' },
                    hovertemplate: '<b>%{x[0]}</b><br>SUC Income Allotment: %{y:,.2f}<extra></extra>'
                });
            }
            if (gaaE.some(v => v !== null)) {
                traces.push({
                    type: 'bar', name: 'GAA Expenditure',
                    x: xE, y: gaaE,
                    marker: { color: '#39EDFF' },
                    hovertemplate: '<b>%{x[0]}</b><br>GAA Expenditure: %{y:,.2f}<extra></extra>'
                });
            }
            if (sucE.some(v => v !== null)) {
                traces.push({
                    type: 'bar', name: 'SUC Income Expenditure',
                    x: xE, y: sucE,
                    marker: { color: '#EA7C69' },
                    hovertemplate: '<b>%{x[0]}</b><br>SUC Income Expenditure: %{y:,.2f}<extra></extra>'
                });
            }

            // Total labels — only for rows with actual data
            traces.push({
                type: 'scatter', mode: 'text', x: xA,
                y: allotTotals.map(v => v > 0 ? v + maxY * 0.03 : null),
                text: allotTotals.map(v => v > 0 ? `<b>${compactPeso(v)}</b>` : ''),
                textposition: 'top center',
                textfont: { family: 'Inter, sans-serif', size: 11, color: '#111827' },
                hoverinfo: 'skip', showlegend: false
            });
            traces.push({
                type: 'scatter', mode: 'text', x: xE,
                y: expTotals.map(v => v > 0 ? v + maxY * 0.03 : null),
                text: expTotals.map(v => v > 0 ? `<b>${compactPeso(v)}</b>` : ''),
                textposition: 'top center',
                textfont: { family: 'Inter, sans-serif', size: 11, color: '#111827' },
                hoverinfo: 'skip', showlegend: false
            });

            const layout = {
                ...baseLayout(),
                barmode: 'stack',
                bargap: 0.25,
                bargroupgap: 0.1,
                margin: { t: 60, r: 30, b: 120, l: 80 },
                legend: {
                    orientation: 'h', x: 0.5, xanchor: 'center',
                    y: 1.12, yanchor: 'bottom', font: { size: 11 }
                },
                xaxis: {
                    type: 'multicategory', tickangle: 0, automargin: true,
                    showgrid: false, zeroline: false, showline: false,
                    ticks: '', tickfont: { size: 11 },
                    showdividers: false,
                },
                yaxis: {
                    rangemode: 'tozero', automargin: true,
                    tickprefix: '₱', tickformat: '.2s',
                    range: [0, maxY * 1.18]
                }
            };

            Plotly.newPlot(chartId, traces, layout, { responsive: true, displayModeBar: false });
            safeResize(chartId);
        }

        function wrapLabel(text, maxLength = 12) {
            if (!text) return '';

            const words = text.split(' ');
            let lines = [];
            let current = '';

            words.forEach(word => {
                if ((current + ' ' + word).length > maxLength) {
                    lines.push(current);
                    current = word;
                } else {
                    current = current ? current + ' ' + word : word;
                }
            });

            if (current) lines.push(current);

            return lines.join('<br>');
        }

        function buildCombinedBudgetRows(allotmentRows, expenditureRows) {
            const map = {};

            (allotmentRows || []).forEach(r => {
                const fn = String(r?.function || '').trim();
                if (!fn) return;

                if (!map[fn]) {
                    map[fn] = {
                        fn,
                        gaa_allotment: 0,
                        suc_allotment: 0,
                        gaa_expenditure: 0,
                        suc_expenditure: 0
                    };
                }

                map[fn].gaa_allotment += n(r.gaa_total);
                map[fn].suc_allotment += n(r.suc_total);
            });

            (expenditureRows || []).forEach(r => {
                const fn = String(r?.function || '').trim();
                if (!fn) return;

                if (!map[fn]) {
                    map[fn] = {
                        fn,
                        gaa_allotment: 0,
                        suc_allotment: 0,
                        gaa_expenditure: 0,
                        suc_expenditure: 0
                    };
                }

                map[fn].gaa_expenditure += n(r.gaa_total);
                map[fn].suc_expenditure += n(r.suc_total);
            });

            return Object.values(map).sort((a, b) =>
                (b.gaa_allotment + b.suc_allotment + b.gaa_expenditure + b.suc_expenditure) -
                (a.gaa_allotment + a.suc_allotment + a.gaa_expenditure + a.suc_expenditure)
            );
        }

        (async function init() {
            try {
                if (filterType === 'all' || filterType === 'suc_income') {
                    await loadIncomeCharts();
                }

                if (filterType === 'all' || filterType === 'allotment_expenditure') {
                    const allotmentData = await loadAllotmentCharts();
                    const expenditureData = await loadExpenditureCharts();

                    console.log('Allotment API:', allotmentData);
                    console.log('Expenditure API:', expenditureData);

                    const allotmentRows = Array.isArray(allotmentData?.breakdown)
                        ? allotmentData.breakdown
                        : [];

                    const expenditureRows = Array.isArray(expenditureData?.breakdown)
                        ? expenditureData.breakdown
                        : [];

                    console.log('Allotment breakdown rows:', allotmentRows);
                    console.log('Expenditure breakdown rows:', expenditureRows);

                    const combinedRows = buildCombinedBudgetRows(allotmentRows, expenditureRows);

                    console.log('Combined budget rows:', combinedRows);

                    renderBudgetComparisonBar('budgetUtilizationFunctionChart', combinedRows);
                }

            } catch (e) {
                console.error(e);
            }
        })();

    </script>
@endsection
