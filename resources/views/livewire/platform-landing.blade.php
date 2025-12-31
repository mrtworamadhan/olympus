<div
    class="min-h-screen bg-[#020617] text-slate-300 font-sans selection:bg-orange-500 selection:text-white overflow-x-hidden">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-[0.2]">
        </div>

        <div
            class="absolute top-[-10%] left-1/2 -translate-x-1/2 w-[60rem] h-[30rem] bg-orange-600 opacity-20 blur-[120px] rounded-full mix-blend-screen">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40rem] h-[40rem] bg-amber-600 opacity-10 blur-[120px] rounded-full mix-blend-screen">
        </div>
    </div>

    <nav x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5"
        :class="scrolled ? 'bg-[#020617]/80 backdrop-blur-xl border-white/10' : 'bg-transparent border-transparent'">

        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2 group cursor-pointer">
                <img src="{{ asset('images/evencapt-logo.png') }}" alt="Evencapt Logo"
                    class="h-10 w-auto drop-shadow-[0_0_15px_rgba(249,115,22,0.5)] hover:scale-110 transition-transform duration-300">
                <span
                    class="font-black text-xl tracking-tight text-white group-hover:text-orange-500 transition-colors">
                    Evencapt<span class="text-orange-500">Olympus</span>
                </span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm font-medium text-slate-400 hover:text-white transition">Features</a>
                <a href="#sports" class="text-sm font-medium text-slate-400 hover:text-white transition">Sports</a>
                <a href="#olympus" class="text-sm font-medium text-slate-400 hover:text-white transition">Olympus
                    Mode</a>
                <a href="#pricing" class="text-sm font-medium text-slate-400 hover:text-white transition">Pricing</a>
            </div>

            <div class="hidden md:flex items-center gap-4">
                <a href="/app/login" class="text-sm font-bold text-white hover:text-orange-500 transition">Login</a>
                <a href="#"
                    class="px-5 py-2.5 rounded-full text-sm font-bold bg-white text-black hover:bg-orange-500 hover:text-white transition shadow-[0_0_20px_rgba(255,255,255,0.2)] hover:shadow-[0_0_20px_rgba(249,115,22,0.4)]">
                    Get Started
                </a>
            </div>

            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>
    </nav>

    <section class="relative z-10 pt-32 pb-20 sm:pt-48 sm:pb-32 px-6 overflow-hidden">
        <div class="max-w-5xl mx-auto text-center">

            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-orange-500/30 bg-orange-500/10 text-orange-400 text-xs font-bold uppercase tracking-wider mb-8 shadow-[0_0_15px_rgba(249,115,22,0.2)] animate-fade-in-up">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                v1.0 Now Available
            </div>

            <h1
                class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tight mb-8 leading-[1.1] drop-shadow-2xl">
                Manage Sports <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-600">Like a
                    PRO.</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-400 leading-relaxed mb-12 max-w-2xl mx-auto">
                The most advanced Event Organizer platform. From automated brackets to real-time "Olympus Mode"
                statistics. Built for Futsal, Mini Soccer, and beyond.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                <a href="#"
                    class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-white bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 transition shadow-lg shadow-orange-900/20 transform hover:-translate-y-1">
                    Create Tournament
                </a>
                <a href="#olympus"
                    class="w-full sm:w-auto px-8 py-4 rounded-xl font-bold text-white border border-white/10 bg-white/5 hover:bg-white/10 hover:border-orange-500/50 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Watch Demo
                </a>
            </div>

            <div class="mt-20 relative mx-auto max-w-5xl group">

                <div
                    class="absolute inset-0 bg-orange-500/20 blur-[100px] -z-10 group-hover:bg-orange-500/30 transition duration-1000">
                </div>

                <div class="rounded-xl bg-[#0f172a] border border-white/10 p-2 shadow-2xl transition-transform duration-700 ease-out hover:scale-[1.02]"
                    style="transform: perspective(1000px) rotateX(5deg);">

                    <div class="rounded-lg overflow-hidden bg-[#020617] relative border border-white/5">

                        <img src="{{ asset('images/dashboard-preview.png') }}" alt="Evencapt Dashboard Interface"
                            class="w-full h-auto object-cover opacity-90 group-hover:opacity-100 transition duration-500">

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#020617] via-transparent to-transparent opacity-20">
                        </div>

                        <div
                            class="absolute top-6 right-6 p-2 bg-[#0f172a]/90 backdrop-blur-md rounded-lg border border-white/10 shadow-lg animate-pulse hidden sm:block">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <span class="text-[10px] text-white font-bold uppercase tracking-widest">Live
                                    Updates</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div
                    class="absolute -bottom-6 -left-6 sm:bottom-10 sm:-left-10 p-4 bg-[#1e293b] rounded-xl border border-white/10 shadow-2xl animate-bounce duration-[3000ms] hidden sm:block z-20">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-full bg-orange-500/20 flex items-center justify-center text-orange-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400">System Speed</div>
                            <div class="text-lg font-black text-white">Real-time</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="sports" class="py-24 border-t border-white/5 bg-[#020617]/50 relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4">Supported Sports</h2>
                <p class="text-slate-400">Our system is optimized for high-intensity competitive sports.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div
                    class="group p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-orange-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition text-orange-500">
                        ⚽
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Futsal</h3>
                    <p class="text-sm text-slate-500">Real-time fouls, periods, and accumulations.</p>
                </div>
                <div
                    class="group p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-orange-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition text-blue-500">
                        👟
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Mini Soccer</h3>
                    <p class="text-sm text-slate-500">Substitution management and line-up cards.</p>
                </div>
                <div
                    class="group p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-orange-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition text-amber-500">
                        🏀
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Basketball</h3>
                    <p class="text-sm text-slate-500">Quarter timing, team fouls, and timeouts.</p>
                </div>
                <div
                    class="group p-6 rounded-2xl bg-[#0f172a] border border-white/5 hover:border-orange-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div
                        class="w-12 h-12 rounded-xl bg-green-500/10 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition text-green-500">
                        🏸
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Badminton</h3>
                    <p class="text-sm text-slate-500">Set scoring, rally points, and rubber sets.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="olympus" class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-orange-900/10 to-transparent pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div
                class="bg-[#0f172a] border border-orange-500/30 rounded-3xl p-8 md:p-16 overflow-hidden relative shadow-2xl">

                <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-500 opacity-20 blur-[80px] rounded-full">
                </div>

                <div class="flex flex-col md:flex-row items-center gap-12">
                    <div class="flex-1 text-left">
                        <div
                            class="inline-block px-3 py-1 bg-orange-500 text-black font-black text-xs uppercase tracking-widest rounded mb-6">
                            Exclusive Feature
                        </div>
                        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Olympus Mode ⚡</h2>
                        <p class="text-lg text-slate-400 mb-8 leading-relaxed">
                            Turn your device into a professional scoreboard controller. Real-time synchronization
                            between the Official's device and the Public Display. No delay, purely native speed.
                        </p>
                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Live Timer & Period Control</span>
                            </li>
                            <li class="flex items-center gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Auto-accumulated Team Fouls</span>
                            </li>
                            <li class="flex items-center gap-3 text-slate-300">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Instant Stat Updates to Public Page</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex-1 w-full relative">
                        <div
                            class="relative mx-auto border-gray-800 bg-gray-800 border-[14px] rounded-[2.5rem] h-[600px] w-[300px] shadow-xl">
                            <div class="h-[32px] w-[3px] bg-gray-800 absolute -left-[17px] top-[72px] rounded-l-lg">
                            </div>
                            <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[124px] rounded-l-lg">
                            </div>
                            <div class="h-[46px] w-[3px] bg-gray-800 absolute -left-[17px] top-[178px] rounded-l-lg">
                            </div>
                            <div class="h-[64px] w-[3px] bg-gray-800 absolute -right-[17px] top-[142px] rounded-r-lg">
                            </div>
                            <div class="rounded-[2rem] overflow-hidden w-[272px] h-[572px] bg-[#020617] relative">
                                <div class="absolute inset-0 flex flex-col items-center justify-center p-4">
                                    <div class="text-orange-500 font-mono text-4xl font-bold mb-4 animate-pulse">12:04
                                    </div>
                                    <div class="w-full bg-slate-800 p-4 rounded-xl mb-4 border border-slate-700">
                                        <div class="flex justify-between text-white font-bold text-2xl">
                                            <span>HOME</span>
                                            <span>3 - 1</span>
                                            <span>AWAY</span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 w-full">
                                        <div
                                            class="bg-green-600/20 border border-green-500/50 text-green-500 p-2 rounded text-center text-xs font-bold">
                                            + GOAL</div>
                                        <div
                                            class="bg-red-600/20 border border-red-500/50 text-red-500 p-2 rounded text-center text-xs font-bold">
                                            + CARD</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4">Core Capabilities</h2>
                <p class="text-slate-400">Everything you need to run a world-class tournament.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[250px]">
                <div
                    class="md:col-span-2 bg-[#0f172a] rounded-2xl p-8 border border-white/5 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-40 transition transform group-hover:scale-110">
                        <svg class="w-32 h-32 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Automated Standings</h3>
                    <p class="text-slate-400 max-w-sm">Points, Goal Difference, Head-to-head. Everything is calculated
                        instantly after every match update.</p>
                </div>

                <div class="bg-[#0f172a] rounded-2xl p-8 border border-white/5 relative overflow-hidden group">
                    <div class="absolute bottom-0 right-0 p-4 opacity-20 group-hover:opacity-40 transition">
                        <svg class="w-20 h-20 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Team Portal</h3>
                    <p class="text-slate-400 text-sm">Teams can register players and upload documents independently.</p>
                </div>

                <div class="bg-[#0f172a] rounded-2xl p-8 border border-white/5 relative overflow-hidden group">
                    <div class="absolute bottom-0 right-0 p-4 opacity-20 group-hover:opacity-40 transition">
                        <svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Digital Match Report</h3>
                    <p class="text-slate-400 text-sm">Generate official PDF reports with one click.</p>
                </div>

                <div
                    class="md:col-span-2 bg-[#0f172a] rounded-2xl p-8 border border-white/5 relative overflow-hidden group">
                    <div
                        class="absolute top-0 right-0 p-8 opacity-20 group-hover:opacity-40 transition transform group-hover:rotate-12">
                        <svg class="w-32 h-32 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Multi-Tenant System</h3>
                    <p class="text-slate-400 max-w-sm">One platform, unlimited Event Organizers. Each with their own
                        branding, colors, and data isolation.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 border-t border-white/5 bg-[#020617]">
        <div class="max-w-7xl mx-auto px-6">
            <p class="text-center text-sm font-bold text-slate-500 uppercase tracking-widest mb-10">Trusted by Top
                Organizers</p>
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20 opacity-50">
                <span class="text-2xl font-black text-white hover:text-orange-500 transition cursor-pointer">GARUDA
                    SPORTS</span>
                <span class="text-2xl font-black text-white hover:text-orange-500 transition cursor-pointer">LIGA
                    MAHASISWA</span>
                <span
                    class="text-2xl font-black text-white hover:text-orange-500 transition cursor-pointer">FUTSAL.ID</span>
                <span
                    class="text-2xl font-black text-white hover:text-orange-500 transition cursor-pointer">CHAMPION</span>
            </div>
        </div>
    </section>

    <section class="py-24 border-t border-white/5 bg-[#0f172a]/30">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-black text-white tracking-tight mb-4">What They Say</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="bg-[#0f172a] p-8 rounded-2xl border border-white/5 relative">
                    <div class="text-orange-500 text-4xl font-serif absolute top-4 left-4">"</div>
                    <p class="text-slate-300 mb-6 relative z-10 italic">"Olympus Mode is a game changer. Our referees
                        love how easy it is to update the score, and the crowd loves the instant updates."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-700 rounded-full"></div>
                        <div>
                            <div class="text-white font-bold text-sm">Andi Saputra</div>
                            <div class="text-slate-500 text-xs">EO Garuda Cup</div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#0f172a] p-8 rounded-2xl border border-white/5 relative">
                    <div class="text-orange-500 text-4xl font-serif absolute top-4 left-4">"</div>
                    <p class="text-slate-300 mb-6 relative z-10 italic">"Finally, a system that handles Futsal rules
                        correctly. The accumulated fouls feature saved us so much headache."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-700 rounded-full"></div>
                        <div>
                            <div class="text-white font-bold text-sm">Budi Santoso</div>
                            <div class="text-slate-500 text-xs">Liga Pelajar</div>
                        </div>
                    </div>
                </div>

                <div class="bg-[#0f172a] p-8 rounded-2xl border border-white/5 relative">
                    <div class="text-orange-500 text-4xl font-serif absolute top-4 left-4">"</div>
                    <p class="text-slate-300 mb-6 relative z-10 italic">"The automated standings and bracket generation
                        cut our administrative work by 80%."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-700 rounded-full"></div>
                        <div>
                            <div class="text-white font-bold text-sm">Citra Lestari</div>
                            <div class="text-slate-500 text-xs">Event Manager</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($activeEvents->count() > 0)
        <section id="events" class="py-24 border-t border-white/5 bg-[#020617] relative z-10">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex justify-between items-end mb-12">
                    <div>
                        <h2 class="text-3xl font-black text-white tracking-tight mb-2">Happening Now</h2>
                        <p class="text-slate-400">Watch matches from tournaments currently running.</p>
                    </div>
                    <a href="#" class="text-sm font-bold text-orange-500 hover:text-white transition">View All &rarr;</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($activeEvents as $evt)
                        <a href="{{ route('public.landing', [$evt->tenant->slug, $evt->slug]) }}" wire:navigate
                            class="group bg-[#0f172a] rounded-2xl border border-white/5 overflow-hidden hover:border-orange-500/50 transition-all hover:-translate-y-1">

                            <div class="h-32 bg-slate-800 relative">
                                @if($evt->banner_image)
                                    <img src="{{ Storage::url($evt->banner_image) }}"
                                        class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-800 to-slate-900">
                                        <span class="text-4xl">🏆</span>
                                    </div>
                                @endif
                                <div
                                    class="absolute top-2 right-2 px-2 py-0.5 bg-red-600 text-white text-[10px] font-bold uppercase rounded animate-pulse">
                                    Live Action
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-3">
                                    @if($evt->tenant->logo)
                                        <img src="{{ Storage::url($evt->tenant->logo) }}" class="w-5 h-5 rounded-full">
                                    @endif
                                    <span
                                        class="text-xs text-slate-400 font-bold uppercase tracking-wider">{{ $evt->tenant->name }}</span>
                                </div>
                                <h3
                                    class="text-lg font-bold text-white leading-tight mb-2 group-hover:text-orange-500 transition-colors line-clamp-2">
                                    {{ $evt->name }}
                                </h3>
                                <p class="text-xs text-slate-500 mb-4">
                                    {{ $evt->start_date->format('d M') }} - {{ $evt->end_date->format('d M Y') }}
                                </p>
                                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                                    <span class="text-xs text-slate-400">View Details</span>
                                    <svg class="w-4 h-4 text-orange-500 transform group-hover:translate-x-1 transition"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section id="pricing" class="py-24 border-t border-white/5 relative z-10 bg-[#020617]">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4">Transparent Pricing</h2>
                <p class="text-slate-400">Invest in the best technology for your sports event.</p>
            </div>

            <div class="flex justify-center mb-12">
                <div class="bg-[#0f172a] p-1 rounded-full border border-white/10 inline-flex relative">
                    <div class="px-6 py-2 rounded-full bg-white/10 text-white text-sm font-bold">Per Event</div>
                    <div class="px-6 py-2 rounded-full text-slate-500 text-sm font-bold">Subscription</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">

                <div
                    class="bg-[#0f172a] rounded-3xl p-8 border border-white/5 hover:border-white/20 transition group relative">
                    <h3 class="text-lg font-bold text-white mb-2">Community</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-black text-white">Rp 0</span>
                        <span class="text-slate-500 text-sm font-medium">/ event</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-8">Perfect for small friendly matches or trial tournaments.</p>

                    <ul class="space-y-4 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Max 8 Teams
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Basic Schedule & Scores
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Public Match Page
                        </li>
                        <li class="flex items-center gap-3 text-slate-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            No Olympus Mode (Live)
                        </li>
                    </ul>

                    <a href="/app/register"
                        class="block w-full py-3 rounded-xl border border-white/10 bg-white/5 text-center text-white font-bold hover:bg-white/10 transition">
                        Start Free
                    </a>
                </div>

                <div
                    class="bg-[#0f172a] rounded-3xl p-8 border border-orange-500/50 shadow-[0_0_30px_rgba(249,115,22,0.1)] relative transform md:-translate-y-4">
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-orange-500 text-white px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest shadow-lg">
                        Most Popular
                    </div>

                    <h3 class="text-lg font-bold text-white mb-2">Champion</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-black text-white">Contact Us</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-8">For serious organizers who need professional standards.</p>

                    <ul class="space-y-4 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-bold text-white">Unlimited Teams</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="font-bold text-white">Olympus Mode (Live Score)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Automated Standings & Stats
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Player Registration Form
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            PDF Match Reports
                        </li>
                    </ul>

                    <a href="https://wa.me/6281277761133?text=Halo%20Evencapt,%20saya%20tertarik%20paket%20Champion"
                        target="_blank"
                        class="block w-full py-3 rounded-xl bg-orange-600 text-center text-white font-bold hover:bg-orange-500 transition shadow-lg shadow-orange-900/20">
                        Choose Champion
                    </a>
                </div>

                <div
                    class="bg-[#0f172a] rounded-3xl p-8 border border-white/5 hover:border-white/20 transition group relative">
                    <h3 class="text-lg font-bold text-white mb-2">League / Enterprise</h3>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-4xl font-black text-white">Contact Us</span>
                    </div>
                    <p class="text-slate-400 text-sm mb-8">Full-season management for leagues with multiple divisions.
                    </p>

                    <ul class="space-y-4 mb-8 text-sm text-slate-300">
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Everything in Champion
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Custom Domain (yourname.com)
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Dedicated Server Priority
                        </li>
                        <li class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            On-site Technical Support
                        </li>
                    </ul>

                    <a href="https://wa.me/6281277761133?text=Halo%20Evencapt,%20saya%20tertarik%20paket%20Enterprise"
                        target="_blank"
                        class="block w-full py-3 rounded-xl border border-white/10 bg-white/5 text-center text-white font-bold hover:bg-white/10 transition">
                        Contact Sales
                    </a>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-orange-900/20 to-[#020617] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">Ready to Elevate Your Tournament?</h2>
            <p class="text-lg text-slate-400 mb-10 max-w-2xl mx-auto">
                Join thousands of organizers who trust Evencapt Olympus. Have questions or need a custom demo? We are
                just one chat away.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="https://wa.me/6281277761133?text=Halo%20Admin%20Evencapt,%20saya%20mau%20tanya%20tentang%20sistem..."
                    target="_blank"
                    class="group flex items-center gap-3 px-8 py-4 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold shadow-lg shadow-green-900/20 transition transform hover:-translate-y-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    Chat on WhatsApp
                </a>

                <a href="#"
                    class="px-8 py-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white font-bold transition">
                    View Documentation
                </a>
            </div>

            <p class="mt-8 text-sm text-slate-500">
                Support available Mon - Sat, 09:00 - 17:00 WIB
            </p>
        </div>
    </section>

    <footer class="py-12 border-t border-white/10 bg-[#020617] text-center">
        <div class="flex items-center justify-center gap-2 mb-6">
            <img src="{{ asset('images/evencapt-logo.png') }}" alt="Evencapt Logo"
                class="h-10 w-auto drop-shadow-[0_0_15px_rgba(249,115,22,0.5)] hover:scale-110 transition-transform duration-300">
            <span class="font-black text-white tracking-tight">Evencapt <span class="text-orange-500">Olympus</span></span>
        </div>
        <div class="flex justify-center gap-6 text-sm text-slate-500 font-medium mb-8">
            <a href="#" class="hover:text-orange-500 transition">Privacy</a>
            <a href="#" class="hover:text-orange-500 transition">Terms</a>
            <a href="#" class="hover:text-orange-500 transition">Contact</a>
            <a href="#" class="hover:text-orange-500 transition">Twitter</a>
        </div>
        <p class="text-xs text-slate-600">
            &copy; {{ date('Y') }} <a href="https://evencapt.com/" class="text-slate-400 hover:text-white">Evencapt Platform</a>. All rights reserved powered by <a href="https://salakatech.com/" class="text-slate-400 hover:text-white">SalakaTech</a>.
        </p>
    </footer>

</div>