<?php

namespace App\Filament\Team\Resources\MasterClubs\RelationManagers;

use App\Models\MasterPlayer;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlayersRelationManager extends RelationManager
{
    protected static string $relationship = 'players';

    protected static ?string $title = 'Players List';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('photo')
                   ->avatar()
                   ->disk('public')
                   ->directory('players-photo')
                   ->image(),

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('number')
                    ->numeric()
                    ->required(),

                TextInput::make('position'),

                DatePicker::make('date_of_birth')
                    ->required()
                     ->maxDate(now()),
                     
                FileUpload::make('identity_card_file')
                    ->disk('public')
                    ->directory('player_docs')
                    ->visibility('public'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name,number,position')
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('number')
                    ->sortable(),
                TextColumn::make('position'),
                TextColumn::make('date_of_birth')
                    ->description(fn (MasterPlayer $record): string => $record->age . ' Tahun')
                    ->date('d M Y')
                    ->label('Tgl Lahir'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Add Player'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
