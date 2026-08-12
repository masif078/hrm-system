<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRM System | Modern HRM for Modern Teams</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FFFFFF;
            color: #0F172A;
        }

        /* Soft Blue / Indigo Mesh Gradient Background */
        .indigo-mesh-bg {
            background-color: #FFFFFF;
            background-image: 
                radial-gradient(at 50% 0%, rgba(99, 102, 241, 0.14) 0px, transparent 60%),
                radial-gradient(at 100% 100%, rgba(238, 242, 255, 0.6) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(224, 231, 255, 0.5) 0px, transparent 50%);
        }
    </style>
</head>
<body class="antialiased selection:bg-indigo-600 selection:text-white indigo-mesh-bg min-h-screen flex flex-col justify-between">

    {{-- 1. Sticky Glassmorphism Navbar --}}
    <header class="sticky top-0 z-50 backdrop-blur-md bg-white/80 border-b border-slate-100 transition-all duration-300">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            {{-- Professional Logo --}}
            <a href="#" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-600 flex items-center justify-center text-white shadow-md shadow-indigo-500/20 group-hover:scale-105 transition-transform">
                    <i class="bi bi-person-badge-fill text-xl"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900">HRM <span class="text-indigo-600">System</span></span>
            </a>

            {{-- Nav Links --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#features" class="hover:text-indigo-600 transition-colors">Features</a>
                <a href="#solutions" class="hover:text-indigo-600 transition-colors">Solutions</a>
                <a href="#pricing" class="hover:text-indigo-600 transition-colors">Pricing</a>
            </div>

            {{-- Auth Action Buttons (100% Preserved Laravel Logic) --}}
            <div class="flex items-center gap-4">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-500/20 transition-all hover:shadow-lg">
                            Dashboard <i class="bi bi-arrow-right ml-1"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 px-4 py-2 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2.5 rounded-xl shadow-md shadow-indigo-500/25 transition-all hover:-translate-y-0.5">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>
    </header>

    <main class="flex-grow">
        {{-- 2. Hero Section --}}
        <section class="pt-20 pb-16 md:pt-28 md:pb-24 px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-4xl text-center space-y-8">
                
                {{-- Category Pill Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-semibold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-indigo-600 animate-pulse"></span>
                    Next-Generation Workforce Platform
                </div>

                {{-- Gradient Heading --}}
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black tracking-tight text-slate-900 leading-tight sm:leading-none">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-blue-600 to-indigo-800">
                        Modern HRM for Modern Teams
                    </span>
                </h1>

                {{-- Subtext --}}
                <p class="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto font-normal leading-relaxed">
                    The all-in-one HR platform to manage attendance, payroll, and employee performance with ease.
                </p>

                {{-- Action Buttons using Laravel Routes --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base px-8 py-4 rounded-xl shadow-lg shadow-indigo-500/25 w-full sm:w-auto flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                                Go to Dashboard <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-base px-8 py-4 rounded-xl shadow-lg shadow-indigo-500/25 w-full sm:w-auto flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5">
                                Get Started Free <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#demo" class="border border-slate-300 hover:border-slate-400 text-slate-700 font-semibold text-base px-8 py-4 rounded-xl bg-white/60 backdrop-blur-sm w-full sm:w-auto flex items-center justify-center gap-2 transition-all hover:bg-white">
                                <i class="bi bi-play-circle text-indigo-600"></i> Book a Demo
                            </a>
                        @endauth
                    @endif
                </div>

                {{-- Trust Notes --}}
                <div class="pt-6 flex items-center justify-center gap-8 text-xs text-slate-500 font-medium">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-indigo-600"></i> 14-Day Free Trial
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-indigo-600"></i> No Credit Card Required
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="bi bi-check-circle-fill text-indigo-600"></i> Instant Setup
                    </div>
                </div>

                {{-- 3. Visual Mockup --}}
                <div class="pt-12">
                    <div class="relative mx-auto max-w-5xl rounded-2xl bg-slate-900 p-3 sm:p-4 shadow-2xl shadow-indigo-950/20 border border-slate-800">
                        {{-- Mockup Window Header --}}
                        <div class="flex items-center justify-between pb-3 mb-3 border-b border-slate-800 px-2">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-rose-500"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                            </div>
                            <div class="text-xs font-mono text-slate-400 bg-slate-800 px-4 py-1 rounded-md">hrmsystem.com/dashboard</div>
                        </div>

                        {{-- Dashboard Content Container --}}
                        <div class="bg-slate-950 rounded-xl p-4 sm:p-6 text-left border border-slate-800 space-y-6">
                            {{-- Stats Header --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                                    <div class="text-xs text-slate-400 font-medium">Total Active Staff</div>
                                    <div class="text-2xl font-bold text-white mt-1">1,284</div>
                                    <div class="text-[11px] text-emerald-400 mt-1">↑ +12% this month</div>
                                </div>
                                <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                                    <div class="text-xs text-slate-400 font-medium">On-Time Attendance</div>
                                    <div class="text-2xl font-bold text-emerald-400 mt-1">98.6%</div>
                                    <div class="text-[11px] text-emerald-400 mt-1">Optimal Level</div>
                                </div>
                                <div class="bg-slate-900 p-4 rounded-xl border border-slate-800">
                                    <div class="text-xs text-slate-400 font-medium">Processed Payroll</div>
                                    <div class="text-2xl font-bold text-indigo-400 mt-1">$184,200</div>
                                    <div class="text-[11px] text-indigo-400 mt-1">Automated 100%</div>
                                </div>
                            </div>

                            {{-- Attendance Log Mockup --}}
                            <div class="bg-slate-900 rounded-xl p-4 border border-slate-800">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="text-sm font-bold text-white">Live Attendance & Workforce Status</h4>
                                    <span class="text-[11px] bg-indigo-500/20 text-indigo-300 font-semibold px-2.5 py-1 rounded-full border border-indigo-500/30">Real-time Sync</span>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800/80">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">JD</div>
                                            <div>
                                                <div class="text-xs font-bold text-white">John Doe</div>
                                                <div class="text-[11px] text-slate-400">Engineering Lead</div>
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-md">Checked In (08:58 AM)</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-950 border border-slate-800/80">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs">AS</div>
                                            <div>
                                                <div class="text-xs font-bold text-white">Alice Smith</div>
                                                <div class="text-[11px] text-slate-400">Product Designer</div>
                                            </div>
                                        </div>
                                        <span class="text-xs font-semibold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-md">Checked In (09:01 AM)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- 4. Features Section (3-Column Grid) --}}
        <section id="features" class="py-20 bg-slate-50/50 border-t border-slate-100">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                
                {{-- Section Header --}}
                <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Everything You Need to Manage HR Effortlessly
                    </h2>
                    <p class="text-slate-600 text-base">
                        Engineered to eliminate manual administrative workloads with smart automated tools.
                    </p>
                </div>

                {{-- 3-Column Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    {{-- Card 1: Smart Attendance --}}
                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="bi bi-clock-history text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Attendance</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Track employee check-ins, check-outs, overtime, and late arrivals automatically with real-time logs and attendance reports.
                        </p>
                    </div>

                    {{-- Card 2: Payroll --}}
                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="bi bi-cash-stack text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Payroll System</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Process monthly employee salaries with 1-click. Automatic calculation of taxes, allowances, deductions, and PDF payslips.
                        </p>
                    </div>

                    {{-- Card 3: Analytics --}}
                    <div class="bg-white rounded-2xl p-8 border border-slate-200/80 shadow-sm hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <i class="bi bi-graph-up-arrow text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Analytics & Performance</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Gain deep insights into workforce efficiency, leave trends, department allocation, and overall company performance.
                        </p>
                    </div>

                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 text-slate-400 text-sm py-16 border-t border-slate-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 pb-12 border-b border-slate-800">
                
                {{-- Column 1: Brand Info --}}
                <div class="space-y-4 md:col-span-1">
                    <a href="#" class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                        <span class="text-lg font-black text-white">HRM <span class="text-indigo-500">System</span></span>
                    </a>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        The all-in-one HR platform to manage attendance, payroll, and employee performance with ease.
                    </p>
                </div>

                {{-- Column 2: Product Links --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Product</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#features" class="hover:text-white transition-colors">Smart Attendance</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Payroll System</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Analytics</a></li>
                    </ul>
                </div>

                {{-- Column 3: Company Links --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Company</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>

                {{-- Column 4: Social Links --}}
                <div class="space-y-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-white">Social Media</h4>
                    <p class="text-xs text-slate-400">Follow HRM System for modern HR insights.</p>
                    <div class="flex items-center gap-3 text-lg pt-1">
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white hover:bg-indigo-600 transition-colors">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white hover:bg-indigo-600 transition-colors">
                            <i class="bi bi-linkedin"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-lg bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white hover:bg-indigo-600 transition-colors">
                            <i class="bi bi-github"></i>
                        </a>
                    </div>
                </div>

            </div>

            {{-- Bottom Copyright Bar --}}
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
                <p>&copy; {{ date('Y') }} HRM System. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-slate-400">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-400">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>