@php
    $themeColor = $event->primary_color ?? $event->tenant->primary_color ?? '#fbbf24';
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans selection:bg-[rgb(var(--theme-rgb))] selection:text-black overflow-x-hidden"
    style="--theme-rgb: {{ $themeRgb }};">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div
            class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-[0.2]">
        </div>
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-[50rem] h-[30rem] bg-[rgb(var(--theme-rgb))] opacity-20 blur-[120px] rounded-full mix-blend-screen">
        </div>
        <div
            class="absolute bottom-0 right-0 w-[40rem] h-[20rem] bg-blue-900/20 opacity-20 blur-[100px] rounded-full mix-blend-screen">
        </div>
    </div>

    <nav x-data="{ scrolled: false, mobileOpen: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        class="fixed top-0 w-full z-50 transition-all duration-300 border-b border-white/5"
        :class="scrolled ? 'bg-[#020617]/80 backdrop-blur-xl border-white/10' : 'bg-transparent border-transparent'">

        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($event->tenant->logo)
                    <img src="{{ Storage::url($event->tenant->logo) }}" class="h-9 w-auto">
                @endif
                <span class="font-bold text-lg tracking-tight text-white font-display">{{ $event->tenant->name }}</span>
            </div>

            <div class="hidden md:flex items-center gap-8">
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false"
                        class="text-sm font-medium text-slate-400 hover:text-[rgb(var(--theme-rgb))] transition-colors flex items-center gap-1 group">
                        Login
                        <svg class="w-3 h-3 text-slate-500 group-hover:text-[rgb(var(--theme-rgb))]" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class="absolute right-0 mt-4 w-48 bg-[#0f172a] border border-white/10 rounded-xl shadow-2xl py-2 z-50 ring-1 ring-black/50">
                        <a href="/app/login"
                            class="block px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-[rgb(var(--theme-rgb))] transition">Administrator</a>
                        <a href="/team/login"
                            class="block px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-[rgb(var(--theme-rgb))] transition">Team
                            Official</a>
                        <a href="/official/login"
                            class="block px-4 py-2 text-sm text-slate-300 hover:bg-white/5 hover:text-[rgb(var(--theme-rgb))] transition">Match
                            Official</a>
                    </div>
                </div>
                @if($event->is_registration_open)
                    <a href="/team/register"
                        class="relative group px-5 py-2 rounded-full text-sm font-bold text-black overflow-hidden"
                        style="background-color: rgb(var(--theme-rgb));">
                        <span class="relative z-10">Register Team</span>
                        <div
                            class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        </div>
                    </a>
                @endif
            </div>
            <button @click="mobileOpen = !mobileOpen" class="md:hidden text-slate-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
        </div>
        <div x-show="mobileOpen" x-collapse class="md:hidden bg-[#0f172a] border-b border-white/10">
            <div class="px-6 py-4 space-y-4">
                <a href="/team/login" class="block text-slate-300 hover:text-[rgb(var(--theme-rgb))]">Login Team</a>
                <a href="/official/login" class="block text-slate-300 hover:text-[rgb(var(--theme-rgb))]">Login
                    Official</a>
            </div>
        </div>
    </nav>

    <div class="relative z-10 pt-32 pb-20 sm:pt-48 sm:pb-32 px-6">
        <div class="max-w-5xl mx-auto text-center">
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-[rgb(var(--theme-rgb))]/30 bg-[rgb(var(--theme-rgb))]/5 text-[rgb(var(--theme-rgb))] text-xs font-bold uppercase tracking-wider mb-8 shadow-[0_0_15px_rgba(var(--theme-rgb),0.3)] animate-fade-in-up">
                <span class="w-1.5 h-1.5 rounded-full bg-[rgb(var(--theme-rgb))] animate-pulse"></span>
                Official Tournament
            </div>
            <h1
                class="text-5xl md:text-7xl lg:text-8xl font-black text-white tracking-tight mb-8 leading-[1.1] drop-shadow-2xl">
                {{ $event->name }}
            </h1>
            <div class="prose prose-lg prose-invert mx-auto text-slate-400 leading-relaxed mb-12 max-w-2xl">
                {!! $event->public_description ?? 'Experience the ultimate competition platform. Real-time schedules, match statistics, and standings powered by modern technology.' !!}
            </div>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-5">
                @if($event->is_registration_open)
                    <a href="/team/register"
                        class="w-full sm:w-auto px-8 py-3.5 rounded-lg font-bold text-[#020617] transition-all transform hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(var(--theme-rgb),0.4)]"
                        style="background-color: rgb(var(--theme-rgb));">Join Competition</a>
                @endif
                <a href="#categories"
                    class="w-full sm:w-auto px-8 py-3.5 rounded-lg font-bold text-white border border-white/10 bg-white/5 hover:bg-white/10 hover:border-[rgb(var(--theme-rgb))]/50 transition-all">View
                    Categories</a>
            </div>
        </div>
    </div>

    <div id="categories" class="relative z-10 py-24 border-t border-white/5 bg-[#020617]/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="mb-12 border-b border-white/10 pb-6 flex justify-between items-end">
                <div>
                    <h2 class="text-3xl font-bold text-white tracking-tight">Available Categories</h2>
                    <p class="text-slate-500 mt-2 text-lg">Select a category to view detailed stats and schedules.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($categories as $category)
                    <a href="{{ route('public.category', [$tenant, $event, $category]) }}" wire:navigate
                        class="group relative h-full bg-[#0f172a] border border-white/10 rounded-2xl p-8 hover:border-[rgb(var(--theme-rgb))]/50 transition-all duration-300 overflow-hidden">
                        <div
                            class="absolute inset-0 bg-gradient-to-tr from-[rgb(var(--theme-rgb))]/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                        </div>
                        <div class="relative z-10 flex justify-between items-start mb-6">
                            <div
                                class="p-3 rounded-xl bg-white/5 border border-white/10 group-hover:border-[rgb(var(--theme-rgb))]/30 group-hover:bg-[rgb(var(--theme-rgb))]/10 transition-colors">
                                <svg class="w-8 h-8 text-slate-400 group-hover:text-[rgb(var(--theme-rgb))]"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 21h8M12 17v4M6 4h12v3a6 6 0 01-6 6 6 6 0 01-6-6V4z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 7H4a2 2 0 00-2 2 5 5 0 005 5M18 7h2a2 2 0 012 2 5 5 0 01-5 5"/>
                                </svg>

                            </div>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-white/10 text-slate-500 group-hover:text-white transition">{{ $category->gender == 'male' ? 'Male' : 'Male' }}</span>
                        </div>
                        <div class="relative z-10 mb-8">
                            <h3
                                class="text-2xl font-bold text-white mb-2 group-hover:text-[rgb(var(--theme-rgb))] transition-colors">
                                {{ $category->name }}</h3>
                            <div class="flex items-center gap-2 text-sm text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Age Limit: {{ $category->age_limit ?? 'Open' }}
                            </div>
                        </div>
                        <div class="relative z-10 flex items-center gap-6 pt-6 border-t border-white/5">
                            <div class="flex flex-col"><span
                                    class="text-xl font-bold text-white">{{ $category->competitors_count }}</span><span
                                    class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Teams</span></div>
                            <div class="w-px h-8 bg-white/10"></div>
                            <div class="flex flex-col"><span
                                    class="text-xl font-bold text-white">{{ $category->matches_count }}</span><span
                                    class="text-[10px] uppercase font-bold text-slate-500 tracking-wider">Matches</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if($event->galleries->count() > 0)
    <section id="gallery" class="relative z-10 py-24 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4">Event Gallery</h2>
                <p class="text-slate-400">Highlight moments from the competition.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
                @foreach($event->galleries as $index => $gallery)
                    {{-- Logic layout bento sederhana: Item pertama besar, sisanya biasa --}}
                    <div class="{{ $index === 0 ? 'col-span-2 row-span-2' : '' }} relative group overflow-hidden rounded-2xl border border-white/10 bg-[#0f172a]">
                        <img src="{{ Storage::url($gallery->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        
                        @if($gallery->caption)
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition duration-300 flex items-end p-4">
                                <span class="text-white text-sm font-medium">{{ $gallery->caption }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($competitors->count() > 0)
    <section id="participants" class="relative z-10 py-24 border-t border-white/5 bg-[#0f172a]">
        <div class="max-w-7xl mx-auto px-6">
            
            <div class="text-center mb-16">
                <span class="inline-block py-1 px-3 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-4">
                    Who is joining?
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-4">
                    {{ $competitors->count() }} Tim Telah Bergabung
                </h2>
                <p class="text-slate-400 max-w-2xl mx-auto">
                    Siap berkompetisi menjadi juara. Apakah tim Anda berikutnya?
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($competitors as $team)
                    <button wire:click="openTeamModal({{ $team->id }})" 
                            class="group relative bg-[#1e293b] rounded-2xl p-6 border border-white/5 hover:border-cyan-500/50 transition duration-300 hover:-translate-y-1 flex flex-col items-center text-center w-full focus:outline-none">
                        
                        <div class="absolute top-3 left-3 z-20">
                            <div onclick="event.stopPropagation(); shareTeam('{{ $team->name }}', '{{ route('public.landing', [$event->tenant->slug, $event->slug]) }}?team={{ $team->id }}')" 
                                class="w-8 h-8 rounded-full bg-white/5 hover:bg-cyan-500 hover:text-white text-slate-400 flex items-center justify-center transition cursor-pointer border border-white/5"
                                title="Bagikan Tim Ini">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            </div>
                        </div>

                        <div class="absolute inset-0 bg-black/60 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 z-10 backdrop-blur-[2px]">
                            <span class="text-xs font-bold text-white bg-cyan-600 px-3 py-1.5 rounded-full border border-cyan-400">
                                Lihat Pemain
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white/5 text-slate-400 border border-white/5">
                                {{ $team->category->name ?? 'Open' }}
                            </span>
                        </div>

                        <div class="w-20 h-20 rounded-full bg-[#020617] border-2 border-white/10 group-hover:border-cyan-500/50 shadow-lg flex items-center justify-center overflow-hidden mb-4 transition duration-300">
                            @if($team->logo)
                                <img src="{{ Storage::url($team->logo) }}" alt="{{ $team->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-3xl grayscale group-hover:grayscale-0 transition">🛡️</span>
                            @endif
                        </div>

                        <h3 class="text-white font-bold text-sm leading-tight group-hover:text-cyan-400 transition mb-1">
                            {{ $team->name }}
                        </h3>
                    </button>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <a href="#register" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-white/5 hover:bg-white/10 text-white font-bold transition border border-white/10">
                    <span>Lihat Semua Peserta</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>

        </div>
    </section>
    @endif

    @if($event->sponsors->count() > 0)
    <section id="sponsors" class="relative z-10 py-20 border-t border-white/5 bg-[#020617]">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-10">Trusted & Supported By</p>
            
            <div class="flex flex-wrap justify-center items-center gap-12 md:gap-20">
                @foreach($event->sponsors as $sponsor)
                    @php
                        $url = $sponsor->website_url;
                        if ($url && !Illuminate\Support\Str::startsWith($url, ['http://', 'https://'])) {
                            $url = 'https://' . $url;
                        }
                    @endphp

                    <a href="{{ $url ?? '#' }}" target="{{ $url ? '_blank' : '_self' }}" 
                       class="group cursor-pointer transition duration-300 relative block">
                        
                        <div class="bg-white p-3 rounded-2xl shadow-lg border border-white/10 opacity-70 group-hover:opacity-100 group-hover:scale-110 transition duration-300 overflow-hidden">
                             <img src="{{ Storage::url($sponsor->logo_path) }}" 
                                 alt="{{ $sponsor->name }}" 
                                 class="h-12 w-auto object-contain mx-auto grayscale group-hover:grayscale-0 transition duration-300">
                        </div>
                        
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <footer class="py-12 border-t border-white/10 bg-[#0f172a] text-center relative z-10">
        <div class="flex justify-center items-center gap-2 mb-4">
            @if($event->tenant->logo)
                <img src="{{ Storage::url($event->tenant->logo) }}" class="h-6 w-auto grayscale opacity-50">
            @endif
            <span class="font-bold text-slate-500 tracking-tight">{{ $event->tenant->name }}</span>
        </div>
        <p class="text-sm text-slate-600">
            &copy; {{ date('Y') }} All rights reserved. Powered by {{ config('app.name') }}.
        </p>
    </footer>
    @if($showTeamModal && $selectedTeam)
        <div class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto">
            
            <div class="bg-[#0f172a] w-full max-w-2xl rounded-3xl border border-white/10 shadow-2xl relative animate-fade-in-up overflow-hidden">
                
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/10 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="relative z-10 p-8 border-b border-white/5 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
                    <div class="w-24 h-24 rounded-full bg-[#1e293b] border-4 border-cyan-500/50 shadow-[0_0_20px_rgba(6,182,212,0.2)] flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if($selectedTeam->logo)
                            <img src="{{ Storage::url($selectedTeam->logo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">🛡️</span>
                        @endif
                    </div>

                    <div class="flex-1">
                        <p class="text-cyan-400 font-bold text-xs uppercase tracking-widest mb-2">Official Squad List</p>
                        <h2 class="text-3xl font-black text-white leading-tight mb-1">{{ $selectedTeam->name }}</h2>
                        <span class="inline-block px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-slate-300 text-xs font-bold">
                            Kategori: {{ $selectedTeam->category->name }}
                        </span>
                        <button onclick="shareTeam('{{ $selectedTeam->name }}', '{{ route('public.landing', [$event->tenant->slug, $event->slug]) }}?team={{ $selectedTeam->id }}')" 
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-green-600/20 hover:bg-green-600 border border-green-500/30 text-green-400 hover:text-white text-xs font-bold transition duration-300">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                            Share Squad
                        </button>
                    </div>

                    <button wire:click="closeTeamModal" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-white transition bg-white/5 rounded-full hover:bg-red-500/20 hover:text-red-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-[#020617]/50">
                    
                    @if($selectedTeam->players->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($selectedTeam->players as $player)
                                <div class="flex items-center gap-4 p-3 rounded-xl bg-[#1e293b] border border-white/5 hover:border-cyan-500/30 transition group relative overflow-hidden">
                                    
                                    <div class="w-12 h-12 rounded-lg bg-cyan-600/10 border border-cyan-500/20 flex items-center justify-center group-hover:bg-cyan-600 group-hover:text-white transition duration-300 flex-shrink-0">
                                        <span class="font-mono font-black text-xl text-cyan-400 group-hover:text-white">
                                            {{ $player->number ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-white font-bold text-sm group-hover:text-cyan-400 transition truncate">
                                            {{ $player->name }}
                                        </h4>
                                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider">
                                            {{ $player->position ?? 'Player' }}
                                        </p>
                                    </div>
                                    
                                    <div class="w-12 h-12 rounded-full border-2 border-white/10 group-hover:border-cyan-400 transition duration-300 flex-shrink-0 overflow-hidden bg-black/20">
                                        @if($player->photo)
                                            <img src="{{ Storage::url($player->photo) }}" 
                                                 alt="{{ $player->name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-[#0f172a] text-slate-500 font-bold text-xs">
                                                {{ substr($player->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="inline-block p-4 rounded-full bg-white/5 mb-4">
                                <svg class="w-8 h-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <p class="text-slate-400 text-sm">Data pemain belum diinput oleh Manager Tim.</p>
                        </div>
                    @endif

                </div>

                <div class="p-6 border-t border-white/5 bg-[#1e293b] flex justify-between items-center text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                    <span>{{ $selectedTeam->players->count() }} Registered Players</span>
                    <span>{{ $event->name }} Official Data</span>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    async function shareTeam(teamName, url) {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: 'Official Squad: ' + teamName,
                    text: 'Lihat skuad resmi tim ' + teamName + ' di sini!',
                    url: url
                });
                return;
            } catch (err) {
                console.log('User membatalkan share atau error native share.');
            }
        }

        try {
            if (navigator.clipboard && window.isSecureContext) {
                await navigator.clipboard.writeText(url);
                alert('Link berhasil disalin! Silakan paste di WhatsApp.');
            } else {
                const textArea = document.createElement("textarea");
                textArea.value = url;
                
                textArea.style.position = "fixed";
                textArea.style.left = "-9999px";
                textArea.style.top = "0";
                
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                const successful = document.execCommand('copy');
                document.body.removeChild(textArea);
                
                if (successful) {
                    alert('Link berhasil disalin! Silakan paste di WhatsApp.');
                } else {
                    prompt('Browser memblokir copy otomatis. Silakan salin link di bawah ini:', url);
                }
            }
        } catch (err) {
            console.error('Gagal menyalin:', err);
            prompt('Silakan salin link di bawah ini secara manual:', url);
        }
    }
</script>