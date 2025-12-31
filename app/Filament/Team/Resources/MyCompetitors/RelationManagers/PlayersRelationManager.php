<?php

namespace App\Filament\Team\Resources\MyCompetitors\RelationManagers;

use App\Models\MasterPlayer;
use App\Models\Player;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
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
                    ->description(fn (Player $record): string => $record->age . ' Tahun')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tgl Lahir'),
                TextColumn::make('goals_count') 
                    ->counts('goals') 
                    ->label('Gol')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                TextColumn::make('yellow_cards_count')
                    ->counts('yellowCards')
                    ->label('Yellow Card')
                    ->alignCenter()
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),

                TextColumn::make('red_cards_count')
                    ->counts('redCards')
                    ->label('Red Card')
                    ->alignCenter()
                    ->color('danger') 
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // CreateAction::make()->label('Add Players'),
                // AssociateAction::make(),
                Action::make('import_master')
                    ->label('Ambil dari Master Data')
                    ->icon('heroicon-o-users')
                    ->color('success')
                    ->form(function ($livewire) {
                        $competitor = $livewire->getOwnerRecord(); 
                        $category = $competitor->category;
                        $eventId = $competitor->event_id;
                        
                        $limitDate = $category->min_date_of_birth 
                            ? Carbon::parse($category->min_date_of_birth) 
                            : null;

                        return [
                            CheckboxList::make('master_player_ids')
                                ->label('Pilih Pemain untuk Dimasukkan ke Tim')
                                ->searchable()
                                ->bulkToggleable()
                                ->options(function () use ($limitDate, $eventId) {
                                    return MasterPlayer::where('user_id', auth()->id())
                                        ->get()
                                        ->mapWithKeys(function ($mp) {
                                            return [$mp->id => "{$mp->name} ({$mp->position})"];
                                        });
                                })
                                ->descriptions(function () use ($limitDate, $eventId) {
                                    return MasterPlayer::where('user_id', auth()->id())
                                        ->get()
                                        ->mapWithKeys(function ($mp) use ($limitDate, $eventId) {
                                            $desc = "Usia: " . Carbon::parse($mp->date_of_birth)->age . " Th";
                                            
                                            if ($limitDate && Carbon::parse($mp->date_of_birth)->lt($limitDate)) {
                                                return [$mp->id => "TIDAK MEMENUHI SYARAT USIA ({$desc})"];
                                            }

                                            $isRegistered = Player::whereHas('competitor', function ($q) use ($eventId) {
                                                    $q->where('event_id', $eventId);
                                                })
                                                ->where('master_player_id', $mp->id)
                                                ->exists();

                                            if ($isRegistered) {
                                                return [$mp->id => "SUDAH TERDAFTAR DI TIM LAIN"];
                                            }

                                            return [$mp->id => "Memenuhi Syarat ({$desc})"];
                                        });
                                })

                                ->disableOptionWhen(function ($value) use ($limitDate, $eventId) {
                                    $mp = MasterPlayer::find($value);
                                    if (!$mp) return true;

                                    if ($limitDate && Carbon::parse($mp->date_of_birth)->lt($limitDate)) {
                                        return true;
                                    }

                                    $isRegistered = Player::whereHas('competitor', function ($q) use ($eventId) {
                                            $q->where('event_id', $eventId);
                                        })
                                        ->where('master_player_id', $mp->id)
                                        ->exists();
                                    
                                    if ($isRegistered) return true;

                                    return false; 
                                }),
                        ];
                    })
                    ->action(function (array $data, $livewire) {
                        $competitor = $livewire->getOwnerRecord();
                        $selectedIds = $data['master_player_ids'];

                        $masters = MasterPlayer::whereIn('id', $selectedIds)->get();

                        $count = 0;
                        foreach ($masters as $mp) {

                            Player::create([
                                'competitor_id' => $competitor->id,
                                'master_player_id' => $mp->id,
                                'name'          => $mp->name,
                                'number'        => $mp->number,
                                'position'      => $mp->position,
                                'date_of_birth' => $mp->date_of_birth,
                                'photo'         => $mp->photo, 
                                'identity_card_file' => $mp->identity_card_file ?? null, // Asumsi ada field ini di master
                            ]);
                            $count++;
                        }

                        Notification::make()
                            ->success()
                            ->title("Berhasil menambahkan {$count} pemain!")
                            ->send();
                    }),
            ])
            ->recordActions([
                
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
