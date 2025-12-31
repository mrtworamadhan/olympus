<?php

namespace App\Filament\Team\Resources\MyCompetitorResource\Widgets;

use App\Models\Competitor;
use App\Models\MatchEvent;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class EventStatsWidget extends TableWidget
{
    public ?Competitor $record = null; 
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Statistik Turnamen')
            ->query(function () {
                if (!$this->record) return MatchEvent::query()->whereRaw('1=0'); 

                return MatchEvent::query()
                    ->select('player_id', DB::raw('count(*) as total'))
                    ->whereHas('game', function($q) {
                        $q->where('tenant_id', $this->record->tenant_id)
                          ->where('event_id', $this->record->event_id);
                    })
                    ->with(['player.competitor']) 
                    ->groupBy('player_id')
                    ->orderByDesc('total')
                    ->limit(10);
            })

            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->label('Tipe Statistik')
                    ->options([
                        'goal' => 'Top Scorer ⚽',
                        'yellow' => 'Kartu Kuning 🟨',
                        'red' => 'Kartu Merah 🟥',
                    ])
                    ->default('goal')
                    ->selectablePlaceholder(false) 
                    ->native(false),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
                    
                Tables\Columns\ImageColumn::make('player.photo')
                    ->circular()
                    ->label('Foto'),

                Tables\Columns\TextColumn::make('player.name')
                    ->label('Nama Pemain')
                    ->description(fn ($record) => $record->player->competitor->name ?? '-'), 

                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->weight('bold')
                    ->alignCenter()
                    ->badge()
                    ->color(function ($livewire) {
                        $filterState = $livewire->tableFilters['event_type']['value'] ?? 'goal';
                        
                        return match($filterState) {
                            'goal' => 'success', 
                            'yellow' => 'warning', 
                            'red' => 'danger',
                            default => 'primary',
                        };
                    }),
            ])
            ->paginated(false);
    }
}