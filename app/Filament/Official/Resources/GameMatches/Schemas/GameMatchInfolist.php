<?php

namespace App\Filament\Official\Resources\GameMatches\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class GameMatchInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('venue.name')
                    ->label('Venue')
                    ->placeholder('-'),
                TextEntry::make('homeCompetitor.name')
                    ->label('Home competitor')
                    ->placeholder('-'),
                TextEntry::make('awayCompetitor.name')
                    ->label('Away competitor')
                    ->placeholder('-'),
                TextEntry::make('scheduled_at')
                    ->dateTime(),
                TextEntry::make('started_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('ended_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('home_score')
                    ->numeric(),
                TextEntry::make('away_score')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('referee_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('operator_id')
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }
}
