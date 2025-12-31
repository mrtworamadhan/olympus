<?php

namespace App\Filament\App\Resources\GameMatches\Tables;

use App\Models\GameMatch;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Livewire\Component;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GameMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scheduled_at')
                    ->label('Jadwal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Info')
                    ->description(fn (GameMatch $record) => $record->venue->name ?? '-'),

                TextColumn::make('homeCompetitor.name')
                    ->label('Home')
                    ->weight('bold')
                    ->alignRight()
                    ->color('primary'),

                TextColumn::make('vs_separator')
                    ->label('')
                    ->default('VS') 
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),

                TextColumn::make('awayCompetitor.name')
                    ->label('Away')
                    ->weight('bold')
                    ->alignLeft()
                    ->color('danger'),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'gray',
                        'live' => 'danger',
                        'finished' => 'success',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('scheduled_at', 'asc')
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
                SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Belum Main',
                        'finished' => 'Selesai',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('operator')
                    ->label('War Room')
                    ->icon('heroicon-o-computer-desktop')
                    ->url(fn (GameMatch $record) => route('match.operator', $record))
                    ->openUrlInNewTab()
                    ->color('success'),
                Action::make('qr_code')
                    ->label('QR Code')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading('Share Pertandingan')
                    ->modalAlignment(Alignment::Center)
                    ->modalWidth('md') 
                    ->modalSubmitAction(false) 
                    ->modalCancelAction(false) 
                    
                    ->modalContent(fn (GameMatch $record) => view('filament.components.game-qr-modal', [
                        'game' => $record,
                    ]))

                    ->modalFooterActions(function (GameMatch $record) {
                        return [
                            Action::make('share_link')
                                ->label('Buka Link')
                                ->icon('heroicon-o-arrow-top-right-on-square')
                                ->url(route('public.match', $record->id))
                                ->openUrlInNewTab()
                                ->color('gray'),

                            Action::make('copy_link')
                                ->label('Copy Link')
                                ->icon('heroicon-o-clipboard-document')
                                ->color('primary')
                                ->action(function (GameMatch $record, Component $livewire) { 
                                    
                                    Notification::make()
                                        ->title('Link berhasil disalin!')
                                        ->success()
                                        ->send();

                                    $livewire->js(
                                        "window.navigator.clipboard.writeText('" . route('public.match', $record->id) . "');"
                                    );
                                }),

                            Action::make('close_modal')
                                ->label('Tutup')
                                ->color('gray')
                                ->close(),
                        ];
                    })
                    ->modalFooterActionsAlignment(Alignment::Center)
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
