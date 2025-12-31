@php
    $themeColor = $category->event->tenant->primary_color ?? '#fbbf24'; 
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans p-6 md:p-8 relative selection:bg-[rgb(var(--theme-rgb))] selection:text-black"
     style="--theme-rgb: {{ $themeRgb }};"
     x-data="{ 
        showAnimation: false,
        shufflingName: 'READY...',
        shufflingLogo: '',
        teams: {{ \App\Models\Competitor::where('category_id', $category->id)->where('status','approved')->get()->map(fn($t) => ['name' => $t->name, 'logo' => $t->logo ? Storage::url($t->logo) : null]) }},
        
        startAnimation() {
            if(this.teams.length === 0) return;
            
            this.showAnimation = true;
            let counter = 0;
            let speed = 50; 
            let maxTime = 3000; // 3 Detik cukup

            let interval = setInterval(() => {
                let randomTeam = this.teams[Math.floor(Math.random() * this.teams.length)];
                this.shufflingName = randomTeam.name;
                this.shufflingLogo = randomTeam.logo;
                counter += speed;
            }, speed);

            setTimeout(() => {
                clearInterval(interval);
                this.shufflingName = 'DONE!';
                $wire.autoDraw().then(() => {
                    setTimeout(() => { this.showAnimation = false; }, 1000);
                });
            }, maxTime);
        }
    }">
    
    <div x-show="showAnimation" style="display: none;"
        class="fixed inset-0 z-50 bg-[#020617]/95 flex flex-col items-center justify-center text-white backdrop-blur-md">
        <div class="text-2xl font-bold text-[rgb(var(--theme-rgb))] mb-8 uppercase tracking-[0.5em] animate-pulse">
            SISTEM MENGACAK...
        </div>
        <div class="w-80 h-80 bg-[#0f172a] rounded-3xl border-4 border-[rgb(var(--theme-rgb))]/50 shadow-[0_0_100px_rgba(var(--theme-rgb),0.3)] flex flex-col items-center justify-center p-6 relative overflow-hidden">
            <div class="w-40 h-40 bg-white/10 rounded-full flex items-center justify-center mb-6 shadow-lg relative z-10">
                <template x-if="shufflingLogo">
                    <img :src="shufflingLogo" class="w-full h-full object-cover rounded-full">
                </template>
                <template x-if="!shufflingLogo">
                    <span class="text-4xl grayscale">🛡️</span>
                </template>
            </div>
            <h2 class="text-2xl font-black text-center text-white relative z-10" x-text="shufflingName"></h2>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-[#0f172a] p-6 rounded-2xl shadow-lg border border-white/5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-[rgb(var(--theme-rgb))] opacity-5 blur-[80px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 mb-4 md:mb-0">
            <a href="/admin/events" class="text-xs font-bold text-slate-500 hover:text-white mb-2 block transition">&larr; KEMBALI KE DASHBOARD</a>
            <h1 class="text-3xl font-black text-white uppercase tracking-tight">Drawing Room</h1>
            <div class="flex items-center gap-2 mt-2">
                <span class="text-slate-400 text-sm">Kategori:</span>
                <span class="font-bold text-[rgb(var(--theme-rgb))] text-sm px-3 py-1 rounded-full bg-[rgb(var(--theme-rgb))]/10 border border-[rgb(var(--theme-rgb))]/20">
                    {{ $category->name }}
                </span>
                <span class="text-xs font-bold text-slate-500 border border-white/10 px-2 py-1 rounded uppercase">
                    {{ $category->format_type == 'knockout' ? 'Sistem Gugur' : 'Fase Grup' }}
                </span>
            </div>
        </div>
        
        <div class="flex gap-3 relative z-10">
             <button wire:click="resetDrawing" 
                onclick="confirm('Reset semua hasil drawing?') || event.stopImmediatePropagation()"
                class="px-4 py-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-red-400 rounded-xl font-bold border border-white/10 transition text-sm">
                ↺ Reset
            </button>
            
            <button @click="startAnimation()" 
                class="px-6 py-3 bg-[rgb(var(--theme-rgb))] hover:bg-[rgb(var(--theme-rgb))]/80 text-black rounded-xl font-black shadow-[0_0_20px_rgba(var(--theme-rgb),0.3)] flex items-center gap-2 transform transition hover:scale-105 active:scale-95 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                ACAK OTOMATIS
            </button>
        </div>
    </div>
    
    @if (session()->has('success'))
        <div class="bg-green-500/10 border-l-4 border-green-500 text-green-400 p-4 mb-6 rounded-r-lg shadow-sm flex justify-between items-center">
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-500/10 border-l-4 border-red-500 text-red-400 p-4 mb-6 rounded-r-lg shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-[#0f172a] rounded-2xl shadow-lg p-5 border border-white/5">
                <h2 class="font-bold text-white mb-4 flex justify-between items-center text-sm uppercase tracking-wider">
                    <span>{{ $category->format_type == 'knockout' ? 'Daftar Tim' : 'Belum Ada Grup' }}</span>
                    <span class="bg-white/10 text-white text-xs font-bold px-2 py-1 rounded-full">{{ count($unassignedTeams) }}</span>
                </h2>
                
                <div class="space-y-2 max-h-[50vh] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($unassignedTeams as $team)
                        <div class="bg-[#1e293b] p-3 rounded-xl border border-white/5 flex items-center gap-3 hover:border-[rgb(var(--theme-rgb))]/50 transition group">
                            <div class="w-8 h-8 bg-black/30 rounded-full overflow-hidden border border-white/10 flex-shrink-0 flex items-center justify-center">
                                @if($team->logo) <img src="{{ Storage::url($team->logo) }}" class="w-full h-full object-cover"> @else <span class="text-[10px]">🛡️</span> @endif
                            </div>
                            <span class="text-xs font-bold text-slate-300 group-hover:text-white truncate flex-1">{{ $team->name }}</span>
                        </div>
                    @endforeach
                    @if(count($unassignedTeams) === 0)
                        <div class="text-center py-4 text-green-400 text-xs font-bold bg-green-500/10 rounded-xl border border-green-500/20">
                            {{ $category->format_type == 'knockout' ? 'Data tim kosong.' : 'Semua tim sudah masuk grup!' }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-[#0f172a] rounded-2xl shadow-lg p-5 border border-white/5 relative overflow-hidden" 
                x-data="{ 
                    generating: false, 
                    progress: 0,
                    statusText: 'Menyiapkan...',
                    
                    startProcess() {
                        this.generating = true;
                        this.progress = 0;
                        let interval = setInterval(() => {
                            this.progress += 2; 
                            if(this.progress > 50) this.statusText = 'Menyusun Bracket...';
                            if (this.progress >= 100) {
                                clearInterval(interval);
                                $wire.generateSchedule(); 
                            }
                        }, 50);
                    }
                }">

                <div x-show="generating" style="display: none;" class="absolute inset-0 bg-[#020617]/90 z-20 flex flex-col items-center justify-center p-6 text-center backdrop-blur-sm">
                    <span class="text-[rgb(var(--theme-rgb))] font-bold text-xs mb-2" x-text="statusText"></span>
                    <div class="w-full h-2 bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-[rgb(var(--theme-rgb))]" :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                <h3 class="font-bold text-white mb-4 text-sm uppercase tracking-wider">Scheduler</h3>
                
                @if($category->format_type != 'knockout')
                    <div class="mb-4">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Format Pertemuan</label>
                        <div class="flex gap-2">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="meetFormat" value="single" class="peer sr-only">
                                <div class="text-center text-[10px] font-bold py-2 px-2 bg-[#1e293b] border border-white/10 rounded-lg text-slate-400 peer-checked:bg-[rgb(var(--theme-rgb))] peer-checked:text-black transition hover:bg-white/5">
                                    1x Main
                                </div>
                            </label>
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" wire:model="meetFormat" value="double" class="peer sr-only">
                                <div class="text-center text-[10px] font-bold py-2 px-2 bg-[#1e293b] border border-white/10 rounded-lg text-slate-400 peer-checked:bg-[rgb(var(--theme-rgb))] peer-checked:text-black transition hover:bg-white/5">
                                    Home Away
                                </div>
                            </label>
                        </div>
                    </div>
                @endif

                <button @click="startProcess()" 
                    class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white rounded-xl font-bold shadow-lg transition flex justify-center items-center gap-2 group">
                    <span class="group-hover:text-[rgb(var(--theme-rgb))] transition-colors">GENERATE JADWAL</span>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[rgb(var(--theme-rgb))]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </div>
        </div>

        <div class="lg:col-span-3">
            
            @if($category->format_type == 'knockout')
                <div class="bg-[#0f172a] rounded-2xl shadow-lg border border-white/5 p-8 text-center min-h-[400px] flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6">
                        <span class="text-4xl"></span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Mode Sistem Gugur</h3>
                    <p class="text-slate-400 max-w-md mx-auto mb-8">
                        Dalam mode ini, tidak ada pembagian grup. Tim akan langsung dipasangkan ke dalam Bracket Pertandingan (Round of 16, Quarter Final, dst).
                    </p>
                    
                    @if(count($unassignedTeams) > 1)
                        <div class="bg-[rgb(var(--theme-rgb))]/10 border border-[rgb(var(--theme-rgb))]/20 p-4 rounded-xl max-w-lg w-full">
                            <p class="text-[rgb(var(--theme-rgb))] font-bold text-sm">
                                {{ count($unassignedTeams) }} Tim Siap Digenerate
                            </p>
                            <p class="text-xs text-slate-400 mt-1">Klik tombol "Generate Jadwal" di sebelah kiri untuk membuat bracket otomatis.</p>
                        </div>
                    @else
                        <div class="bg-red-500/10 border border-red-500/20 p-4 rounded-xl">
                            <p class="text-red-400 font-bold text-sm">Data Tim Kurang</p>
                            <p class="text-xs text-slate-500 mt-1">Minimal butuh 2 tim yang sudah di-approve untuk membuat jadwal.</p>
                        </div>
                    @endif
                </div>

            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($groups as $groupName => $teams)
                        <div class="bg-[#0f172a] rounded-2xl shadow-lg border border-white/5 overflow-hidden hover:border-[rgb(var(--theme-rgb))]/30 transition duration-300 group">
                            <div class="bg-[#1e293b] px-5 py-4 flex justify-between items-center border-b border-white/5">
                                <h3 class="font-black text-lg tracking-wider text-white">GRUP <span class="text-[rgb(var(--theme-rgb))]">{{ $groupName }}</span></h3>
                                <span class="text-[10px] font-bold bg-black/30 border border-white/10 px-2 py-1 rounded text-slate-300">{{ count($teams) }} TIM</span>
                            </div>
                            
                            <div class="p-4 min-h-[150px] space-y-2">
                                @foreach($teams as $team)
                                    <div class="flex items-center justify-between bg-[#020617] p-3 rounded-xl border border-white/5 hover:border-white/10 transition">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-white/5 rounded-full overflow-hidden border border-white/10 flex-shrink-0 flex items-center justify-center">
                                                @if($team->logo) <img src="{{ Storage::url($team->logo) }}" class="w-full h-full object-cover"> @else <span class="text-[10px]">🛡️</span> @endif
                                            </div>
                                            <span class="text-sm font-bold text-slate-300">{{ $team->name }}</span>
                                        </div>
                                        
                                        <select wire:change="moveTeam({{ $team->id }}, $event.target.value)" 
                                            class="text-[10px] py-1 pl-2 pr-6 border-white/10 rounded-lg bg-[#1e293b] text-slate-300 font-bold shadow-sm focus:ring-[rgb(var(--theme-rgb))] focus:border-[rgb(var(--theme-rgb))]">
                                            <option class="text-slate-500">Pindah...</option>
                                            <option value="" class="text-red-400 font-bold">Kick (Hapus)</option>
                                            @foreach(array_keys($groups) as $g)
                                                @if($g !== $groupName) <option value="{{ $g }}">Ke Grup {{ $g }}</option> @endif
                                            @endforeach
                                        </select>
                                    </div>
                                @endforeach
                                
                                @if(count($teams) === 0)
                                    <div class="flex flex-col items-center justify-center h-32 text-slate-600 border-2 border-dashed border-white/5 rounded-xl">
                                        <span class="text-2xl mb-1 grayscale opacity-50">∅</span>
                                        <span class="text-xs font-bold">Kosong</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
        
    </div>
</div>