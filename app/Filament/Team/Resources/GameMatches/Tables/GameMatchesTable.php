<?php

namespace App\Filament\Team\Resources\GameMatches\Tables;

use App\Models\GameMatch;
use App\Models\Player;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameMatchesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('opponent')
                    ->label('Lawan')
                    ->state(function (GameMatch $record) {
                        $myTeamId = Auth::user()->competitor->id ?? 0;
                        if ($record->home_competitor_id == $myTeamId) {
                            return 'vs ' . $record->awayCompetitor->name . ' (Home)';
                        }
                        return 'vs ' . $record->homeCompetitor->name . ' (Away)';
                    })
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'Home') ? 'success' : 'danger'),

                TextColumn::make('scheduled_at')
                    ->label('Waktu Kick-off')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                TextColumn::make('venue.name')
                    ->label('Lokasi'),

                TextColumn::make('referee.name')
                    ->label('Wasit'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('manage_lineup')
                    ->label('Atur Pemain')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->modalHeading('Pilih Pemain untuk Pertandingan Ini')
                    ->modalSubmitActionLabel('Simpan Line Up')
                    ->form([
                        CheckboxList::make('players')
                            ->label('Daftar Pemain')
                            ->searchable()
                            ->options(function () {
                                $myTeamId = Auth::user()->competitor->id ?? 0;
                                return Player::where('competitor_id', $myTeamId)
                                    ->orderBy('number', 'asc')
                                    ->get()
                                    ->mapWithKeys(function ($player) {
                                        $label = "{$player->number}. {$player->name} ({$player->position})";
                                        
                                        return [$player->id => $label];
                                    });
                            })
                            ->afterStateHydrated(function ($component, GameMatch $record) {
                                $myTeamId = Auth::user()->competitor->id ?? 0;
                                $selectedPlayers = DB::table('game_lineups')
                                    ->where('game_id', $record->id)
                                    ->where('competitor_id', $myTeamId)
                                    ->pluck('player_id')
                                    ->toArray();
                                
                                $component->state($selectedPlayers);
                            }),
                    ])

                    ->action(function (array $data, GameMatch $record) {
                        $myTeamId = Auth::user()->competitor->id ?? 0;
                        $playerIds = $data['players'];

                        DB::transaction(function () use ($record, $myTeamId, $playerIds) {
                            DB::table('game_lineups')
                                ->where('game_id', $record->id)
                                ->where('competitor_id', $myTeamId)
                                ->delete();

                            $insertData = [];
                            foreach ($playerIds as $playerId) {
                                $insertData[] = [
                                    'game_id' => $record->id,
                                    'competitor_id' => $myTeamId,
                                    'player_id' => $playerId,
                                    'is_starter' => true,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                            
                            if (!empty($insertData)) {
                                DB::table('game_lineups')->insert($insertData);
                            }
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Line Up Disimpan')
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
