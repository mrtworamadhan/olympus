<?php

namespace App\Filament\App\Resources\Categories\Schemas;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use App\Models\Event;
use App\Models\Sport;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Dasar')
                    ->components([
                        Grid::Make(3)
                            ->components([
                                Select::make('event_id')
                                    ->label('Pilih Event')
                                    ->options(Event::all()->pluck('name', 'id'))
                                    ->required(),
                                Select::make('sport_id')
                                    ->label('Cabang Olahraga')
                                    ->options(Sport::all()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive(), 
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->placeholder('Contoh: Futsal Putra U-23')
                                    ->required(),
                            ])
                        
                    ])->columnSpanFull(),

                Section::make('Konfigurasi Aturan (Rule Engine)')
                    ->description('Aturan ini akan berlaku untuk semua pertandingan di kategori ini.')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('rules_settings.period_duration')
                                    ->label('Durasi per Babak (Menit)')
                                    ->numeric()
                                    ->default(15)
                                    ->required(),
                                Toggle::make('rules_settings.is_stop_clock')
                                    ->label('Gunakan Waktu Bersih (Stop Clock)?')
                                    ->default(false),

                                TextInput::make('rules_settings.max_fouls')
                                    ->label('Batas Foul per Babak')
                                    ->numeric()
                                    ->default(5)
                                    ->helperText('Jika lebih, lawan dapat penalti/second penalty.'),

                                TextInput::make('rules_settings.points_win')
                                    ->label('Poin Menang')
                                    ->numeric()
                                    ->default(3),
                                TextInput::make('rules_settings.points_draw')
                                    ->label('Poin Seri')
                                    ->numeric()
                                    ->default(1),
                                TextInput::make('rules_settings.points_loss')
                                    ->label('Poin Kalah')
                                    ->numeric()
                                    ->default(0),
                            ]),
                    ])->columnSpanFull(),
                    
                Section::make('Syarat & Aturan Kategori')
                    ->components([
                        Grid::make(2)
                            ->components([
                                TextInput::make('age_limit_helper')
                                    ->label('Batasan Umur (U-XX)')
                                    ->numeric()
                                    ->placeholder('Contoh: 15 untuk U-15')
                                    ->dehydrated(false)
                                    ->live() 
                                    ->afterStateUpdated(function (Get $get, Set $set, ?string $state) {
                                        if ($state) {
                                            $year = now()->year - (int)$state;
                                            $cutoffDate = Carbon::create($year, 1, 1)->format('Y-m-d');
                                            $set('min_date_of_birth', $cutoffDate);
                                        }
                                    }),

                                DatePicker::make('min_date_of_birth')
                                    ->label('Minimal Tanggal Lahir')
                                    ->helperText('Pemain harus lahir SETELAH tanggal ini.')
                                    ->required()
                                    ->native(false) 
                                    ->displayFormat('d F Y'),
                            ])          
                    ])->columnSpanFull(),

                Section::make('Format Turnamen')
                    ->components([
                        Grid::make(3)
                            ->components([
                                Select::make('format_type')
                                    ->options([
                                        'hybrid' => 'Fase Grup + Gugur (Semi Group)',
                                        'group_stage' => 'Liga (Klasemen Saja)',
                                        'knockout' => 'Sistem Gugur Murni',
                                    ])
                                    ->reactive()
                                    ->required(),

                                TextInput::make('total_groups')
                                    ->label('Jumlah Grup')
                                    ->numeric()
                                    ->default(4)
                                    ->hidden(fn (Get $get) => $get('format_type') === 'knockout'),

                                TextInput::make('teams_passing_per_group')
                                    ->label('Jatah Lolos per Grup')
                                    ->numeric()
                                    ->default(2)
                                    ->helperText('Contoh: 2 (Juara & Runner Up)')
                                    ->hidden(fn (Get $get) => $get('format_type') === 'knockout'),
                            ])
                        
                    ])->columnSpanFull(),
            ]);
    }
}
