<?php

namespace App\Livewire;

use App\Models\Competitor;
use App\Services\StandingsService;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EventStandings extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $record;

    public array $teamStats = []; 
    public array $sortedIds = [];

    public function mount($record, StandingsService $service)
    {
        $this->record = $record;
        
        $rawStandings = $service->getStandings($this->record->category);

        foreach ($rawStandings as $groupName => $teams) {
            foreach ($teams as $team) {
                $this->sortedIds[] = $team->id;
                
                $this->teamStats[$team->id] = $team->stats;
            }
        }
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (empty($this->sortedIds)) {
                    return Competitor::query()->where('id', 0);
                }

                return Competitor::query()
                    ->whereIn('id', $this->sortedIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $this->sortedIds) . ')');
            })
            
            ->groups([
                Tables\Grouping\Group::make('group_name')
                    ->label('Grup')
                    ->collapsible(false),
            ])
            ->defaultGroup('group_name')
            
            ->columns([
                
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Team')
                    ->weight('bold')
                    ->description(function (Competitor $record) {
                        return $record->id === $this->record->id ? 'Your Club' : null;
                    })
                    ->color(fn (Competitor $record) => $record->id === $this->record->id ? 'primary' : null),

                
                Tables\Columns\TextColumn::make('played')
                    ->label('P')
                    ->alignCenter()
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['played'] ?? 0),

                Tables\Columns\TextColumn::make('won')
                    ->label('W')
                    ->alignCenter()
                    ->color('success')
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['won'] ?? 0),

                Tables\Columns\TextColumn::make('drawn')
                    ->label('D')
                    ->alignCenter()
                    ->color('gray')
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['drawn'] ?? 0),

                Tables\Columns\TextColumn::make('lost')
                    ->label('L')
                    ->alignCenter()
                    ->color('danger')
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['lost'] ?? 0),
                
                Tables\Columns\TextColumn::make('goals_for')
                    ->label('GF')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['goals_for'] ?? 0),

                Tables\Columns\TextColumn::make('goals_against')
                    ->label('GA')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['goals_against'] ?? 0),

                Tables\Columns\TextColumn::make('goal_diff')
                    ->label('GD')
                    ->alignCenter()
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['goal_diff'] ?? 0)
                    ->color(function (Competitor $record) {
                        $gd = $this->teamStats[$record->id]['goal_diff'] ?? 0;
                        return $gd > 0 ? 'success' : ($gd < 0 ? 'danger' : 'gray');
                    })
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+$state" : $state),

                Tables\Columns\TextColumn::make('points')
                    ->label('Pts')
                    ->alignCenter()
                    ->weight('bold')
                    ->size('lg')
                    ->color('primary')
                    ->state(fn (Competitor $record) => $this->teamStats[$record->id]['points'] ?? 0),
            ])
            ->paginated(false)
            ->striped();
    }

    public function render(): View
    {
        return view('livewire.event-standings');
    }
}