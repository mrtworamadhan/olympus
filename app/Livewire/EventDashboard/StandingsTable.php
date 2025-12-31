<?php

namespace App\Livewire\EventDashboard;

use App\Models\Category;
use App\Models\Competitor;
use App\Models\Event; // Pastikan import Event model
use App\Services\StandingsService; // Pastikan Service ada
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Foundation\Testing\Concerns\InteractsWithTestCaseLifecycle;
use Livewire\Component;

class StandingsTable extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $eventId;
    public $categoryId;
    
    public array $teamStats = [];
    public array $sortedIds = [];

    public function mount($eventId, $categoryId = null)
    {
        $this->eventId = $eventId;
        $this->categoryId = $categoryId;
        
        $this->calculateStandings();
    }

    public function calculateStandings()
    {
        $service = new StandingsService();
        $category = Category::find($this->categoryId);
        
        if (!$category) return;

        $rawStandings = $service->getStandings($category);

        $this->sortedIds = [];
        $this->teamStats = [];

        foreach ($rawStandings as $groupName => $teams) {
            foreach ($teams as $team) {
                if ($this->categoryId && $team->category_id != $this->categoryId) {
                    continue;
                }

                $this->sortedIds[] = $team->id;
                $this->teamStats[$team->id] = $team->stats;
            }
        }
    }

    public function table(Table $table): Table
    {
        $showDrawingButton = false;
        $emptyStateHeading = 'Belum Ada Data Klasemen';
        $emptyStateDesc = 'Belum ada pertandingan yang dimainkan.';

        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            
            if ($category && in_array($category->format_type, ['hybrid', 'group_stage'])) {
                
                $hasUngroupedTeams = Competitor::where('category_id', $this->categoryId)->where('status', 'approved')
                    ->where(fn($q) => $q->whereNull('group_name')->orWhere('group_name', ''))
                    ->exists();

                if ($hasUngroupedTeams) {
                    $showDrawingButton = true;
                    $emptyStateHeading = 'Pembagian Grup Belum Selesai';
                    $emptyStateDesc = 'Kategori ini menggunakan sistem Grup, namun tim belum dibagi ke dalam grup.';
                    
                    $this->sortedIds = []; 
                }
            } elseif ($category && $category->format_type === 'knockout') {
                $emptyStateHeading = 'Sistem Gugur (Knockout)';
                $emptyStateDesc = 'Klasemen tidak tersedia untuk format sistem gugur murni. Silakan cek bagan pertandingan.';
                $this->sortedIds = [];
            }
        }

        return $table
            ->query(function () {
                if (empty($this->sortedIds)) {
                    return Competitor::query()->whereRaw('1 = 0'); 
                }
                
                return Competitor::query()
                    ->whereIn('id', $this->sortedIds)
                    ->where('status','approved')
                    ->orderByRaw('FIELD(id, ' . implode(',', $this->sortedIds) . ')');
            })
            ->groups([
                Tables\Grouping\Group::make('group_name')
                    ->label('Grup')
                    ->collapsible(false),
            ])
            ->defaultGroup('group_name')
            ->columns([
                Tables\Columns\TextColumn::make('index')->label('#')->rowIndex(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Tim')
                    ->weight('bold')
                    ->description(fn (Competitor $record) => $record->category->name ?? '-'),

                Tables\Columns\TextColumn::make('played')->label('P')->alignCenter()
                    ->state(fn ($record) => $this->teamStats[$record->id]['played'] ?? 0),
                
                Tables\Columns\TextColumn::make('won')->label('W')->alignCenter()->color('success')
                    ->state(fn ($record) => $this->teamStats[$record->id]['won'] ?? 0),
                
                Tables\Columns\TextColumn::make('drawn')->label('D')->alignCenter()->color('gray')
                    ->state(fn ($record) => $this->teamStats[$record->id]['drawn'] ?? 0),
                
                Tables\Columns\TextColumn::make('lost')->label('L')->alignCenter()->color('danger')
                    ->state(fn ($record) => $this->teamStats[$record->id]['lost'] ?? 0),
                
                Tables\Columns\TextColumn::make('goal_diff')->label('GD')->alignCenter()
                    ->state(fn ($record) => $this->teamStats[$record->id]['goal_diff'] ?? 0)
                    ->formatStateUsing(fn ($state) => $state > 0 ? "+$state" : $state),
                
                Tables\Columns\TextColumn::make('points')->label('Pts')->alignCenter()
                    ->weight('bold')->color('primary')
                    ->state(fn ($record) => $this->teamStats[$record->id]['points'] ?? 0),
            ])
            ->paginated(false)
            ->emptyStateHeading($emptyStateHeading)
            ->emptyStateDescription($emptyStateDesc)
            ->emptyStateIcon($showDrawingButton ? 'heroicon-o-user-group' : 'heroicon-o-table-cells')
            ->emptyStateActions([
                Action::make('drawing')
                    ->label('Buka Ruang Undian (Drawing)')
                    ->icon('heroicon-o-arrows-right-left')
                    ->url(fn() => route('drawing.room', ['category' => $this->categoryId])) // URL Dinamis
                    ->openUrlInNewTab()
                    ->button()
                    ->visible($showDrawingButton),
            ]);
    }

    public function render()
    {
        return view('livewire.event-dashboard.standings-table');
    }
}