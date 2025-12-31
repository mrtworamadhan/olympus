<?php

namespace App\Filament\App\Resources\Categories\Tables;

use App\Models\Category;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('event.name')
                    ->label('Event')
                    ->sortable()
                    ->searchable()
                    ->description(fn (Category $record): string => $record->sport->name ?? '-'), // Subtitle: Nama Cabor
                
                TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('min_date_of_birth')
                    ->label('Tanggal Lahir Minimum')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('rules_settings.period_duration')
                    ->label('Durasi')
                    ->suffix(' Menit'),
                
                IconColumn::make('rules_settings.is_stop_clock')
                    ->label('Stop Clock')
                    ->boolean(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'registration' => 'info',
                        'drawing' => 'warning',
                        'ongoing' => 'success',
                        'completed' => 'success',
                    }),
            ])
            ->filters([
                SelectFilter::make('event_id')
                    ->relationship('event', 'name')
                    ->label('Filter Event'),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('drawing')
                    ->label('Ruang Undian')
                    ->icon('heroicon-o-arrows-right-left')
                    ->url(fn (Category $record) => route('drawing.room', ['category' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
