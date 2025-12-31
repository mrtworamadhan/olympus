<?php

namespace App\Livewire\EventDashboard;

use App\Models\GameMatch;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ScheduleTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $eventId;
    public $categoryId;

    public function mount($eventId, $categoryId = null)
    {
        $this->eventId = $eventId;
        $this->categoryId = $categoryId;
    }

    public function table(Table $table): Table
    {
        $showDrawingButton = !empty($this->categoryId);
        
        $emptyDescription = $showDrawingButton 
            ? 'Jadwal pertandingan belum dibuat. Silakan ke Ruang Undian untuk generate jadwal otomatis.' 
            : 'Silakan pilih Filter Kategori di atas untuk melihat atau membuat jadwal.';

        return $table
            ->query(function () {
                $query = GameMatch::query()
                    ->whereHas('category', fn($q) => $q->where('event_id', $this->eventId));

                if ($this->categoryId) {
                    $query->where('category_id', $this->categoryId);
                }

                return $query->orderBy('scheduled_at', 'asc');
            })
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label('Waktu')
                    ->dateTime('d M, H:i')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->hidden(fn() => $this->categoryId !== null),

                TextColumn::make('homeCompetitor.name')
                    ->label('Home')
                    ->weight('bold')
                    ->alignRight()
                    ->color('primary'),

                TextColumn::make('score')
                    ->label('Skor')
                    ->alignCenter()
                    ->state(function (GameMatch $record) {
                        if ($record->status === 'scheduled') return 'VS';
                        return "{$record->home_score} - {$record->away_score}";
                    })
                    ->badge()
                    ->color(fn ($state) => $state === 'VS' ? 'gray' : 'success'),

                TextColumn::make('awayCompetitor.name')
                    ->label('Away')
                    ->weight('bold')
                    ->alignLeft()
                    ->color('danger'),
                
                TextColumn::make('venue.name')
                    ->label('Lokasi')
                    ->badge(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'gray',
                        'live' => 'danger',
                        'finished' => 'success',
                        default => 'warning',
                    }),

                TextColumn::make('referee.name')
                    ->label('Perangkat')
                    ->icon('mdi-whistle-outline')
                    ->color('warning')
                    ->weight('bold')
                    ->placeholder('Belum ditentukan')
                    ->description(function (GameMatch $record) {
                        return $record->operator 
                            ? 'Op: ' . $record->operator->name 
                            : null;
                    }),
            ])
            ->headerActions([
                Action::make('edit_schedule')
                    ->label('Atur Jadwal (Scheduler)')
                    ->icon('heroicon-o-calendar-days')                    
                    ->url(fn() => $this->categoryId 
                        ? route('match.scheduler', ['category' => $this->categoryId]) 
                        : null
                    )
                    ->openUrlInNewTab()                    
                    ->visible(fn() => !empty($this->categoryId)), 
            ])
            ->paginated(10)
            ->emptyStateHeading('Jadwal Pertandingan Kosong')
            ->emptyStateDescription($emptyDescription)
            ->emptyStateIcon('heroicon-o-calendar')
            ->emptyStateActions([
                Action::make('drawing')
                    ->label('Buka Ruang Undian & Generate Jadwal')
                    ->icon('heroicon-o-arrows-right-left')
                    ->url(fn() => route('drawing.room', ['category' => $this->categoryId])) 
                    ->openUrlInNewTab()
                    ->button()
                    ->visible($showDrawingButton),
            ]);

    }

    public function render()
    {
        return view('livewire.event-dashboard.schedule-table');
    }
}