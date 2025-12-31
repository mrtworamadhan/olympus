<?php

namespace App\Filament\Team\Resources\MasterClubs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MasterClubForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tim')
                    ->columnSpanFull()
                    ->components([
                        Grid::make()
                            ->columns(1)
                            ->components([
                                Hidden::make('user_id')
                                    ->default(auth()->id()),
                                FileUpload::make('logo')
                                    ->directory('teams-logo')
                                    ->disk('public')
                                    ->alignCenter()
                                    ->avatar()
                                    ->image(),
                                TextInput::make('name')
                                    ->label('Club Name')
                                    ->required(),
                            ]),
                        // Grid::make()
                        //     ->columns(4)
                        //     ->components([
                                
                        //         TextInput::make('name')
                        //             ->label('Nama Tim')
                        //             ->required(),
                        //         TextInput::make('short_name')
                        //             ->label('Singkatan (3 Huruf)')
                        //             ->maxLength(3)
                        //             ->placeholder('BFC'),
                        //         TextInput::make('group_name')
                        //             ->label('Grup')
                        //             ->default('A'),
                        //     ]),

                    ]),
            ]);
    }
}
