<?php

namespace App\Filament\App\Resources\Competitors\RelationManagers;

use App\Models\Player;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;

class PlayersRelationManager extends RelationManager
{
    protected static string $relationship = 'players';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pemain')
                    ->required()
                    ->maxLength(255),

                TextInput::make('number')
                    ->label('No. Punggung')
                    ->numeric()
                    ->maxValue(99),

                Select::make('position')
                    ->label('Posisi')
                    ->options([
                        'GK' => 'Goalkeeper',
                        'DF' => 'Defender',
                        'MF' => 'Midfielder',
                        'FW' => 'Forward',
                    ])
                    ->required(),
                    
                FileUpload::make('photo')
                    ->label('Foto Pemain')
                    ->directory('players-photo')
                    ->disk('public')
                    ->image(),

                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('photo')
                    ->circular()
                    ->disk('public')
                    ->visibility('public')
                    ->label('Foto'),
                
                TextColumn::make('number')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('position')
                    ->label('Posisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GK' => 'gray',
                        'DF' => 'info',
                        'MF' => 'warning',
                        'FW' => 'success',
                        default => 'gray',
                    }),
            
                TextColumn::make('date_of_birth')
                    ->label('Age')
                    ->date('d M Y') 
                    ->description(fn (Player $record): string => $record->age . ' Tahun'),
                
                ViewColumn::make('identity_card_file')
                    ->label('Dokumen')
                    ->view('filament.components.identity-card-preview'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->label('Tambah Pemain Baru'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
