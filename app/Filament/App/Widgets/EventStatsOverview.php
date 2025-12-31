<?php

namespace App\Filament\App\Widgets;

use App\Models\Competitor;
use App\Models\Event;
use App\Models\GameMatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventStatsOverview extends BaseWidget
{
    public $eventId;
    public $categoryId;

    protected function getStats(): array
    {
        if (! $this->eventId) {
            return [];
        }

        $teamQuery = Competitor::where('event_id', $this->eventId);
        if ($this->categoryId) {
            $teamQuery->where('category_id', $this->categoryId)
                        ->where('status', 'approved');
        }
        $totalTeams = $teamQuery->count();

        $matchQuery = GameMatch::whereHas('category', fn($q) => $q->where('event_id', $this->eventId));
        if ($this->categoryId) {
            $matchQuery->where('category_id', $this->categoryId);
        }
        $totalMatches = $matchQuery->count();

        // 3. Status Event
        $event = Event::find($this->eventId);
        $isActive = $event?->is_active ?? false;

        return [
            Stat::make('Total Tim', $totalTeams)
                ->description('Tim Terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Total Pertandingan', $totalMatches)
                ->description('Jadwal & Hasil')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Status Event', $isActive ? 'ON GOING' : 'DRAFT')
                ->description($isActive ? 'Publik' : 'Tersembunyi')
                ->descriptionIcon($isActive ? 'heroicon-m-check-circle' : 'heroicon-m-pause-circle')
                ->color($isActive ? 'success' : 'warning'),
        ];
    }
}