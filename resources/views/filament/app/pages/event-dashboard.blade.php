<x-filament-panels::page>
    
    <div>
        {{ $this->form }}
    </div>

    @if($filter_event_id)

        @livewire(\App\Filament\App\Widgets\EventStatsOverview::class, [
            'eventId' => $filter_event_id,
            'categoryId' => $filter_category_id
        ], key('stats-' . $filter_event_id . '-' . $filter_category_id))
        
        <x-filament::section >
            <x-slot name="heading">
                {{ $this->scheduleAction }}
                {{ $this->standingsAction }}
                {{ $this->statsAction }}
            </x-slot>
            
            <div wire:loading.delay class="p-4 text-center w-full">
                <x-filament::loading-indicator class="h-8 w-8 mx-auto text-primary-500" />
                <span class="text-sm text-gray-500 mt-2">Memuat data...</span>
            </div>

            <div wire:loading.remove>
                @if($activeTab === 'schedule')
                    @livewire('event-dashboard.schedule-table', [
                        'eventId' => $filter_event_id,
                        'categoryId' => $filter_category_id
                    ], key('schedule-' . $filter_event_id . '-' . $filter_category_id))

                @elseif($activeTab === 'standings')
                    @livewire('event-dashboard.standings-table', [
                        'eventId' => $filter_event_id,
                        'categoryId' => $filter_category_id
                    ], key('standings-' . $filter_event_id . '-' . $filter_category_id))
                @elseif($activeTab === 'stats')
        
                        
                        @livewire('event-dashboard.stats-board', [
                            'eventId' => $filter_event_id,
                            'categoryId' => $filter_category_id,
                            'type' => 'goal'
                        ], key('stats-goal-' . $filter_event_id . '-' . $filter_category_id))

                        @livewire('event-dashboard.stats-board', [
                            'eventId' => $filter_event_id,
                            'categoryId' => $filter_category_id,
                            'type' => 'yellow'
                        ], key('stats-yellow-' . $filter_event_id . '-' . $filter_category_id))

                        @livewire('event-dashboard.stats-board', [
                            'eventId' => $filter_event_id,
                            'categoryId' => $filter_category_id,
                            'type' => 'red'
                        ], key('stats-red-' . $filter_event_id . '-' . $filter_category_id))


                @endif
            </div>
        </x-filament::section>

    @else
    <x-filament::section>
        <div
                class="flex flex-col items-center justify-center p-12 text-center bg-white rounded-xl border border-gray-200 shadow-sm dark:bg-gray-900 dark:border-gray-800">
                <div class="mb-4 rounded-full bg-gray-100 dark:bg-gray-800 p-4">
                <div class="flex justify-center mb-4">
                    <x-filament::icon icon="heroicon-o-cursor-arrow-rays" icon-size="sm" />
                </div>

            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                Dashboard Event
            </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">
                    Pilih <strong>Event</strong> dan <strong>Kategori</strong> di atas untuk melihat kontrol panel lengkap.
                </p>
            </div>
        </x-filament::section>


@endif

</x-filament-panels::page>