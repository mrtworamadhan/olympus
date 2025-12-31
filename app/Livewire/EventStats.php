<?php

namespace App\Livewire;

use App\Models\Player;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms; 
use Filament\Forms\Contracts\HasForms; 
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable; 
use Filament\Tables\Contracts\HasTable; 
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

// Pastikan implements HasForms dan HasTable ada
class EventStats extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $record; 
    public $activeTab = 'goal'; 

    public function mount($record)
    {
        $this->record = $record;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
        $this->resetTable(); 
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Player::query()
                    ->whereHas('competitor', function ($q) {
                        $q->where('event_id', $this->record->event_id);
                    })
                    ->withCount(['matchEvents' => function ($q) {
                        $q->where('event_type', $this->activeTab)
                          ->whereHas('game', fn($g) => $g->where('tenant_id', $this->record->tenant_id));
                    }])
                    ->having('match_events_count', '>', 0)
                    ->orderByDesc('match_events_count');
            })
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->label('Nama Pemain')
                    ->description(fn (Player $player) => $player->competitor->name ?? '-')
                    ->searchable(),

                TextColumn::make('match_events_count')
                    ->label('Total')
                    ->badge()
                    ->color(fn () => match($this->activeTab) {
                        'goal' => 'success',
                        'yellow' => 'warning',
                        'red' => 'danger',
                        default => 'primary',
                    })
                    ->alignCenter(),
            ])
            ->headerActions([
                
                Action::make('goal')
                    ->label('Top Scorers')
                    ->icon('heroicon-m-trophy')
                    ->color(fn() => $this->activeTab === 'goal' ? 'primary' : 'gray') 
                    ->action(function () {
                        $this->activeTab = 'goal';
                    }),

                Action::make('yellow')
                    ->label('Kartu Kuning')
                    ->icon('heroicon-m-exclamation-triangle')
                    ->color(fn() => $this->activeTab === 'yellow' ? 'warning' : 'gray')
                    ->action(function () {
                        $this->activeTab = 'yellow';
                    }),

                Action::make('red')
                    ->label('Kartu Merah')
                    ->icon('heroicon-m-no-symbol')
                    ->color(fn() => $this->activeTab === 'red' ? 'danger' : 'gray')
                    ->action(function () {
                        $this->activeTab = 'red';
                    }),
            ])
            
            ->paginated(false);
    }

    public function render(): View
    {
        return view('livewire.event-stats');
    }
}