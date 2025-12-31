<?php

namespace App\Filament\App\Resources\Competitors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class CompetitorForm
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
                                FileUpload::make('logo')
                                ->directory('teams-logo')
                                ->disk('public')
                                ->avatar()
                                ->alignCenter()
                                ->image(),
                            ]),
                        Grid::make()
                            ->columns(3)
                            ->components([
                                Select::make('event_id')
                                    ->relationship('event', 'name')
                                    ->required(),
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required(),
                                TextInput::make('name')
                                    ->label('Nama Tim')
                                    ->required(),
                                TextInput::make('short_name')
                                    ->label('Singkatan (3 Huruf)')
                                    ->maxLength(3)
                                    ->placeholder('BFC'),
                                TextInput::make('group_name')
                                    ->label('Grup')
                                    ->default('A'),
                            ]),

                    ]),
                
                Section::make('Akun Login Manager')
                    ->columnSpanFull()
                    ->relationship('user')
                    ->visibleOn('create')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Manager')
                            ->required(),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique('users', 'email', ignoreRecord: true),
                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->hiddenOn('edit')
                            ->required(),
                        Hidden::make('role')
                            ->default('team'), 
                    ]),
            ]);
    }
}
