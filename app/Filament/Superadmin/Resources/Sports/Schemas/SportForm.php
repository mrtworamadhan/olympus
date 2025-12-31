<?php

namespace App\Filament\Superadmin\Resources\Sports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Select::make('scoring_type')
                    ->options([
                        'goal_based' => 'Goal Based (Futsal/Bola)',
                        'point_based' => 'Point Based (Basket)',
                        'set_based' => 'Set Based (Voli/Badminton)',
                    ])
                    ->required()
                    ->default('goal_based'),
            ]);
    }
}
