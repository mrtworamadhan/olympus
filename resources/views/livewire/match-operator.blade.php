@php
    $themeColor = $game->tenant->primary_color ?? '#0ea5e9'; 
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans relative overflow-hidden selection:bg-cyan-500 selection:text-white"
     style="--theme-rgb: {{ $themeRgb }};">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-[0.2]"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[60rem] h-[30rem] bg-indigo-600/20 blur-[120px] rounded-full mix-blend-screen"></div>
    </div>

    <div class="relative z-20 bg-[#0f172a]/80 backdrop-blur-md border-b border-white/5 p-4 h-20 flex justify-between items-center shadow-lg">
        <div class="flex items-center gap-4">
            <!-- <a href="/admin/events" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 transition text-slate-400 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a> -->
            <div>
                <h1 class="text-lg font-bold text-white tracking-tight leading-none">{{ $game->category->name }}</h1>
                <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $game->venue->name ?? 'Venue TBD' }}
                </p>
            </div>
        </div>

        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-[0_0_15px_rgba(0,0,0,0.5)] border transition-all duration-500
                {{ $status === 'live' ? 'bg-red-500/10 border-red-500 text-red-500 animate-pulse shadow-red-900/20' : 'bg-slate-800 border-white/10 text-slate-500' }}">
                {{ $status === 'live' ? '● LIVE' : strtoupper($status) }}
            </span>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('game.report', $game->id) }}" target="_blank" 
               class="hidden md:flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition shadow-lg shadow-indigo-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                PDF Report
            </a>
        </div>
    </div>

    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 h-[calc(100vh-80px)]">

        <div class="bg-[#0f172a]/50 backdrop-blur-sm border-r border-white/5 p-6 flex flex-col items-center justify-center relative group">
            <h2 class="absolute top-4 left-4 text-[5rem] font-black text-white/5 select-none z-0">HOME</h2>
            
            <div class="relative z-10 flex flex-col items-center w-full">
                <div class="w-28 h-28 rounded-full bg-[#1e293b] border-4 border-cyan-500/50 shadow-[0_0_30px_rgba(6,182,212,0.2)] flex items-center justify-center mb-6 overflow-hidden relative">
                    @if($game->homeCompetitor->logo)
                        <img src="{{ Storage::url($game->homeCompetitor->logo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl grayscale">🏠</span>
                    @endif
                    
                    <div class="absolute bottom-0 w-full flex justify-center gap-1 pb-1">
                        @if(($game->meta_data['home_red_cards'] ?? 0) > 0)
                            <span class="bg-red-600 text-white text-[10px] font-bold px-1.5 rounded shadow">
                                {{ $game->meta_data['home_red_cards'] }} 🟥
                            </span>
                        @endif
                        @if(($game->meta_data['home_fouls'] ?? 0) > 0)
                            <span class="bg-yellow-500 text-black text-[10px] font-bold px-1.5 rounded shadow">
                                {{ $game->meta_data['home_fouls'] }} ⚠️
                            </span>
                        @endif
                    </div>
                </div>

                <h3 class="text-2xl font-black text-white text-center leading-tight mb-8">{{ $game->homeCompetitor->name }}</h3>

                <div class="text-[10rem] leading-none font-mono font-bold text-cyan-400 mb-8 drop-shadow-[0_0_20px_rgba(34,211,238,0.3)]">
                    {{ $game->home_score }}
                </div>

                <div class="grid grid-cols-3 gap-3 w-full max-w-[280px]">
                    <button wire:click="openEventModal('goal', {{ $game->home_competitor_id }})" 
                        class="col-span-3 py-4 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 rounded-xl text-white font-black text-xl shadow-lg border border-white/10 active:scale-95 transition flex items-center justify-center gap-2">
                        + GOAL
                    </button>

                    <button wire:click="openEventModal('foul', {{ $game->home_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-cyan-500/30">
                        <span class="text-yellow-500 font-black text-lg">F</span>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Foul</span>
                    </button>
                    <button wire:click="openEventModal('yellow_card', {{ $game->home_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-cyan-500/30">
                        <div class="w-4 h-6 bg-yellow-400 rounded-sm shadow-sm"></div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Yellow</span>
                    </button>
                    <button wire:click="openEventModal('red_card', {{ $game->home_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-cyan-500/30">
                        <div class="w-4 h-6 bg-red-600 rounded-sm shadow-sm"></div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Red</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-black/40 backdrop-blur-xl border-x border-white/5 flex flex-col relative overflow-hidden" 
             x-data="{ 
                timeLeft: @entangle('timerSeconds').live, 
                isRunning: @entangle('timerRunning').live,
                timerInterval: null,
                init() {
                    if (this.isRunning) this.startTimer();
                    this.$watch('isRunning', value => { value ? this.startTimer() : this.stopTimer(); });
                },
                startTimer() {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        if (this.timeLeft > 0) this.timeLeft--;
                        else this.stopTimer();
                    }, 1000);
                },
                stopTimer() { clearInterval(this.timerInterval); $wire.syncTimer(this.timeLeft); },
                toggle() { $wire.toggleTimer(this.timeLeft); },
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                    const s = (seconds % 60).toString().padStart(2, '0');
                    return `${m}:${s}`;
                }
             }">
            
            <div class="pt-8 pb-4 text-center border-b border-white/5">
                <p class="text-slate-500 text-[10px] uppercase tracking-[0.3em] font-bold mb-3">MATCH PERIOD</p>
                <div class="flex items-center justify-center gap-6">
                    <button wire:click="changePeriod(-1)" onclick="confirm('Mundur babak?') || event.stopImmediatePropagation()" class="w-10 h-10 rounded-full bg-[#1e293b] hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition border border-white/5">&lt;</button>
                    <div class="text-5xl font-black text-white w-20 text-center">{{ $period }}</div>
                    <button wire:click="changePeriod(1)" onclick="confirm('Lanjut babak?') || event.stopImmediatePropagation()" class="w-10 h-10 rounded-full bg-[#1e293b] hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center transition border border-white/5">&gt;</button>
                </div>
            </div>

            <div class="py-10 flex flex-col items-center justify-center bg-gradient-to-b from-transparent via-[#1e293b]/20 to-transparent">
                <div class="relative mb-8">
                    <div class="absolute inset-0 blur-[40px] opacity-20" :class="isRunning ? 'bg-green-500' : 'bg-yellow-500'"></div>
                    
                    <span class="relative z-10 text-[5rem] sm:text-[6rem] font-mono font-bold tracking-widest leading-none drop-shadow-2xl"
                        :class="timeLeft <= 60 ? 'text-red-500 animate-pulse' : (isRunning ? 'text-green-400' : 'text-yellow-400')"
                        x-text="formatTime(timeLeft)">
                        00:00
                    </span>
                </div>

                <button @click="toggle()"
                    class="w-48 py-4 rounded-full font-black text-lg uppercase tracking-widest shadow-[0_0_20px_rgba(0,0,0,0.5)] transition transform active:scale-95 border border-white/10 flex items-center justify-center gap-3"
                    :class="isRunning 
                        ? 'bg-yellow-600 hover:bg-yellow-500 text-white' 
                        : 'bg-green-600 hover:bg-green-500 text-white'">
                    <span x-text="isRunning ? '⏸ PAUSE' : '▶ START'"></span>
                </button>
            </div>

            @if($status === 'live' || $status === 'break')
                <div class="px-8 pb-6">
                    <button wire:click="updateStatus('finished')"
                        onclick="confirm('Yakin pertandingan selesai?') || event.stopImmediatePropagation()"
                        class="w-full py-3 bg-[#1e293b] hover:bg-red-900/50 text-slate-400 hover:text-red-400 rounded-xl font-bold text-xs uppercase tracking-widest border border-white/5 hover:border-red-500/50 transition">
                        ⏹ End Match
                    </button>
                </div>
            @endif

            <div class="flex-1 bg-[#020617] border-t border-white/10 flex flex-col min-h-0">
                <div class="px-4 py-2 border-b border-white/5 bg-[#0f172a] flex justify-between items-center">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Event Log</span>
                    <div class="flex gap-1">
                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                        <div class="w-2 h-2 rounded-full bg-yellow-500"></div>
                        <div class="w-2 h-2 rounded-full bg-green-500"></div>
                    </div>
                </div>
                
                <div class="flex-1 overflow-y-auto p-4 space-y-2 custom-scrollbar font-mono text-xs">
                    @php $sortedEvents = $game->events->sortByDesc('id'); @endphp
                    @forelse($sortedEvents as $event)
                        @php
                            $isHome = $event->competitor_id == $game->home_competitor_id;
                            $colorClass = $isHome ? 'text-cyan-400' : 'text-orange-400';
                            $borderClass = $isHome ? 'border-l-2 border-cyan-500' : 'border-r-2 border-orange-500';
                        @endphp
                        <div class="group flex items-center justify-between bg-[#1e293b]/50 p-2 rounded hover:bg-[#1e293b] transition {{ $borderClass }}">
                            <div class="flex items-center gap-3">
                                <span class="text-slate-500 font-bold w-8">{{ $event->minute }}'</span>
                                <div>
                                    <span class="font-bold {{ $colorClass }}">{{ $isHome ? 'HOME' : 'AWAY' }}</span>
                                    <span class="text-slate-300 mx-1">|</span>
                                    <span class="text-white">{{ $event->player->name ?? 'Unknown' }}</span>
                                    <span class="text-[10px] ml-2 px-1.5 py-0.5 rounded bg-white/10 text-slate-300 uppercase tracking-wide">
                                        {{ str_replace('_', ' ', $event->event_type) }}
                                    </span>
                                </div>
                            </div>
                            <button wire:click="deleteEvent({{ $event->id }})" 
                                onclick="confirm('Hapus event ini?') || event.stopImmediatePropagation()"
                                class="text-slate-600 hover:text-red-500 opacity-0 group-hover:opacity-100 transition px-2">
                                [DEL]
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8 text-slate-700 italic">Waiting for events...</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-[#0f172a]/50 backdrop-blur-sm border-l border-white/5 p-6 flex flex-col items-center justify-center relative group">
            <h2 class="absolute top-4 right-4 text-[5rem] font-black text-white/5 select-none z-0">AWAY</h2>
            
            <div class="relative z-10 flex flex-col items-center w-full">
                <div class="w-28 h-28 rounded-full bg-[#1e293b] border-4 border-orange-500/50 shadow-[0_0_30px_rgba(249,115,22,0.2)] flex items-center justify-center mb-6 overflow-hidden relative">
                    @if($game->awayCompetitor->logo)
                        <img src="{{ Storage::url($game->awayCompetitor->logo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl grayscale"></span>
                    @endif

                    <div class="absolute bottom-0 w-full flex justify-center gap-1 pb-1">
                        @if(($game->meta_data['away_red_cards'] ?? 0) > 0)
                            <span class="bg-red-600 text-white text-[10px] font-bold px-1.5 rounded shadow">
                                {{ $game->meta_data['away_red_cards'] }} 🟥
                            </span>
                        @endif
                        @if(($game->meta_data['away_fouls'] ?? 0) > 0)
                            <span class="bg-yellow-500 text-black text-[10px] font-bold px-1.5 rounded shadow">
                                {{ $game->meta_data['away_fouls'] }} ⚠️
                            </span>
                        @endif
                    </div>
                </div>

                <h3 class="text-2xl font-black text-white text-center leading-tight mb-8">{{ $game->awayCompetitor->name }}</h3>

                <div class="text-[10rem] leading-none font-mono font-bold text-orange-500 mb-8 drop-shadow-[0_0_20px_rgba(249,115,22,0.3)]">
                    {{ $game->away_score }}
                </div>

                <div class="grid grid-cols-3 gap-3 w-full max-w-[280px]">
                    <button wire:click="openEventModal('goal', {{ $game->away_competitor_id }})" 
                        class="col-span-3 py-4 bg-gradient-to-r from-orange-600 to-amber-600 hover:from-orange-500 hover:to-amber-500 rounded-xl text-white font-black text-xl shadow-lg border border-white/10 active:scale-95 transition flex items-center justify-center gap-2">
                        + GOAL
                    </button>

                    <button wire:click="openEventModal('foul', {{ $game->away_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-orange-500/30">
                        <span class="text-yellow-500 font-black text-lg">F</span>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Foul</span>
                    </button>
                    <button wire:click="openEventModal('yellow_card', {{ $game->away_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-orange-500/30">
                        <div class="w-4 h-6 bg-yellow-400 rounded-sm shadow-sm"></div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Yellow</span>
                    </button>
                    <button wire:click="openEventModal('red_card', {{ $game->away_competitor_id }})" class="aspect-square bg-[#1e293b] hover:bg-[#334155] border border-white/10 rounded-xl flex flex-col items-center justify-center gap-1 transition group-hover:border-orange-500/30">
                        <div class="w-4 h-6 bg-red-600 rounded-sm shadow-sm"></div>
                        <span class="text-[8px] text-slate-400 uppercase font-bold">Red</span>
                    </button>
                </div>
            </div>
        </div>

        @if($showEventModal)
            <div class="fixed inset-0 z-50 bg-[#020617]/80 backdrop-blur-md flex items-center justify-center p-4">
                <div class="bg-[#0f172a] rounded-2xl w-full max-w-sm p-6 border border-white/10 shadow-2xl animate-fade-in-up ring-1 ring-white/10">
                    <h3 class="text-xl font-black mb-6 uppercase text-center tracking-tight text-white border-b border-white/5 pb-4 flex items-center justify-center gap-2">
                        @switch($eventType)
                            @case('goal') INPUT GOAL @break
                            @case('yellow_card') 🟨 YELLOW CARD @break
                            @case('red_card') 🟥 RED CARD @break
                            @case('foul') ⚠️ RECORD FOUL @break
                        @endswitch
                    </h3>

                    <label class="block text-[10px] font-bold text-slate-500 mb-3 uppercase tracking-widest">Select Player</label>
                    
                    <div class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto mb-6 custom-scrollbar pr-1">
                        @php
                            $teamPlayers = \App\Models\Player::where('competitor_id', $this->selectedTeamId)->orderBy('number')->get();
                        @endphp
                        
                        @foreach($teamPlayers as $player)
                            <button wire:click="$set('selectedPlayerId', {{ $player->id }})"
                                class="flex items-center gap-4 p-3 rounded-xl border transition group
                                {{ $selectedPlayerId == $player->id ? 'border-cyan-500 bg-cyan-500/10' : 'border-white/5 bg-white/5 hover:bg-white/10 hover:border-white/10' }}">
                                <span class="font-mono font-bold text-lg w-10 h-10 flex items-center justify-center rounded-lg
                                    {{ $selectedPlayerId == $player->id ? 'bg-cyan-500 text-black' : 'bg-[#020617] text-slate-400' }}">
                                    {{ $player->number ?? '#' }}
                                </span>
                                <span class="font-bold {{ $selectedPlayerId == $player->id ? 'text-cyan-400' : 'text-white' }}">{{ $player->name }}</span>
                            </button>
                        @endforeach

                        @if($teamPlayers->count() == 0)
                            <div class="text-center py-8 rounded-xl border border-dashed border-white/10 bg-white/5">
                                <p class="text-slate-500 text-sm">No players data.</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="$set('showEventModal', false)" class="flex-1 py-3 bg-[#1e293b] hover:bg-[#334155] rounded-xl font-bold text-slate-400 transition">CANCEL</button>
                        <button wire:click="saveEvent" class="flex-1 py-3 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white rounded-xl font-bold shadow-lg transition">SAVE</button>
                    </div>
                </div>
            </div>
        @endif

        @if($showPenaltyModal)
            <div class="fixed inset-0 z-[60] bg-[#020617]/90 backdrop-blur-md flex items-center justify-center p-4">
                <div class="bg-[#0f172a] rounded-3xl w-full max-w-md p-8 border border-orange-500/30 shadow-2xl animate-fade-in-up relative overflow-hidden">

                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-32 bg-orange-500/20 blur-[50px]"></div>

                    <h3 class="text-2xl font-black mb-2 text-center text-white uppercase relative z-10">Penalty Shootout 🥅</h3>
                    <p class="text-slate-400 text-center text-sm mb-8 relative z-10">Enter the final penalty score.</p>

                    <div class="flex justify-between items-center gap-6 mb-10 relative z-10">
                        <div class="text-center w-1/3">
                            <h4 class="font-bold text-xs text-cyan-400 mb-3 truncate uppercase tracking-widest">{{ $game->homeCompetitor->name }}</h4>
                            <input type="number" wire:model="penHome" class="w-full text-center bg-[#020617] border border-white/10 rounded-2xl text-4xl font-mono font-bold py-4 focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-white shadow-inner">
                        </div>

                        <div class="text-2xl font-bold text-slate-600">-</div>

                        <div class="text-center w-1/3">
                            <h4 class="font-bold text-xs text-orange-400 mb-3 truncate uppercase tracking-widest">{{ $game->awayCompetitor->name }}</h4>
                            <input type="number" wire:model="penAway" class="w-full text-center bg-[#020617] border border-white/10 rounded-2xl text-4xl font-mono font-bold py-4 focus:ring-2 focus:ring-orange-500 focus:border-transparent text-white shadow-inner">
                        </div>
                    </div>

                    <button wire:click="finishWithPenalties" class="w-full py-4 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 rounded-xl font-bold text-white text-lg shadow-lg transform transition active:scale-95 relative z-10">
                        FINISH MATCH
                    </button>
                </div>
            </div>
        @endif

    </div> 

</div>