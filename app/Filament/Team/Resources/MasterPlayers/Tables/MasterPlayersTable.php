<?php

namespace App\Filament\Team\Resources\MasterPlayers\Tables;

use App\Models\MasterPlayer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MasterPlayersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->visibility('public')
                    ->disk('public')
                    ->circular(),
                    
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('number')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('position')
                    ->searchable(),
                
                TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->date('d M Y') 
                    ->description(fn (MasterPlayer $record): string => $record->age . ' Tahun'),

                TextColumn::make('club.name')
                    ->label('Club')
                    ->sortable(),

                ImageColumn::make('identity_card_file')
                    ->disk('public')
                    ->visibility('public')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dokumen'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
