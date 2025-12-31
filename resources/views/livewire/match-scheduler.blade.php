@php
    $themeColor = $category->event->tenant->primary_color ?? '#fbbf24'; 
    list($r, $g, $b) = sscanf($themeColor, "#%02x%02x%02x");
    $themeRgb = "$r, $g, $b";
@endphp

<div class="min-h-screen bg-[#020617] text-slate-300 font-sans transition-colors selection:bg-[rgb(var(--theme-rgb))] selection:text-black"
     style="--theme-rgb: {{ $themeRgb }};">
        
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center mb-8 sticky top-0 bg-[#020617]/95 backdrop-blur z-30 py-4 border-b border-white/10 px-4 shadow-xl">
        <div class="mb-4 md:mb-0 text-center md:text-left">
            <h1 class="text-2xl font-black text-white uppercase tracking-wide">Pengatur Jadwal</h1>
            <p class="text-sm text-slate-500">Kategori: <span class="text-[rgb(var(--theme-rgb))] font-bold">{{ $category->name }}</span> | Total Match: {{ count($inputs) }}</p>
        </div>
        <div class="flex gap-3 items-center">
            
            <a href="{{ route('drawing.room', $category->id) }}" class="px-4 py-2 text-slate-400 hover:text-white font-bold text-sm transition bg-white/5 rounded-lg border border-white/5 hover:border-white/10">
                &larr; Kembali
            </a>
            
            <button wire:click="saveAll" wire:loading.attr="disabled" class="px-5 py-2.5 bg-[rgb(var(--theme-rgb))] hover:bg-[rgb(var(--theme-rgb))]/80 text-black rounded-lg font-black shadow-[0_0_15px_rgba(var(--theme-rgb),0.3)] flex items-center gap-2 disabled:opacity-50 transition transform active:scale-95">
                <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                <svg wire:loading class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span>SIMPAN PERUBAHAN</span>
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-green-500/10 border-l-4 border-green-500 text-green-400 p-4 mb-8 rounded-r-lg shadow-sm flex justify-between items-center animate-bounce-once">
                <p class="font-bold">{{ session('success') }}</p>
                <span>✅</span>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto space-y-10 px-4 pb-20">
        
        @foreach($gamesByGroup as $groupName => $games)
            <div class="bg-[#0f172a] rounded-xl shadow-lg border border-white/5 overflow-hidden" x-data="{ showTools: false }">
                
                <div class="bg-[#1e293b] px-6 py-4 flex justify-between items-center border-b border-white/5">
                    <h2 class="text-xl font-bold tracking-tight text-white">GRUP <span class="text-[rgb(var(--theme-rgb))]">{{ $groupName }}</span></h2>
                    <button @click="showTools = !showTools" class="text-xs bg-white/5 hover:bg-white/10 border border-white/10 px-3 py-1.5 rounded transition flex items-center gap-1 text-slate-300">
                        <span x-text="showTools ? 'Tutup Tools' : '⚡ Smart Tools'"></span>
                    </button>
                </div>

                <div x-show="showTools" class="bg-[#020617]/50 border-b border-white/5 p-6 grid grid-cols-1 lg:grid-cols-2 gap-8" 
                     style="display: none;"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="space-y-5">
                        <div class="bg-[#1e293b] p-4 rounded-lg border border-white/5 shadow-sm">
                            <label class="block text-xs font-bold text-[rgb(var(--theme-rgb))] uppercase mb-2">Set Venue (Massal):</label>
                            <select wire:change="applyVenueToGroup('{{ $groupName }}', $event.target.value)" 
                                class="w-full text-sm border-white/10 rounded-md bg-[#0f172a] text-white focus:ring-[rgb(var(--theme-rgb))] focus:border-[rgb(var(--theme-rgb))] py-2 px-3">
                                <option value="">-- Pilih Venue --</option>
                                @foreach($venues as $venue) <option value="{{ $venue->id }}">{{ $venue->name }}</option> @endforeach
                            </select>
                        </div>
                        
                        <div class="bg-[#1e293b] p-4 rounded-lg border border-white/5 shadow-sm">
                            <label class="block text-xs font-bold text-[rgb(var(--theme-rgb))] uppercase mb-2">Set Perangkat (Massal):</label>
                            <div class="grid grid-cols-2 gap-3">
                                <select wire:change="applyOfficialsToGroup('{{ $groupName }}', 'referee', $event.target.value)" 
                                    class="w-full text-xs border-white/10 rounded-md bg-[#0f172a] text-white py-2 px-3">
                                    <option value="">- Set Wasit -</option>
                                    @foreach($referees as $ref) <option value="{{ $ref->id }}">{{ $ref->name }}</option> @endforeach
                                </select>
                                <select wire:change="applyOfficialsToGroup('{{ $groupName }}', 'operator', $event.target.value)" 
                                    class="w-full text-xs border-white/10 rounded-md bg-[#0f172a] text-white py-2 px-3">
                                    <option value="">- Set Operator -</option>
                                    @foreach($operators as $op) <option value="{{ $op->id }}">{{ $op->name }}</option> @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ date: '{{ now()->addDay()->format('Y-m-d') }}', time: '08:00', duration: 30, breakTime: 5 }" 
                         class="bg-[#1e293b] p-4 rounded-lg border border-white/5 shadow-sm h-full flex flex-col justify-between">
                        <div>
                            <label class="block text-xs font-bold text-[rgb(var(--theme-rgb))] uppercase mb-3 border-b border-white/5 pb-2">Generator Waktu</label>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div><span class="text-[10px] text-slate-500 block mb-1">Tanggal Mulai</span><input type="date" x-model="date" class="w-full text-sm border-white/10 rounded-md bg-[#0f172a] text-white py-1.5 px-3"></div>
                                <div><span class="text-[10px] text-slate-500 block mb-1">Jam Mulai</span><input type="time" x-model="time" class="w-full text-sm border-white/10 rounded-md bg-[#0f172a] text-white py-1.5 px-3"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div><span class="text-[10px] text-slate-500 block mb-1">Durasi (Menit)</span><input type="number" x-model="duration" class="w-full text-sm border-white/10 rounded-md bg-[#0f172a] text-white py-1.5 px-3"></div>
                                <div><span class="text-[10px] text-slate-500 block mb-1">Jeda (Menit)</span><input type="number" x-model="breakTime" class="w-full text-sm border-white/10 rounded-md bg-[#0f172a] text-white py-1.5 px-3"></div>
                            </div>
                        </div>
                        <button wire:click="applyTimeSequence('{{ $groupName }}', date, time, duration, breakTime)" class="w-full bg-[rgb(var(--theme-rgb))] hover:bg-[rgb(var(--theme-rgb))]/80 text-black py-2.5 rounded-md text-sm font-bold transition">TERAPKAN KE SEMUA</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-[#020617] border-b border-white/5">
                        <tr>
                            <th class="pl-4 py-4 w-[5%] text-center">#</th>
                            <th class="px-4 py-4 w-[45%] text-center">Match (Pertandingan)</th> 
                            <th class="px-4 py-4 w-[15%] text-center">Lokasi</th> 
                            <th class="px-4 py-4 w-[20%] text-center">Perangkat</th>
                            <th class="px-4 py-4 w-[15%] text-center">Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($games as $game)
                            <tr class="hover:bg-white/5 transition group">
                                <td class="pl-4 py-4 text-center align-middle">
                                    <div class="flex flex-col items-center justify-center gap-1 opacity-20 group-hover:opacity-100 transition">
                                        <button wire:click="moveMatch({{ $game->id }}, 'up')" class="p-1 rounded hover:bg-white/10 text-slate-400 hover:text-[rgb(var(--theme-rgb))] transition"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg></button>
                                        <button wire:click="moveMatch({{ $game->id }}, 'down')" class="p-1 rounded hover:bg-white/10 text-slate-400 hover:text-[rgb(var(--theme-rgb))] transition"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg></button>
                                    </div>
                                </td>
                                <td class="px-4 py-4 align-middle">
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="flex flex-col items-center justify-center text-center gap-1">
                                            @if($game->homeCompetitor->logo) <img src="{{ asset('storage/' . $game->homeCompetitor->logo) }}" class="w-10 h-10 object-contain bg-white/5 rounded-full p-0.5 border border-white/10"> @else <div class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-xs text-slate-500">?</div> @endif
                                            <span class="font-bold text-slate-300 text-xs leading-tight line-clamp-2 w-full">{{ $game->homeCompetitor->name }}</span>
                                        </div>
                                        <div class="flex flex-col items-center justify-center"><span class="text-[10px] font-black text-slate-600 bg-white/5 px-2 py-0.5 rounded-full">VS</span></div>
                                        <div class="flex flex-col items-center justify-center text-center gap-1">
                                            @if($game->awayCompetitor->logo) <img src="{{ asset('storage/' . $game->awayCompetitor->logo) }}" class="w-10 h-10 object-contain bg-white/5 rounded-full p-0.5 border border-white/10"> @else <div class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center text-xs text-slate-500">?</div> @endif
                                            <span class="font-bold text-slate-300 text-xs leading-tight line-clamp-2 w-full">{{ $game->awayCompetitor->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-4 align-middle">
                                    <select wire:model="inputs.{{ $game->id }}.venue_id" class="w-full text-xs border-white/10 rounded-md bg-[#0f172a] text-slate-300 focus:ring-[rgb(var(--theme-rgb))] py-1.5 px-2">
                                        <option value="">Lokasi...</option>
                                        @foreach($venues as $venue) <option value="{{ $venue->id }}">{{ $venue->name }}</option> @endforeach
                                    </select>
                                </td>
                                <td class="px-2 py-4 align-middle space-y-2">
                                    <select wire:model="inputs.{{ $game->id }}.referee_id" class="w-full text-[11px] border-white/10 rounded-md bg-[#0f172a] text-slate-300 focus:ring-[rgb(var(--theme-rgb))] py-1.5 px-2"><option value="">- Wasit -</option>@foreach($referees as $ref) <option value="{{ $ref->id }}">{{ $ref->name }}</option> @endforeach</select>
                                    <select wire:model="inputs.{{ $game->id }}.operator_id" class="w-full text-[11px] border-white/10 rounded-md bg-[#0f172a] text-slate-300 focus:ring-[rgb(var(--theme-rgb))] py-1.5 px-2"><option value="">- Operator -</option>@foreach($operators as $op) <option value="{{ $op->id }}">{{ $op->name }}</option> @endforeach</select>
                                </td>
                                <td class="px-2 py-4 align-middle">
                                    <div class="flex flex-col gap-1.5">
                                        <input type="date" wire:model="inputs.{{ $game->id }}.date" class="w-full text-xs border-white/10 rounded-md bg-[#0f172a] text-slate-300 focus:ring-[rgb(var(--theme-rgb))] py-1 px-2">
                                        <input type="time" wire:model="inputs.{{ $game->id }}.time" class="w-full text-xs border-white/10 rounded-md bg-[#0f172a] text-slate-300 focus:ring-[rgb(var(--theme-rgb))] py-1 px-2">
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        @endforeach

        @if(count($knockoutStages) > 0)
            <div class="border-t-4 border-dashed border-white/5 my-16 relative text-center">
                <span class="bg-[#020617] px-6 py-1 text-slate-500 font-bold uppercase tracking-widest absolute -top-4 left-1/2 -translate-x-1/2">
                    Bagan Fase Gugur (Bracket)
                </span>
            </div>

            <div class="overflow-x-auto px-6 pb-20 scrollbar-hide">
                <div class="flex gap-0 min-w-max">
                    
                    @foreach($knockoutStages as $stageName => $games)
                        @php
                            $stageIndex = $loop->index; 
                            
                            $heightMultiplier = 100 * pow(2, $stageIndex);
                            
                            $pixelGap = 16 * pow(2, $stageIndex); 
                        @endphp

                        <div class="flex flex-col relative w-[300px]"> 
                            
                            <div class="text-center mb-6 h-10 flex-shrink-0"> 
                                <h3 class="text-xs font-black uppercase tracking-wider bg-[rgb(var(--theme-rgb))]/10 text-[rgb(var(--theme-rgb))] px-4 py-1.5 rounded-full inline-block shadow-sm border border-[rgb(var(--theme-rgb))]/20">
                                    {{ $stageName }}
                                </h3>
                            </div>

                            <div class="flex flex-col flex-1 justify-around relative px-4">
                                
                                @foreach($games as $game)
                                    <div class="relative py-2 w-full"> 
                                        
                                        <div class="relative bg-[#0f172a] border border-white/10 rounded-xl shadow-lg hover:border-[rgb(var(--theme-rgb))]/50 transition p-4 group z-20">
                                            
                                            <div class="flex justify-between items-center mb-3 border-b border-white/5 pb-2">
                                                <span class="text-[9px] font-bold text-slate-500 uppercase tracking-wider">#{{ $game->id }}</span>
                                                <span class="text-[9px] font-bold px-2 py-0.5 rounded {{ $game->status == 'scheduled' ? 'bg-blue-500/10 text-blue-400' : 'bg-green-500/10 text-green-400' }}">
                                                    {{ $game->status == 'scheduled' ? 'VS' : 'FT' }}
                                                </span>
                                            </div>

                                            <div class="space-y-2 mb-3">
                                                <div class="flex justify-between items-center bg-[#1e293b] p-2 rounded-lg border border-white/5 {{ $game->home_score > $game->away_score ? 'border-l-2 border-l-green-500' : '' }}">
                                                    <span class="font-bold text-xs text-slate-300 truncate w-32">
                                                        {{ $game->homeCompetitor->name ?? ($game->meta_data['placeholder_home'] ?? 'TBA') }}
                                                    </span>
                                                    <span class="font-bold text-white text-xs">{{ $game->status != 'scheduled' ? $game->home_score : '' }}</span>
                                                </div>
                                                <div class="flex justify-between items-center bg-[#1e293b] p-2 rounded-lg border border-white/5 {{ $game->away_score > $game->home_score ? 'border-l-2 border-l-green-500' : '' }}">
                                                    <span class="font-bold text-xs text-slate-300 truncate w-32">
                                                        {{ $game->awayCompetitor->name ?? ($game->meta_data['placeholder_away'] ?? 'TBA') }}
                                                    </span>
                                                    <span class="font-bold text-white text-xs">{{ $game->status != 'scheduled' ? $game->away_score : '' }}</span>
                                                </div>
                                            </div>

                                            <div class="flex gap-1">
                                                <input type="date" wire:model.live="inputs.{{ $game->id }}.date" class="w-1/2 bg-[#020617] border-white/10 text-[9px] text-slate-400 rounded px-1 py-1">
                                                <input type="time" wire:model.live="inputs.{{ $game->id }}.time" class="w-1/2 bg-[#020617] border-white/10 text-[9px] text-slate-400 rounded px-1 py-1">
                                            </div>
                                        </div>

                                        @if(!$loop->parent->last) 
                                            
                                            <div class="absolute right-[-20px] top-1/2 w-[20px] h-[2px] bg-white/10 z-10"></div>

                                            @if($loop->iteration % 2 != 0)
                                                <div class="absolute right-[-22px] top-1/2 w-[2px] border-r-2 border-white/10"
                                                     style="height: calc({{ $heightMultiplier }}%); transform-origin: top;">
                                                </div>
                                            @endif
                                        @else
                                            <div class="absolute right-[-40px] top-1/2 w-[40px] h-[2px] bg-[rgb(var(--theme-rgb))] z-10"></div>
                                        @endif

                                        @if(!$loop->parent->first)
                                            <div class="absolute left-[-16px] top-1/2 w-[16px] h-[2px] bg-white/10 z-10"></div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <div class="flex flex-col justify-center relative w-[250px] pl-6">
                        <div class="relative bg-gradient-to-b from-yellow-600/20 to-yellow-900/10 border border-yellow-500/30 rounded-xl shadow-[0_0_30px_rgba(234,179,8,0.1)] p-6 text-center animate-fade-in-up">
                            
                            <div class="absolute -top-6 left-1/2 -translate-x-1/2">
                                <span class="text-4xl drop-shadow-lg">🏆</span>
                            </div>
                            
                            <h3 class="mt-4 text-xs font-bold text-yellow-500 uppercase tracking-widest mb-2">CHAMPION</h3>
                            
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

                            <div class="w-20 h-20 bg-[#0f172a] rounded-full mx-auto mb-3 border-2 border-yellow-500/50 flex items-center justify-center overflow-hidden shadow-lg">
                                @if($winnerLogo)
                                    <img src="{{ Storage::url($winnerLogo) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl grayscale">🛡️</span>
                                @endif
                            </div>
                            
                            <h2 class="text-lg font-black text-white leading-tight">
                                {{ $winnerName }}
                            </h2>
                        </div>
                    </div>

                </div>
            </div>
        @endif

    </div>
</div>