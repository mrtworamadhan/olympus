<?php

namespace App\Filament\Official\Resources\GameMatches\Tables;

use App\Models\GameMatch;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GameMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('homeCompetitor.logo')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->circular(),

                TextColumn::make('homeCompetitor.name')
                    ->label('Home Team')
                    ->weight('bold')
                    ->color('primary'),
                    
                TextColumn::make('vs')
                    ->default('VS')
                    ->description('2-1')
                    ->alignCenter(),

                TextColumn::make('awayCompetitor.name')
                    ->label('Away Team')
                    ->weight('bold')
                    ->color('danger'),

                ImageColumn::make('awayCompetitor.logo')
                    ->label('')
                    ->disk('public')
                    ->visibility('public')
                    ->circular(),

                TextColumn::make('scheduled_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i'),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'gray',
                        'live' => 'danger',
                        'finished' => 'success',
                        default => 'warning',
                    }),
                
                TextColumn::make('venue.name')
                    ->label('Lokasi'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('open_war_room')
                    ->label('Match Room')
                    ->icon('heroicon-o-computer-desktop')
                    ->url(fn (GameMatch $record) => route('match.operator', $record))
                    ->openUrlInNewTab()
                    ->color('success'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
