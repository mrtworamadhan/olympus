<?php

namespace App\Filament\App\Resources\GameMatches\Schemas;

use App\Models\Category;
use App\Models\Competitor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class GameMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Setup Pertandingan')
                    ->components([
                        Select::make('category_id')                            
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->required()
                            ->live() 
                            ->afterStateUpdated(function (Set $set) {
                                $set('home_competitor_id', null);
                                $set('away_competitor_id', null);
                            }),

                        Select::make('venue_id')
                            ->label('Lapangan')
                            ->relationship('venue', 'name')
                            ->required(),

                        DateTimePicker::make('scheduled_at')
                            ->label('Waktu Kick-off')
                            ->required()
                            ->seconds(false),
                    ])->columns(3),

                Section::make('Tim Bertanding')
                    ->description('Pastikan pilih kategori di atas dulu.')
                    ->components([
                        Select::make('home_competitor_id')
                            ->label('Tim Tuan Rumah (Home)')
                            ->options(function (Get $get) {
                                $categoryId = $get('category_id');
                                if (! $categoryId) {
                                    return [];
                                }
                                
                                $event = Category::find($categoryId)?->event;
                                
                                if (! $event) return [];

                                return Competitor::where('event_id', $event->id)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required(),

                        Select::make('away_competitor_id')
                            ->label('Tim Tamu (Away)')
                            ->options(function (Get $get) {
                                $categoryId = $get('category_id');
                                if (! $categoryId) return [];
                                $event = Category::find($categoryId)?->event;
                                if (! $event) return [];

                                return Competitor::where('event_id', $event->id)
                                    ->pluck('name', 'id');
                            })
                            ->searchable()
                            ->required()
                            ->different('home_competitor_id'),
                    ])->columns(2),
                
                Section::make('Perangkat Pertandingan')
                    ->components([
                        Select::make('referee_id')
                            ->label('Wasit Utama')
                            ->relationship('referee', 'name', function ($query) {
                                return $query->where('role', 'referee');
                            })
                            ->searchable()
                            ->preload(),

                        Select::make('operator_id')
                            ->label('Operator (Petugas Skor)')
                            ->relationship('operator', 'name', function ($query) {
                                return $query->where('role', 'operator');
                            })
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Section::make('Status Awal')
                    ->collapsed() 
                    ->components([
                        Select::make('status')
                            ->options([
                                'scheduled' => 'Terjadwal',
                                'processing' => 'Pemanasan',
                                'live' => 'Sedang Main',
                                'finished' => 'Selesai',
                                'walkover' => 'Menang WO',
                            ])
                            ->default('scheduled')
                            ->required(),
                    ]),
        ]);
    }
}
