<?php

namespace App\Filament\Team\Resources\GameMatches\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GameMatchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Select::make('venue_id')
                    ->relationship('venue', 'name'),
                Select::make('home_competitor_id')
                    ->relationship('homeCompetitor', 'name'),
                Select::make('away_competitor_id')
                    ->relationship('awayCompetitor', 'name'),
                DateTimePicker::make('scheduled_at')
                    ->required(),
                DateTimePicker::make('started_at'),
                DateTimePicker::make('ended_at'),
                TextInput::make('home_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('away_score')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options([
                            'scheduled' => 'Scheduled',
                            'processing' => 'Processing',
                            'live' => 'Live',
                            'break' => 'Break',
                            'finished' => 'Finished',
                            'walkover' => 'Walkover',
                            'cancelled' => 'Cancelled',
                        ])
                    ->default('scheduled')
                    ->required(),
                TextInput::make('meta_data'),
                TextInput::make('referee_id')
                    ->numeric(),
                TextInput::make('operator_id')
                    ->numeric(),
            ]);
    }
}
