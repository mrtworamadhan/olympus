<?php

namespace App\Filament\Team\Resources\MyCompetitors\Pages;

use App\Filament\App\Resources\Competitors\RelationManagers\PlayersRelationManager;
use App\Filament\Team\Resources\MyCompetitors\MyCompetitorResource;
use App\Filament\Team\Resources\MyCompetitors\RelationManagers\GamesRelationManager;
use App\Filament\Team\Resources\MyCompetitors\Widgets\EventStatsWidget;
use Filament\Actions\EditAction;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class InfoListMyCompetitor extends ViewRecord
{
    protected static string $resource = MyCompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->schema([
                Tabs::make('Event Dashboard')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Data Tim')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        ImageEntry::make('logo')->circular()->disk('public')->visibility('public'),
                                        TextEntry::make('name')->label('Nama Tim')->size('Large'),
                                        TextEntry::make('category.name')->label('Kategori'),
                                        TextEntry::make('status')
                                            ->badge()
                                            ->color(fn (string $state): string => match ($state) {
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                default => 'warning',
                                            }),
                                    ])->columns(4),
                                    
                            ]),
                        
                        Tab::make('Klasemen')
                            ->icon('heroicon-o-trophy')
                            ->schema([
                                ViewEntry::make('standings_container')
                                    ->columnSpanFull()
                                    ->view('filament.team.resources.my-competitors.infolists.standings-container'),
                            ]),

                        Tab::make('Statistik')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                ViewEntry::make('stats_container')
                                    ->columnSpanFull()
                                    ->view('filament.team.resources.my-competitors.infolists.livewire-container'),
                            ]),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PlayersRelationManager::class,
            
            GamesRelationManager::class, 
        ];
    }
}
