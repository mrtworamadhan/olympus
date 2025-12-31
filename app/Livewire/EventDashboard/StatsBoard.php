<?php

namespace App\Livewire\EventDashboard;

use App\Models\Player; // Kita query dari Player biar enak di tabel
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StatsBoard extends Component implements HasForms, HasTable, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $eventId;
    public $categoryId;
    public $type; // 'goal', 'yellow', 'red'

    public function mount($eventId, $categoryId = null, $type = 'goal')
    {
        $this->eventId = $eventId;
        $this->categoryId = $categoryId;
        $this->type = $type;
    }

    public function table(Table $table): Table
    {
        // Tentukan Judul dan Warna berdasarkan Type
        $heading = match ($this->type) {
            'yellow' => 'Kartu Kuning',
            'red' => 'Kartu Merah',
            default => 'Top Scorer',
        };

        $icon = match ($this->type) {
            'yellow' => 'heroicon-o-stop',
            'red' => 'heroicon-o-hand-raised',
            default => 'heroicon-o-fire',
        };

        return $table
            ->heading($heading)
            ->description('5 Teratas')
            ->query(function () {
                // QUERY BUILDER STANDAR ELOQUENT
                // Kita ambil Player, tapi kita hitung jumlah event (gol/kartu) mereka
                return Player::query()
                    ->whereHas('competitor', function ($q) {
                        $q->where('event_id', $this->eventId);
                    })
                    // Hitung jumlah Goal/Kartu spesifik di Event & Kategori ini
                    ->withCount(['matchEvents as total_stats' => function ($q) {
                        $q->where('event_type', $this->type)
                          ->whereHas('game', function ($g) {
                              $g->whereHas('category', function ($c) {
                                  $c->where('event_id', $this->eventId);
                              });
                              if ($this->categoryId) {
                                  $g->where('category_id', $this->categoryId);
                              }
                          });
                    }])
                    // Ambil yang totalnya > 0
                    ->having('total_stats', '>', 0)
                    ->orderByDesc('total_stats')
                    ->limit(5);
            })
            ->columns([
                // KOLOM 1: RANKING
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                // KOLOM 2: FOTO & NAMA PEMAIN
                Tables\Columns\ImageColumn::make('photo')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-player.png')), // Ganti jika ada default

                Tables\Columns\TextColumn::make('name')
                    ->label('Pemain')
                    ->description(fn (Player $record) => $record->competitor->name ?? '-')
                    ->weight('bold')
                    ->searchable(),

                // KOLOM 3: TOTAL (Gol/Kartu)
                Tables\Columns\TextColumn::make('total_stats')
                    ->label('Total')
                    ->alignCenter()
                    ->badge()
                    ->color(match ($this->type) {
                        'yellow' => 'warning',
                        'red' => 'danger',
                        default => 'primary', // Goal
                    })
                    ->weight('black')
                    ->size('lg'),
            ])
            ->paginated(false) // Matikan pagination biar compact
            ->striped(); 
    }

    public function render()
    {
        return view('livewire.event-dashboard.stats-board');
    }
}