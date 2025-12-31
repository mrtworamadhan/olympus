<?php

namespace App\Filament\Team\Resources\MyCompetitors\RelationManagers;

use BackedEnum;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GamesRelationManager extends RelationManager
{
    protected static string $relationship = 'matches';
    protected static ?string $title = 'Matches Schedule';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('homeCompetitor.name')
                    ->label('Home')
                    ->weight('bold')
                    ->color(fn ($record) => $record->home_competitor_id == $this->getOwnerRecord()->id ? 'primary' : 'gray'),
                
                TextColumn::make('score')
                    ->label('Skor')
                    ->getStateUsing(fn ($record) => $record->status === 'finished' || $record->status === 'live' 
                        ? "{$record->home_score} - {$record->away_score}" 
                        : 'VS')
                    ->badge()
                    ->color(fn ($record) => $record->status === 'live' ? 'danger' : 'gray'),

                TextColumn::make('awayCompetitor.name')
                    ->label('Away')
                    ->weight('bold')
                    ->color(fn ($record) => $record->away_competitor_id == $this->getOwnerRecord()->id ? 'primary' : 'gray'),
                    
                TextColumn::make('venue.name')->label('Lokasi'),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->where(function($q) {
                $teamId = $this->getOwnerRecord()->id;
                $q->where('home_competitor_id', $teamId)
                  ->orWhere('away_competitor_id', $teamId);
            }))
            ->defaultSort('scheduled_at', 'asc')
            
            ->recordActions([
                ViewAction::make(),
              
            ]);
    }
}
