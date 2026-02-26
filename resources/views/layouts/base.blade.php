<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Normative Funding | CLSU Analytica</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--
    <link rel="stylesheet" href="{{ asset('css/output.css') }}" /> --}}
    <link href="https://fonts.cdnfonts.com/css/buttershine-serif" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.cdnfonts.com/css/anton" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/bricolage-grotesque" rel="stylesheet" />
    <link href="https://fonts.cdnfonts.com/css/inter" rel="stylesheet" />
</head>

<body class="min-h-screen bg-[#D9D9D9] overflow-x-hidden">
    <div class="flex h-screen">

        <!-- MOBILE OVERLAY -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="closeSidebar()">
        </div>

        <!-- SIDEBAR -->
        <aside id="sidebar" class="fixed md:static inset-y-0 left-0 z-50
               w-72 md:w-64
               bg-[#252525] text-gray-300
               flex flex-col
               transform -translate-x-full md:translate-x-0
               transition-transform duration-200 ease-in-out">

            <!-- Brand -->
            <div class="pt-6 flex flex-col justify-center items-center">
                <img src="{{ asset('images/Seal Monogram_white.png') }}" alt="CLSU Seal"
                    class="h-20 w-20 md:h-24 md:w-24 object-cover" />
                <p class="text-2xl md:text-3xl font-[buttershine-serif] mt-2">ANALYTICA</p>
            </div>

            <div class="mt-3 flex justify-center items-center px-4">
                <div class="w-full border-t border-gray-300"></div>
            </div>

            <!-- Search -->
            <div class="flex justify-center items-center p-4">
                <div class="relative w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-black"></i>
                    <input type="search" placeholder="Search Here"
                        class="w-full rounded-3xl border border-gray-300 p-3 pl-12 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto">

                <!-- Dashboard -->
                <a href="#" class="block px-6 py-2 hover:bg-slate-700 hover:text-white">
                    <div class="flex flex-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M5 3a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4M5 10a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4M5 17a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4m7.33 0a2 2 0 1 0 0 4 2 2 0 1 0 0-4" />
                        </svg>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">Dashboard</span>
                    </div>
                </a>

                {{-- Students --}}
                <div class="px-2">
                    <button onclick="toggleMenu('student-menu','student-arrow')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-700 hover:text-white transition">
                        <div class="flex items-center">
                            <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24" >
                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                <path d="m21.45 8.61-9-4.5a1 1 0 0 0-.89 0l-6 3-3 1.5-1 .5a1 1 0 0 0-.55.89v6h2v-5.38l2 1v3.83c0 2.06 3.12 4.56 7 4.56s7-2.49 7-4.56v-3.83l2.45-1.22c.34-.17.55-.52.55-.89s-.21-.72-.55-.89Zm-15 .29L12 6.12l6.76 3.38L12 12.88 5.24 9.5l1.21-.61ZM17 15.45c0 .76-2.11 2.56-5 2.56s-5-1.79-5-2.56v-2.83l4.55 2.28c.14.07.29.11.45.11s.31-.04.45-.11L17 12.62z"></path>
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">Students</span>
                        </div>
                        <i id="student-arrow"
                            class="fa-solid fa-circle-chevron-down transition-transform duration-300
                                {{ $active_page === 'graduates' ? 'rotate-180' : '' }}">
                        </i>
                    </button>
                    <div id="student-menu"
                        class="ml-8 mt-1 space-y-2 overflow-hidden transition-all duration-300 max-h-0
                        {{ $active_page === 'graduates' ? 'max-h-40' : 'max-h-0' }}"">
                            <a href="{{ route('graduates') }}"
                                class="block text-sm pl-4 py-2 text-white hover:text-white hover:bg-slate-700 font-['Bricolage_Grotesque']
                                    {{ $active_page === 'graduates' ? 'bg-slate-700 text-white font-bold border-l-4 border-[#008232]' : '' }}">
                                Graduation
                            </a>
                    </div>
                </div>

                <!-- Faculty -->
                <div class="px-2">
                    <button onclick="toggleMenu('faculty-menu','faculty-arrow')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-700 hover:text-white transition">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M20 6h-3V4c0-1.1-.9-2-2-2H9c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2M9 4h6v2H9zM4 20V8h16v12z" />
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">Faculty</span>
                        </div>
                        <i id="faculty-arrow"
                            class="fa-solid fa-circle-chevron-down transition-transform duration-300
                                {{ $active_page === 'suc-faculty' ? 'rotate-180' : '' }}">
                        </i>
                    </button>
                    <div id="faculty-menu"
                        class="ml-8 mt-1 space-y-2 overflow-hidden transition-all duration-300 max-h-0
                        {{ $active_page === 'suc-faculty' ? 'max-h-40' : 'max-h-0' }}"">
                            <a href="{{ route('suc-faculty') }}"
                                class="block text-sm pl-4 py-2 text-white hover:text-white hover:bg-slate-700 font-['Bricolage_Grotesque']
                                    {{ $active_page === 'suc-faculty' ? 'bg-slate-700 text-white font-bold border-l-4 border-[#008232]' : '' }}">
                                SUC Faculty
                            </a>
                    </div>
                </div>

                <!-- Normative Funding -->
                <div class="px-2">
                    <button onclick="toggleMenu('normative-menu','normative-arrow')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-700 hover:text-white transition">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path
                                    d="M20 7h-3V3c0-.33-.16-.64-.43-.82a.98.98 0 0 0-.92-.11L3.28 6.82C2.51 7.11 2 7.87 2 8.69V20c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2m-5-2.54V7H8.39zM4 20V9h16v2h-5c-1.1 0-2 .9-2 2v3c0 1.1.9 2 2 2h5v2zm16-4h-5v-3h5z" />
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">Financial Reports</span>
                        </div>
                        <i id="normative-arrow" class="fa-solid fa-circle-chevron-down transition-transform duration-300
                            {{ $active_page === 'normative_breakdown' ? 'rotate-180' : '' }}">
                        </i>
                    </button>

                    <div id="normative-menu"
                        class="ml-8 mt-1 space-y-2 overflow-hidden transition-all duration-300
                            {{ $active_page === 'normative_breakdown' ? 'max-h-40' : 'max-h-0' }}">

                        <a href="{{ route('dashboard') }}"
                            class="block pl-4 py-2 text-sm hover:bg-slate-700 hover:text-white font-['Bricolage_Grotesque']
                                {{ $active_page === 'normative_breakdown' ? 'bg-slate-700 text-white font-bold border-l-4 border-[#008232]' : '' }}">
                            Normative Funding Breakdown
                        </a>

                        {{-- <a href="{{ route('suc-faculty') }}"
                            class="block text-sm pl-4 py-2 text-white hover:text-white hover:bg-slate-700
                                {{ $active_page === 'suc-faculty' ? 'bg-slate-700 text-white font-bold border-l-4 border-[#008232]' : '' }}">
                            SUC Faculty
                        </a> --}}

                        {{-- <a href="{{ route('graduates') }}"
                            class="block text-sm pl-4 py-2 text-white hover:text-white hover:bg-slate-700
                                {{ $active_page === 'graduates' ? 'bg-slate-700 text-white font-bold border-l-4 border-[#008232]' : '' }}">
                            Graduates
                        </a> --}}
                    </div>
                </div>

                <!-- Research -->
                <div class="px-2">
                    <button onclick="toggleMenu('alumni-menu','alumni-arrow')"
                        class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-700 hover:text-white transition">
                        <div class="flex items-center">
                            <svg  xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                fill="currentColor" viewBox="0 0 24 24" >
                                <!--Boxicons v3.0.8 https://boxicons.com | License  https://docs.boxicons.com/free-->
                                <path d="M21 15c0-2.55-1.38-4.83-3.53-6.06l1.23-1.23a.996.996 0 0 0 0-1.41l-.5-.5 1-1-3-3-1 1-.5-.5a.996.996 0 0 0-1.41 0l-8 7.99a.996.996 0 0 0 0 1.41l4 4c.2.2.45.29.71.29s.51-.1.71-.29l5.28-5.28c1.82.79 3.01 2.56 3.01 4.57 0 2.76-2.24 5-5 5H3v2h18v-2h-2.11a6.97 6.97 0 0 0 2.11-5Zm-11-1.41L7.41 11 14 4.41 16.59 7z"></path><path d="M6.29 17.71 7 17l.71-.71-1.5-1.5-1.5-1.5L4 14l-.71.71 1.5 1.5z"></path>
                            </svg>
                            <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">Research</span>
                        </div>
                        <i id="alumni-arrow"
                            class="fa-solid fa-circle-chevron-down transition-transform duration-300"></i>
                    </button>
                    <div id="alumni-menu"
                        class="ml-8 mt-1 space-y-2 overflow-hidden transition-all duration-300 max-h-0"></div>
                </div>

                <div class="mt-3 px-4">
                    <div class="w-full border-t border-gray-300"></div>
                </div>

                <div class="mt-5 px-6">
                    <div class="flex items-center justify-center text-gray-300 hover:text-white">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span class="ml-2 text-sm font-['Bricolage_Grotesque'] font-black">About Analytica</span>
                    </div>
                </div>
            </nav>

            <!-- Bottom Profile -->
            <div class="p-4 h-20 bg-[#009539]">
                <div class="flex flex-row justify-between items-center">
                    <div class="bg-white h-12 w-12 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-user text-black text-xl"></i>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-['Bricolage_Grotesque'] font-black">Profile Name</span>
                        <span class="font-['Bricolage_Grotesque'] font-black">User Role</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M9 13h7v-2H9V7l-6 5 6 5z" />
                        <path d="M19 3h-7v2h7v14h-7v2h7c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2" />
                    </svg>
                </div>
            </div>
        </aside>

        <!-- RIGHT SIDE -->
        <div class="flex-1 flex flex-col min-w-0 md:ml-0">

            <!-- HEADER -->
            <header class="bg-[#008232] shadow-sm px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between gap-3">
                    <button
                        class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white/10 text-white"
                        onclick="openSidebar()" aria-label="Open sidebar">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div
                        class="text-lg sm:text-2xl md:text-4xl text-white font-['Bricolage_Grotesque'] font-black truncate">
                        @yield('page_title', 'CLSU Analytica')
                    </div>
                    <div class="hidden sm:block"></div>
                </div>
            </header>

            <!-- MAIN -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div>@yield('content')</div>
            </main>
        </div>
    </div>

    <script>
        function openSidebar() {
            document.getElementById("sidebar").classList.remove("-translate-x-full");
            document.getElementById("sidebarOverlay").classList.remove("hidden");
            document.body.classList.add("overflow-hidden");
        }
        function closeSidebar() {
            document.getElementById("sidebar").classList.add("-translate-x-full");
            document.getElementById("sidebarOverlay").classList.add("hidden");
            document.body.classList.remove("overflow-hidden");
        }
        function toggleMenu(menuId, arrowId) {
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(arrowId);
            if (!menu) return;
            if (menu.classList.contains("max-h-0")) {
                menu.classList.remove("max-h-0");
                menu.classList.add("max-h-40");
                if (arrow) arrow.classList.add("rotate-180");
            } else {
                menu.classList.add("max-h-0");
                menu.classList.remove("max-h-40");
                if (arrow) arrow.classList.remove("rotate-180");
            }
        }
        window.matchMedia("(min-width: 768px)").addEventListener("change", e => {
            if (e.matches) closeSidebar();
        });
    </script>
</body>

</html>
