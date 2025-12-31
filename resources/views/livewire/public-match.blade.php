@php
    $themeColor = $game->tenant->primary_color ?? '#fbbf24'; 
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans pb-12 selection:bg-[rgb(var(--theme-rgb))] selection:text-black overflow-x-hidden" 
     style="--theme-rgb: {{ $themeRgb }};"
     wire:poll.2s>

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-[0.2]"></div>
        <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[40rem] h-[20rem] bg-[rgb(var(--theme-rgb))] opacity-10 blur-[100px] rounded-full mix-blend-screen"></div>
    </div>

    <div class="relative z-10 pt-6 px-4 mb-4">
        <div class="max-w-md mx-auto">
            <a href="{{ route('public.category', [$tenant->slug, $event->slug, $game->category ?? '']) }}" wire:navigate 
               class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[rgb(var(--theme-rgb))] transition-colors group mb-4">
                <span class="p-1 rounded-full bg-white/5 border border-white/10 group-hover:border-[rgb(var(--theme-rgb))]/50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </span>
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="relative z-10 px-4">
        <div class="max-w-md mx-auto bg-[#0f172a] border border-white/10 rounded-3xl shadow-2xl overflow-hidden relative">
            
            <div class="bg-white/5 p-4 text-center border-b border-white/5">
                <h1 class="text-sm font-black uppercase tracking-widest text-white mb-1">{{ $game->category->name }}</h1>
                <p class="text-xs font-medium text-slate-400 flex justify-center items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $game->venue->name }}
                </p>
                <div class="mt-3">
                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border 
                        {{ $game->status === 'live' ? 'bg-red-500/10 border-red-500/50 text-red-500 animate-pulse' : 'bg-slate-800 border-white/10 text-slate-400' }}">
                        {{ $game->status === 'live' ? '● LIVE MATCH' : strtoupper($game->status) }}
                    </span>
                </div>
            </div>

            <div class="p-6 pb-8">
                <div class="flex justify-between items-center relative">
                    
                    <div class="flex flex-col items-center w-1/3 text-center z-10">
                        <div class="w-20 h-20 rounded-full mb-3 flex items-center justify-center border-2 border-white/10 bg-[#020617] shadow-[0_0_20px_rgba(0,0,0,0.5)] relative">
                            @if($game->homeCompetitor->logo)
                                <img src="{{ Storage::url($game->homeCompetitor->logo) }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <span class="text-3xl"></span>
                            @endif
                            
                            @if(($game->meta_data['home_red_cards'] ?? 0) > 0)
                                <div class="absolute -bottom-1 -right-1 bg-red-600 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-[#0f172a] shadow-lg">
                                    {{ $game->meta_data['home_red_cards'] }}
                                </div>
                            @endif
                        </div>
                        <h2 class="font-bold text-sm text-white leading-tight line-clamp-2">
                            {{ $game->homeCompetitor->name }}
                        </h2>
                    </div>

                    <div class="flex flex-col items-center justify-center w-1/3 relative z-10" 
                        x-data="{ 
                            serverSeconds: @entangle('timerSeconds'),
                            isRunning: @entangle('timerRunning'),
                            timeLeft: 0,
                            localInterval: null,
                            init() {
                                this.timeLeft = this.serverSeconds;
                                if (this.isRunning) this.startLocalTimer();
                                this.$watch('serverSeconds', (newVal) => { if (!this.isRunning) this.timeLeft = newVal; });
                                this.$watch('isRunning', (val) => {
                                    if (val) this.startLocalTimer();
                                    else { clearInterval(this.localInterval); this.timeLeft = this.serverSeconds; }
                                });
                            },
                            startLocalTimer() {
                                if (this.localInterval) clearInterval(this.localInterval);
                                this.localInterval = setInterval(() => { if (this.timeLeft > 0) this.timeLeft--; }, 1000);
                            },
                            formatTime(seconds) {
                                const m = Math.floor(seconds / 60).toString().padStart(2, '0');
                                const s = (seconds % 60).toString().padStart(2, '0');
                                return `${m}:${s}`;
                            }
                        }">
                        
                        <div class="text-5xl sm:text-6xl font-black text-white tracking-tighter leading-none mb-3 font-mono drop-shadow-[0_0_10px_rgba(var(--theme-rgb),0.5)]">
                            {{ $game->home_score }}<span class="text-slate-600 mx-1">:</span>{{ $game->away_score }}
                        </div>

                        @if(isset($game->meta_data['penalty_home']))
                            <div class="text-sm font-bold text-[rgb(var(--theme-rgb))] uppercase tracking-widest mb-3 animate-pulse">
                                (Pen: {{ $game->meta_data['penalty_home'] }} - {{ $game->meta_data['penalty_away'] }})
                            </div>
                        @else
                            <div class="mb-3"></div>
                        @endif

                        <div class="font-mono text-xl font-bold tracking-widest px-3 py-1 rounded bg-[#020617] border border-white/10"
                            :class="isRunning ? 'text-green-400 border-green-500/30 shadow-[0_0_10px_rgba(74,222,128,0.2)]' : 'text-slate-500'">
                            <span x-text="formatTime(timeLeft)">00:00</span>
                        </div>

                        <div class="text-[10px] font-bold text-slate-500 mt-2 uppercase tracking-widest">
                            Period {{ $game->meta_data['period'] ?? 1 }}
                        </div>
                    </div>

                    <div class="flex flex-col items-center w-1/3 text-center z-10">
                        <div class="w-20 h-20 rounded-full mb-3 flex items-center justify-center border-2 border-white/10 bg-[#020617] shadow-[0_0_20px_rgba(0,0,0,0.5)] relative">
                            @if($game->awayCompetitor->logo)
                                <img src="{{ Storage::url($game->awayCompetitor->logo) }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <span class="text-3xl"></span>
                            @endif

                            @if(($game->meta_data['away_red_cards'] ?? 0) > 0)
                                <div class="absolute -bottom-1 -right-1 bg-red-600 text-white text-[10px] font-bold w-6 h-6 flex items-center justify-center rounded-full border-2 border-[#0f172a] shadow-lg">
                                    {{ $game->meta_data['away_red_cards'] }}
                                </div>
                            @endif
                        </div>
                        <h2 class="font-bold text-sm text-white leading-tight line-clamp-2">
                            {{ $game->awayCompetitor->name }}
                        </h2>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto mt-6 px-4 relative z-10">
        <div class="bg-[#0f172a] rounded-3xl p-6 border border-white/5 shadow-lg relative min-h-[200px]">
            
            <div class="absolute left-1/2 top-6 bottom-6 w-px bg-white/5 -translate-x-1/2 hidden md:block"></div>

            <div class="flex flex-col md:flex-row gap-8 md:gap-4 relative">
                
                <div class="flex-1 flex flex-col items-end gap-4">
                    @forelse($game->events->where('competitor_id', $game->home_competitor_id)->sortBy('minute') as $event)
                        <div class="flex items-center gap-3 animate-fade-in-up w-full md:w-auto justify-end">
                            <div class="text-right">
                                <div class="text-xs font-bold text-white">{{ $event->player->name }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider
                                    @if($event->event_type == 'goal') text-[rgb(var(--theme-rgb))]
                                    @elseif($event->event_type == 'yellow_card') text-yellow-400
                                    @elseif($event->event_type == 'red_card') text-red-500
                                    @endif">
                                    @if($event->event_type == 'goal') Goal ⚽
                                    @elseif($event->event_type == 'yellow_card') Yellow Card 🟨
                                    @elseif($event->event_type == 'red_card') Red Card 🟥
                                    @endif
                                </div>
                            </div>
                            <span class="font-mono text-xs font-bold bg-white/5 border border-white/10 px-2 py-1 rounded text-slate-400 w-10 text-center">
                                {{ $event->minute }}'
                            </span>
                        </div>
                    @empty
                        <div class="hidden md:block text-[10px] text-slate-700 text-right w-full italic pr-4 mt-4">No events yet</div>
                    @endforelse
                </div>

                <div class="hidden md:block w-4"></div>

                <div class="flex-1 flex flex-col items-start gap-4">
                    @forelse($game->events->where('competitor_id', $game->away_competitor_id)->sortBy('minute') as $event)
                        <div class="flex items-center gap-3 animate-fade-in-up w-full md:w-auto flex-row-reverse md:flex-row justify-end md:justify-start">
                            <span class="font-mono text-xs font-bold bg-white/5 border border-white/10 px-2 py-1 rounded text-slate-400 w-10 text-center">
                                {{ $event->minute }}'
                            </span>
                            <div class="text-right md:text-left">
                                <div class="text-xs font-bold text-white">{{ $event->player->name }}</div>
                                <div class="text-[10px] font-bold uppercase tracking-wider
                                    @if($event->event_type == 'goal') text-[rgb(var(--theme-rgb))]
                                    @elseif($event->event_type == 'yellow_card') text-yellow-400
                                    @elseif($event->event_type == 'red_card') text-red-500
                                    @endif">
                                    @if($event->event_type == 'goal') ⚽ Goal
                                    @elseif($event->event_type == 'yellow_card') 🟨 Yellow Card
                                    @elseif($event->event_type == 'red_card') 🟥 Red Card
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="hidden md:block text-[10px] text-slate-700 text-left w-full italic pl-4 mt-4">No events yet</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto mt-8 px-6">
        <h3 class="text-slate-500 text-[10px] font-bold uppercase tracking-widest mb-4 text-center">Team Fouls</h3>

        <div class="mb-6 bg-[#0f172a] p-5 rounded-2xl border border-white/5 shadow-sm">
            <div class="flex justify-between items-end mb-2">
                <span class="text-2xl font-black text-white">{{ $game->meta_data['home_fouls'] ?? 0 }}</span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest pb-1">{{ $game->homeCompetitor->name }} vs {{ $game->awayCompetitor->name }}</span>
                <span class="text-2xl font-black text-white">{{ $game->meta_data['away_fouls'] ?? 0 }}</span>
            </div>
            
            <div class="flex h-2 rounded-full overflow-hidden bg-white/5">
                @php
                    $hFoul = $game->meta_data['home_fouls'] ?? 0;
                    $aFoul = $game->meta_data['away_fouls'] ?? 0;
                    $total = $hFoul + $aFoul;
                    $hPercent = $total > 0 ? ($hFoul / $total) * 100 : 50;
                @endphp
                <div style="background-color: rgb(var(--theme-rgb)); width: {{ $hPercent }}%;" class="h-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(var(--theme-rgb),0.5)]"></div>
                <div class="bg-slate-700 h-full flex-1"></div>
            </div>

            <div class="flex justify-between mt-2 text-[10px] text-red-500 font-bold">
                <span class="{{ ($game->meta_data['home_fouls'] ?? 0) >= 5 ? 'opacity-100 animate-pulse' : 'opacity-0' }}">
                    ⚠️ LIMIT REACHED
                </span>
                <span class="{{ ($game->meta_data['away_fouls'] ?? 0) >= 5 ? 'opacity-100 animate-pulse' : 'opacity-0' }}">
                    LIMIT REACHED ⚠️
                </span>
            </div>
        </div>

        <div class="text-center mt-12 pb-8 border-t border-white/5 pt-8">
            <p class="text-[10px] text-slate-500 uppercase tracking-widest">
                Real-time Data • Powered by {{ config('app.name') }}
            </p>
        </div>
    </div>
</div>