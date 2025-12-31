@php
    $themeColor = $event->primary_color ?? $event->tenant->primary_color ?? '#fbbf24';
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans pb-20 selection:bg-[rgb(var(--theme-rgb))] selection:text-black overflow-x-hidden"
     style="--theme-rgb: {{ $themeRgb }};">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b_1px,transparent_1px),linear-gradient(to_bottom,#1e293b_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-[0.2]"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[50rem] h-[30rem] bg-[rgb(var(--theme-rgb))] opacity-10 blur-[120px] rounded-full mix-blend-screen"></div>
    </div>

    <div class="relative z-10 pt-8 px-4 mb-6">
        <div class="max-w-md mx-auto">
            <a href="{{ route('public.landing', [$tenant->slug, $event->slug]) }}" wire:navigate 
               class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[rgb(var(--theme-rgb))] transition-colors mb-6 group">
                <span class="p-1 rounded-full bg-white/5 border border-white/10 group-hover:border-[rgb(var(--theme-rgb))]/50 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </span>
                Back to Home
            </a>

            <div class="bg-[#0f172a]/80 backdrop-blur-md border border-white/10 p-6 rounded-3xl shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[rgb(var(--theme-rgb))] opacity-10 blur-[50px] rounded-full"></div>
                
                <div class="relative z-10">
                    <span class="inline-block py-1 px-3 rounded-full bg-[rgb(var(--theme-rgb))]/10 border border-[rgb(var(--theme-rgb))]/20 text-[rgb(var(--theme-rgb))] text-[10px] font-bold uppercase tracking-widest mb-3">
                        Category Dashboard
                    </span>
                    <h1 class="text-3xl font-black text-white uppercase tracking-tight mb-2 leading-none">
                        {{ $category->name }}
                    </h1>
                    <p class="text-sm text-slate-400 flex items-center gap-2 font-medium">
                        <svg class="w-4 h-4 text-[rgb(var(--theme-rgb))]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $event->start_date->format('d M') }} - {{ $event->end_date->format('d M Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-md mx-auto px-4 relative z-10 mb-8 flex items-center gap-2">
        <div class="bg-[#0f172a] rounded-xl p-1.5 flex shadow-lg border border-white/5 flex-1">
            @foreach(['matches' => 'Matches', 'standings' => 'Table', 'bracket' => 'Bracket', 'stats' => 'Stats'] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" 
                    class="flex-1 py-2 text-[10px] sm:text-xs font-bold rounded-lg transition-all duration-300 relative overflow-hidden
                    {{ $activeTab === $key 
                        ? 'text-black shadow-[0_0_20px_rgba(var(--theme-rgb),0.3)]' 
                        : 'text-slate-400 hover:text-white hover:bg-white/5' }}"
                    style="{{ $activeTab === $key ? 'background-color: rgb(var(--theme-rgb));' : '' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
        <button onclick="shareContent('{{ $event->name }} - {{ ucfirst($activeTab) }}', 'Cek info terbaru {{ ucfirst($activeTab) }} di {{ $event->name }}!', window.location.href)"
                class="bg-[#0f172a] hover:bg-[#1e293b] text-slate-400 hover:text-[rgb(var(--theme-rgb))] p-3 rounded-xl border border-white/5 shadow-lg transition active:scale-95"
                title="Share halaman ini">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
        </button>
    </div>

    <div class="max-w-md mx-auto px-4 relative z-10 min-h-[50vh]">
        
        @if($activeTab === 'matches')
            <div class="space-y-8 animate-fade-in-up">
                @forelse($groupedGames as $date => $games)
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="h-px flex-1 bg-white/10"></div>
                            <h3 class="text-xs font-bold text-[rgb(var(--theme-rgb))] uppercase tracking-widest">{{ $date }}</h3>
                            <div class="h-px flex-1 bg-white/10"></div>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($games as $game)
                                <div class="relative group block bg-[#0f172a] border border-white/5 rounded-2xl transition-all hover:-translate-y-1 hover:shadow-lg overflow-hidden hover:border-[rgb(var(--theme-rgb))]/50">
                                    <div class="absolute top-0 right-0 z-30">
                                        <button onclick="event.preventDefault(); event.stopPropagation(); shareContent('Match Day', 'Saksikan {{ $game->homeCompetitor->name ?? 'TBA' }} vs {{ $game->awayCompetitor->name ?? 'TBA' }}', '{{ route('public.match', [$event->tenant->slug, $event->slug, $game->id]) }}')"
                                                class="bg-white/5 hover:bg-[rgb(var(--theme-rgb))] hover:text-black text-slate-500 p-2.5 rounded-bl-2xl border-l border-b border-white/5 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                                        </button>
                                    </div>
                                    <a href="{{ route('public.match', [$event->tenant->slug, $event->slug, $game->id]) }}" wire:navigate class="block p-4 w-full h-full">
                                        <div class="absolute inset-0 bg-gradient-to-r from-[rgb(var(--theme-rgb))]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        
                                        <div class="relative z-10 flex justify-between items-center text-[10px] font-bold tracking-widest text-slate-500 mb-4 uppercase pr-8">
                                            <div class="flex items-center gap-2">
                                                <span class="{{ $game->status === 'live' ? 'text-red-500 animate-pulse font-black' : '' }}">
                                                    {{ $game->status === 'live' ? '● LIVE' : $game->scheduled_at->format('H:i') }}
                                                </span>
                                                <span class="px-2 py-0.5 rounded bg-white/5 border border-white/5 text-slate-300 group-hover:text-[rgb(var(--theme-rgb))] transition-colors">
                                                    {{ (isset($game->meta_data['group_stage']) && strlen($game->meta_data['group_stage']) == 1) ? 'GRUP '.$game->meta_data['group_stage'] : ($game->meta_data['group_stage'] ?? 'MATCH') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    {{ $game->venue->name ?? 'TBA' }}
                                                </span>
                                            </div>
                                            
                                        </div>

                                        <div class="relative z-10 flex justify-between items-center">
                                            <div class="flex items-center gap-3 w-5/12">
                                                <div class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center overflow-hidden border border-white/10">
                                                    @if($game->homeCompetitor && $game->homeCompetitor->logo) <img src="{{ Storage::url($game->homeCompetitor->logo) }}" class="w-full h-full object-cover"> @else <span class="text-xs"></span> @endif
                                                </div>
                                                <span class="font-bold text-sm truncate text-white {{ $game->home_score > $game->away_score ? 'text-[rgb(var(--theme-rgb))]' : '' }}">{{ $game->homeCompetitor->name ?? 'TBA' }}</span>
                                            </div>
                                            <div class="w-2/12 text-center">
                                                @if($game->status === 'scheduled') <span class="text-xs text-slate-600 font-bold bg-white/5 px-2 py-1 rounded">VS</span>
                                                @else <span class="font-black text-xl text-white tracking-widest">{{ $game->home_score }} - {{ $game->away_score }}</span> @endif
                                            </div>
                                            <div class="flex items-center justify-end gap-3 w-5/12">
                                                <span class="font-bold text-sm truncate text-right text-white {{ $game->away_score > $game->home_score ? 'text-[rgb(var(--theme-rgb))]' : '' }}">{{ $game->awayCompetitor->name ?? 'TBA' }}</span>
                                                <div class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center overflow-hidden border border-white/10">
                                                    @if($game->awayCompetitor && $game->awayCompetitor->logo) <img src="{{ Storage::url($game->awayCompetitor->logo) }}" class="w-full h-full object-cover"> @else <span class="text-xs"></span> @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20 border border-dashed border-white/10 rounded-3xl bg-white/5">
                        <div class="text-[4rem] mb-4 opacity-50"></div>
                        <h3 class="text-white font-bold text-lg">Belum Ada Jadwal</h3>
                        <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">
                            Jadwal pertandingan belum dirilis oleh panitia. Silakan cek kembali nanti.
                        </p>
                    </div>
                @endforelse
            </div>
        @endif

        @if($activeTab === 'standings')
            <div class="animate-fade-in-up">
                @if($category->competition_type == 'knockout')
                    <div class="text-center py-20 border border-dashed border-white/10 rounded-3xl bg-white/5">
                        <div class="text-[4rem] mb-4 opacity-50"></div>
                        <h3 class="text-white font-bold text-lg">Sistem Gugur (Knockout)</h3>
                        <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto mb-6">
                            Kategori ini menggunakan sistem gugur. Tidak ada klasemen poin. Silakan lihat bagan pertandingan.
                        </p>
                        <button wire:click="setTab('bracket')" class="px-6 py-2 bg-[rgb(var(--theme-rgb))] hover:bg-[rgb(var(--theme-rgb))]/80 text-black font-bold rounded-full transition shadow-lg">
                            Lihat Bracket →
                        </button>
                    </div>
                @else
                    <div class="space-y-8">
                        @foreach($standings as $group => $teams)
                            <div class="bg-[#0f172a] rounded-2xl shadow-sm overflow-hidden border border-white/5">
                                <div class="bg-white/5 px-4 py-3 border-b border-white/5 flex items-center gap-2">
                                    <span class="w-2 h-4 rounded-full bg-[rgb(var(--theme-rgb))]"></span>
                                    <h3 class="font-bold text-white tracking-wide">Group {{ $group }}</h3>
                                </div>
                                <table class="w-full text-sm">
                                    <thead>
                                        <tr class="text-[10px] uppercase text-slate-500 font-bold tracking-wider border-b border-white/5 bg-[#0f172a]">
                                            <th class="pl-4 py-3 text-left font-mono w-8">#</th>
                                            <th class="py-3 text-left">Team</th>
                                            <th class="py-3 text-center w-8">P</th>
                                            <th class="py-3 text-center w-8">W</th>
                                            <th class="py-3 text-center w-8">D</th>
                                            <th class="py-3 text-center w-8">L</th>
                                            <th class="py-3 text-center w-10">GD</th>
                                            <th class="pr-4 py-3 text-right w-10">PTS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($teams as $team)
                                            <tr class="hover:bg-white/5 transition group">
                                                <td class="pl-4 py-3 text-slate-500 text-xs font-mono font-bold group-hover:text-white">{{ $loop->iteration }}</td>
                                                <td class="py-3">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-6 h-6 bg-white/5 rounded-full overflow-hidden shrink-0 border border-white/10">
                                                            @if($team->logo) <img src="{{ Storage::url($team->logo) }}" class="w-full h-full object-cover"> @else <span class="text-[8px] flex items-center justify-center h-full">⚽</span> @endif
                                                        </div>
                                                        <span class="font-bold text-slate-200 group-hover:text-[rgb(var(--theme-rgb))] transition-colors truncate max-w-[120px]">{{ $team->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="text-center font-medium text-slate-400">{{ $team->stats['played'] }}</td>
                                                <td class="text-center text-slate-600 text-xs">{{ $team->stats['won'] }}</td>
                                                <td class="text-center text-slate-600 text-xs">{{ $team->stats['drawn'] }}</td>
                                                <td class="text-center text-slate-600 text-xs">{{ $team->stats['lost'] }}</td>
                                                <td class="text-center text-slate-500 text-xs font-mono">
                                                    <span class="{{ $team->stats['goal_diff'] > 0 ? 'text-green-400' : ($team->stats['goal_diff'] < 0 ? 'text-red-400' : '') }}">
                                                        {{ $team->stats['goal_diff'] > 0 ? '+'.$team->stats['goal_diff'] : $team->stats['goal_diff'] }}
                                                    </span>
                                                </td>
                                                <td class="pr-4 py-3 text-right font-black text-white text-base">{{ $team->stats['points'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @if($activeTab === 'bracket')
            <div class="overflow-x-auto pb-12 pt-4 -mx-4 px-4 scrollbar-hide animate-fade-in-up">
                @if(count($knockoutStages) > 0)
                    <div class="flex gap-0 min-w-max justify-center items-stretch pl-4">
                        
                        @foreach($knockoutStages as $stageName => $games)
                            @php
                                $stageIndex = $loop->index;
                                $heightMultiplier = 100 * pow(2, $stageIndex);
                                $pixelGap = 16 * pow(2, $stageIndex); 
                            @endphp

                            <div class="flex flex-col relative w-[280px]">
                                <div class="text-center mb-8 h-8 flex-shrink-0">
                                    <span class="text-[10px] font-black text-[#020617] uppercase tracking-wider px-4 py-1.5 rounded-full shadow-[0_0_15px_rgba(var(--theme-rgb),0.4)]"
                                          style="background-color: rgb(var(--theme-rgb));">
                                        {{ $stageName }}
                                    </span>
                                </div>

                                <div class="flex flex-col flex-1 justify-around relative px-3">
                                    @foreach($games as $game)
                                        <div class="relative py-2 w-full z-20">
                                            
                                            <a href="{{ route('public.match', [$event->tenant->slug, $event->slug, $game->id]) }}" wire:navigate 
                                               class="block relative bg-[#0f172a] border border-white/10 rounded-xl shadow-lg hover:border-[rgb(var(--theme-rgb))]/50 transition p-3 group">
                                                
                                                <div class="flex justify-between items-center mb-2 border-b border-white/5 pb-2">
                                                    <span class="text-[9px] font-bold text-slate-500 uppercase">Match #{{ $game->id }}</span>
                                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $game->status == 'scheduled' ? 'bg-blue-500/10 text-blue-400' : 'bg-green-500/10 text-green-400' }}">
                                                        {{ $game->status == 'scheduled' ? 'VS' : 'FT' }}
                                                    </span>
                                                </div>

                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center bg-[#1e293b] p-2 rounded-lg border border-white/5 {{ $game->home_score > $game->away_score ? 'border-l-2 border-l-green-500' : '' }}">
                                                        <div class="flex items-center gap-2 overflow-hidden">
                                                            @if($game->homeCompetitor && $game->homeCompetitor->logo) <img src="{{ Storage::url($game->homeCompetitor->logo) }}" class="w-4 h-4 object-cover"> @endif
                                                            <span class="font-bold text-xs text-slate-200 truncate w-20">{{ $game->homeCompetitor->name ?? ($game->meta_data['placeholder_home'] ?? 'TBA') }}</span>
                                                        </div>
                                                        <span class="font-black text-xs text-white">{{ $game->status !== 'scheduled' ? $game->home_score : '' }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center bg-[#1e293b] p-2 rounded-lg border border-white/5 {{ $game->away_score > $game->home_score ? 'border-l-2 border-l-green-500' : '' }}">
                                                        <div class="flex items-center gap-2 overflow-hidden">
                                                            @if($game->awayCompetitor && $game->awayCompetitor->logo) <img src="{{ Storage::url($game->awayCompetitor->logo) }}" class="w-4 h-4 object-cover"> @endif
                                                            <span class="font-bold text-xs text-slate-200 truncate w-20">{{ $game->awayCompetitor->name ?? ($game->meta_data['placeholder_away'] ?? 'TBA') }}</span>
                                                        </div>
                                                        <span class="font-black text-xs text-white">{{ $game->status !== 'scheduled' ? $game->away_score : '' }}</span>
                                                    </div>
                                                </div>
                                            </a>

                                            @if(!$loop->parent->last) 
                                                <div class="absolute right-[-12px] top-1/2 w-[12px] h-[2px] bg-white/10 z-10"></div>
                                                @if($loop->iteration % 2 != 0)
                                                    <div class="absolute right-[-14px] top-1/2 w-[2px] border-r-2 border-white/10"
                                                         style="height: calc({{ $heightMultiplier }}%); transform-origin: top;">
                                                    </div>
                                                @endif
                                            @else
                                                <div class="absolute right-[-30px] top-1/2 w-[30px] h-[2px] bg-[rgb(var(--theme-rgb))] z-10"></div>
                                            @endif

                                            @if(!$loop->parent->first)
                                                <div class="absolute left-[-12px] top-1/2 w-[12px] h-[2px] bg-white/10 z-10"></div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <div class="flex flex-col justify-center relative w-[200px] pl-4">
                            <div class="relative bg-gradient-to-b from-yellow-600/20 to-yellow-900/10 border border-yellow-500/30 rounded-xl shadow-[0_0_30px_rgba(234,179,8,0.1)] p-4 text-center animate-pulse-slow">
                                <div class="absolute -top-5 left-1/2 -translate-x-1/2"><span class="text-3xl drop-shadow-lg">🏆</span></div>
                                <h3 class="mt-4 text-[10px] font-bold text-yellow-500 uppercase tracking-widest mb-2">CHAMPION</h3>
                                
                                @php
                                    $finalMatch = collect($knockoutStages)->last()[0] ?? null;
                                    $winnerName = 'TBD';
                                    $winnerLogo = null;
                                    if($finalMatch && $finalMatch->status == 'finished') {
                                        if($finalMatch->home_score > $finalMatch->away_score) {
                                            $winnerName = $finalMatch->homeCompetitor->name;
                                            $winnerLogo = $finalMatch->homeCompetitor->logo;
                                        } elseif($finalMatch->away_score > $finalMatch->home_score) {
                                            $winnerName = $finalMatch->awayCompetitor->name;
                                            $winnerLogo = $finalMatch->awayCompetitor->logo;
                                        }
                                    }
                                @endphp

                                <div class="w-16 h-16 bg-[#0f172a] rounded-full mx-auto mb-2 border-2 border-yellow-500/50 flex items-center justify-center overflow-hidden shadow-lg">
                                    @if($winnerLogo) 
                                        <img src="{{ Storage::url($winnerLogo) }}" class="w-full h-full object-cover">
                                    @else 
                                        <span class="text-xl grayscale">🛡️</span> 
                                    @endif
                                </div>
                                <h2 class="text-sm font-black text-white leading-tight">{{ $winnerName }}</h2>
                            </div>
                        </div>

                    </div>
                @else
                    <div class="text-center py-20 border border-dashed border-white/10 rounded-2xl bg-white/5">
                        <div class="text-slate-600 mb-2 text-4xl grayscale">🕸️</div>
                        <p class="text-slate-500 text-sm">Bracket belum tersedia.</p>
                    </div>
                @endif
            </div>
        @endif

        @if($activeTab === 'stats')
            <div x-data="{ statTab: 'goals' }" class="pb-12 animate-fade-in-up">
                <div class="flex justify-center gap-2 mb-8">
                    @foreach(['goals' => '⚽ Top Scorer', 'yellow' => '🟨 Yellow Cards', 'red' => '🟥 Red Cards'] as $key => $label)
                        <button @click="statTab = '{{ $key }}'" 
                            :class="statTab === '{{ $key }}' ? 'bg-[#0f172a] text-white border-[rgb(var(--theme-rgb))]' : 'bg-transparent text-slate-500 border-white/10 hover:border-white/30'"
                            class="px-4 py-2 rounded-full text-xs font-bold transition border">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
                @foreach(['goals' => ['data' => $this->topScorers, 'color' => 'text-blue-400', 'label' => 'Goals'], 'yellow' => ['data' => $this->topYellowCards, 'color' => 'text-yellow-400', 'label' => 'Cards'], 'red' => ['data' => $this->topRedCards, 'color' => 'text-red-500', 'label' => 'Cards']] as $key => $config)
                    <div x-show="statTab === '{{ $key }}'" class="space-y-3" style="display: none;">
                        @forelse($config['data'] as $stat)
                            <div class="bg-[#0f172a] p-3 rounded-xl shadow-sm border border-white/5 flex items-center justify-between hover:border-[rgb(var(--theme-rgb))]/30 transition group">
                                <div class="flex items-center gap-4">
                                    <span class="font-mono text-slate-600 font-bold w-6 text-center text-sm">{{ $loop->iteration }}</span>
                                    <div class="w-10 h-10 bg-white/5 rounded-full overflow-hidden shrink-0 border border-white/10">
                                        @if($stat->player->photo) <img src="{{ Storage::url($stat->player->photo) }}" class="w-full h-full object-cover"> @else <span class="text-[10px] flex items-center justify-center h-full text-slate-500">👤</span> @endif
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-white group-hover:text-[rgb(var(--theme-rgb))] transition-colors">{{ $stat->player->name }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase">{{ $stat->player->competitor->name }}</span>
                                    </div>
                                </div>
                                <div class="font-black text-xl {{ $config['color'] }}">{{ $stat->total }}</div>
                            </div>
                        @empty
                            <div class="text-center text-slate-500 py-12 border border-dashed border-white/10 rounded-xl">Belum ada statistik.</div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

<script>
    async function shareContent(title, text, url) {
        if (!title) title = document.title;
        if (!url) url = window.location.href;
        if (navigator.share) {
            try { await navigator.share({ title: title, text: text, url: url }); return; } catch (err) { console.log('Share native dibatalkan'); }
        }
        try {
            if (navigator.clipboard && window.isSecureContext) { await navigator.clipboard.writeText(url); alert('Link berhasil disalin! Siap dibagikan.'); } 
            else { 
                const textArea = document.createElement("textarea"); textArea.value = url; textArea.style.position = "fixed"; textArea.style.left = "-9999px"; 
                document.body.appendChild(textArea); textArea.focus(); textArea.select(); document.execCommand('copy'); document.body.removeChild(textArea); 
                alert('Link berhasil disalin! Siap dibagikan.'); 
            }
        } catch (err) { prompt('Silakan salin link manual:', url); }
    }
</script>