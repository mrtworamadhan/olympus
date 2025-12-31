<?php

namespace App\Filament\Team\Resources\MyCompetitors\Tables;

use App\Models\Category;
use App\Models\Competitor;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MyCompetitorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->disk('public')
                    ->visibility('public')
                    ->circular(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'submitted' => 'info',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Edit LineUp'),
                Action::make('submit_registration')
                    ->label('Ajukan Tim')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== ['approved', 'submitted'])
                    
                    ->form([
                        Select::make('category_id')
                            ->label('Pilih Kategori Turnamen')
                            ->options(function ($record) {
                                return Category::where('event_id', $record->event_id)
                                    ->pluck('name', 'id');
                            })
                            ->default(fn ($record) => $record->category_id)
                            ->required()
                            ->helperText('Sistem akan mengecek usia pemain secara otomatis.'),
                    ])
                    
                    ->action(function (Competitor $record, array $data) {
                        $category = Category::find($data['category_id']);
                        
                        if (!$category->min_date_of_birth) {
                            $record->update([
                                'category_id' => $data['category_id'],
                                'status' => 'submitted'
                            ]);
                            
                            Notification::make()->success()->title('Pendaftaran Diajukan!')->send();
                            return;
                        }

                        $minDate = Carbon::parse($category->min_date_of_birth);
                        
                        $players = $record->players;

                        $invalidPlayers = [];

                        foreach ($players as $player) {

                            if (!$player->date_of_birth) continue; 

                            $playerDob = Carbon::parse($player->date_of_birth);

                            if ($playerDob->lt($minDate)) {
                                $age = $playerDob->age;
                                $invalidPlayers[] = "{$player->name} ($age Th)";
                            }
                        }

                        if (count($invalidPlayers) > 0) {
                            $names = implode(', ', $invalidPlayers);
                            
                            Notification::make()
                                ->danger() 
                                ->title('Validasi Usia Gagal!')
                                ->body("Pendaftaran ditolak. Pemain berikut melebihi batas usia kategori {$category->name}: \n" . $names)
                                ->persistent()
                                ->send();
                                
                            return; 
                            
                        } else {
                            $record->update([
                                'category_id' => $data['category_id'],
                                'status' => 'submitted'
                            ]);

                            Notification::make()
                                ->success()
                                ->title('Berhasil Diajukan!')
                                ->body('Data tim dan usia pemain valid. Menunggu persetujuan Admin.')
                                ->send();
                        }
                    })
                    ->modalHeading('Konfirmasi Pendaftaran')
                    ->modalSubmitActionLabel('Cek Validasi & Ajukan')
                    ->modalDescription('Pastikan semua data pemain sudah benar. Sistem akan menolak jika ada pemain yang melebihi batas usia.'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
