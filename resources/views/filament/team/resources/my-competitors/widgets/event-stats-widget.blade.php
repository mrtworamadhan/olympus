<x-filament::widget>
    <x-filament::section>
        <button 
            wire:click="setStatTab('goal')"
            class="text-sm font-bold {{ $activeStatTab === 'goal'
                ? 'text-primary-600 border-b-2 border-primary-600'
                : 'text-gray-500' }}"
        >
            Top Scorers (Goals)
        </button>

        <button 
            wire:click="setStatTab('yellow_card')"
            class="text-sm font-bold {{ $activeStatTab === 'yellow_card'
                ? 'text-primary-600 border-b-2 border-primary-600'
                : 'text-gray-500' }}"
        >
            Kartu Kuning
        </button>

        <button 
            wire:click="setStatTab('red_card')"
            class="text-sm font-bold {{ $activeStatTab === 'red_card'
                ? 'text-primary-600 border-b-2 border-primary-600'
                : 'text-gray-500' }}"
        >
            Kartu Merah
        </button>


        {{-- Content List --}}
        <div class="space-y-2">
            @forelse($stats as $stat)
                <div class="flex items-center justify-between p-2 bg-gray-50 rounded hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center gap-3">
                        {{-- Avatar Player (Opsional) --}}
                        <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-bold">
                            {{ substr($stat->player->name ?? 'U', 0, 1) }}
                        </div>
                        
                        <div class="flex flex-col">
                            <span class="font-medium text-sm">{{ $stat->player->name ?? 'Unknown Player' }}</span>
                            <span class="text-xs text-gray-500">{{ $stat->player->competitor->name ?? '-' }}</span>
                        </div>
                    </div>
                    
                    <span class="font-bold text-lg text-primary-600">
                        {{ $stat->total }}
                    </span>
                </div>
            @empty
                <div class="text-center text-gray-400 py-4 text-sm">
                    Belum ada data statistik untuk kategori ini.
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament::widget>