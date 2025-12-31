<?php

namespace App\Filament\Team\Resources\AvailableEvents\Tables;

use App\Models\Category;
use App\Models\Competitor;
use App\Models\Event;
use App\Models\MasterClub;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AvailableEventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('name')
                    ->label('Nama Event')
                    ->searchable()
                    ->description(fn (Event $record) => $record->tenant->name ?? 'Organizer'), // Tampilkan nama EO (Tenant)
                
                TextColumn::make('start_date')->date('d M Y')->label('Mulai'),
                TextColumn::make('location')->label('Lokasi'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('register_team')
                    ->label('Daftar')
                    ->icon('heroicon-o-user-plus')
                    ->button()
                    
                    ->form(function (Event $record) {
                        return [
                            Placeholder::make('info')
                                ->content("Anda akan mendaftar ke turnamen: {$record->name}"),
                            
                            Select::make('master_club_id')
                                ->label('Tim')
                                ->required()
                                ->options(function () {
                                    return MasterClub::where('user_id', Auth::id())
                                        ->pluck('name', 'id');
                                })
                                ->searchable()
                                ->disableOptionWhen(function ($value) use ($record) {                                    
                                    return Competitor::where('event_id', $record->id)
                                        ->where('master_club_id', $value) 
                                        ->exists();
                                })
                                ->validationAttribute('Tim yang dipilih'),

                            TextInput::make('short_name')
                                ->label('Singkatan (3 Huruf)')
                                ->maxLength(3),

                            Select::make('category_id')
                                ->label('Pilih Kategori')
                                ->options(function () use ($record) {
                                    return Category::where('event_id', $record->id)
                                        ->pluck('name', 'id');
                                })
                                ->required(),
                        ];
                    })
                    ->action(function (Event $record, array $data) {
                        
                        $existing = Competitor::where('user_id', Auth::id())
                            ->where('event_id', $record->id)
                            ->where('category_id', $data['category_id'])
                            ->exists();
                            
                        if ($existing) {
                            Notification::make()->danger()->title('Tim Anda sudah terdaftar di kategori ini!')->send();
                            return;
                        }

                        $masterClub = MasterClub::find($data['master_club_id']);

                        Competitor::create([
                            'user_id'     => Auth::id(),
                            'event_id'    => $record->id,
                            'tenant_id'   => $record->tenant_id,
                            'category_id' => $data['category_id'],
                            'master_club_id' => $masterClub->id,
                            'name'        =>  $masterClub->name,
                            'logo'        => $masterClub->logo,
                            'short_name'  => $data['short_name'],
                            'status'      => 'pending',     
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Pendaftaran Berhasil!')
                            ->body('Silakan lengkapi data pemain di menu "Tim Saya".')
                            ->send();
                            
                        return redirect()->to('/team/my-competitors');
                    }),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
