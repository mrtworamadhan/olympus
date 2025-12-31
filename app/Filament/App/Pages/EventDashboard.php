<?php

namespace App\Filament\App\Pages;

use App\Models\Category;
use App\Models\Competitor;
use App\Models\Event;
use App\Models\GameMatch;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Livewire\Attributes\Url;
use BackedEnum;

class EventDashboard extends Page implements HasForms, HasTable, HasActions
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected string $view = 'filament.app.pages.event-dashboard';

    protected static ?string $navigationLabel = 'Control Center';

    protected static ?string $title = 'Event Control Center';
    
    protected static ?int $navigationSort = 1;

    #[Url]
    public ?string $filter_event_id = null;

    #[Url]
    public ?string $filter_category_id = null;

    public ?array $data = [];

    public string $activeTab = 'schedule';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function scheduleAction(): Action
    {
        return Action::make('schedule')
            ->label('Jadwal & Hasil')
            ->icon('heroicon-m-calendar')
            ->color($this->activeTab === 'schedule' ? 'primary' : 'gray')
            ->outlined($this->activeTab !== 'schedule') 
            ->action(function () {
                $this->activeTab = 'schedule';
            });
    }

    public function standingsAction(): Action
    {
        return Action::make(name: 'standings')
            ->label('Klasemen')
            ->icon('heroicon-m-trophy')
            ->color($this->activeTab === 'standings' ? 'primary' : 'gray')
            ->outlined($this->activeTab !== 'standings')
            ->action(function () {
                $this->activeTab = 'standings';
            });
    }
    
    public function statsAction(): Action
    {
        return Action::make('stats')
            ->label('Statistik Pemain')
            ->icon('heroicon-m-chart-bar')
            ->color($this->activeTab === 'stats' ? 'primary' : 'gray')
            ->outlined($this->activeTab !== 'stats')
            ->action(function () {
                $this->activeTab = 'stats';
            });
    }

    public function mount(): void
    {
        $this->form->fill([
            'filter_event_id' => $this->filter_event_id,
            'filter_category_id' => $this->filter_category_id,
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make()
                    ->schema([
                        Select::make('filter_event_id')
                            ->label('Pilih Event')
                            ->options(Event::pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->filter_event_id = $state;
                                $this->filter_category_id = null;
                                $this->data['filter_category_id'] = null; 
                            })
                            ->required(),

                        Select::make('filter_category_id')
                            ->label('Pilih Kategori')
                            ->options(fn (callable $get) => 
                                $get('filter_event_id') 
                                    ? Category::where('event_id', $get('filter_event_id'))->pluck('name', 'id')
                                    : []
                            )
                            ->searchable()
                            ->live()
                            
                            ->afterStateUpdated(function ($state) {
                                $this->filter_category_id = $state;
                            })
                            ->disabled(fn (callable $get) => !$get('filter_event_id')),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('filter')
                ->label('Tampilkan Data')
                ->icon('heroicon-m-magnifying-glass')
                ->submit('filter'), 
        ];
    }

    public function filter()
    {
        $this->filter_event_id = $this->data['filter_event_id'];
        $this->filter_category_id = $this->data['filter_category_id'];
    }

    public function getStatsProperty(): array
    {
        if (!$this->filter_event_id) {
            return [];
        }

        $teamQuery = Competitor::where('event_id', $this->filter_event_id);
        if ($this->filter_category_id) {
            $teamQuery->where('category_id', $this->filter_category_id)
                        ->where('status', 'approved');
        }
        $totalTeams = $teamQuery->count();

        $matchQuery = GameMatch::whereHas('category', fn($q) => $q->where('event_id', $this->filter_event_id));
        if ($this->filter_category_id) {
            $matchQuery->where('category_id', $this->filter_category_id);
        }
        $totalMatches = $matchQuery->count();

        $event = Event::find($this->filter_event_id);
        $isActive = $event?->is_active ?? false;

        return [
            [
                'label' => 'Total Tim',
                'value' => $totalTeams,
                'desc'  => 'Tim Terdaftar',
                'icon'  => 'heroicon-o-users',
                'color' => 'primary',
            ],
            [
                'label' => 'Total Pertandingan',
                'value' => $totalMatches,
                'desc'  => 'Jadwal / Hasil',
                'icon'  => 'heroicon-o-calendar',
                'color' => 'info',
            ],
            [
                'label' => 'Status Event',
                'value' => $isActive ? 'ON GOING' : 'DRAFT',
                'desc'  => $isActive ? 'Publik' : 'Tersembunyi',
                'icon'  => $isActive ? 'heroicon-o-check-circle' : 'heroicon-o-pause-circle',
                'color' => $isActive ? 'success' : 'warning',
            ],
        ];
    }
}