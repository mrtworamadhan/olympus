<?php

namespace App\Filament\Team\Resources\MasterPlayers\Schemas;

use App\Models\MasterClub;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class MasterPlayerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make()
                    ->default(auth()->id()),
                FileUpload::make('photo')
                    ->label('Foto Pemain')
                    ->avatar()
                    ->directory('players-photo')
                    ->disk('public')
                    ->image(),
                    
                Select::make('master_club_id')
                    ->label('Tim')
                    ->required()
                    ->options(function () {
                        return MasterClub::where('user_id', Auth::id())
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->reactive(),

                TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('number')
                    ->label('Jersey Number')
                    ->numeric()
                    ->required(),
                    
                TextInput::make('position')
                    ->label('Position')
                    ->placeholder('GK, CB, ST, dll'),
                
                DatePicker::make('date_of_birth')
                    ->label('Date of Birth')
                    ->required()
                    ->maxDate(now()),

                FileUpload::make('identity_card_file')
                    ->label('Card Identity')
                    ->image()
                    ->directory('player_docs')
                    ->disk('public')
                    ->visibility('public'),
            ]);
    }
}
