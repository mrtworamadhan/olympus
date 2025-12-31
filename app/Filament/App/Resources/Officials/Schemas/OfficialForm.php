<?php

namespace App\Filament\App\Resources\Officials\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class OfficialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tim')
                    ->columnSpanFull()
                    ->components([
                        Grid::make()
                            ->columns(3)
                            ->components([
                                TextInput::make('name')
                                    ->required()
                                    ->label('Nama Lengkap'),

                                Select::make('role')
                                    ->options([
                                        'referee' => 'Wasit (Referee)',
                                        'operator' => 'Operator / Admin Pertandingan',
                                        'inspector' => 'Pengawas Pertandingan (IP)',
                                    ])
                                    ->required()
                                    ->label('Jabatan'),

                                TextInput::make('license_level')
                                    ->label('Lisensi (Opsional)')
                                    ->placeholder('Contoh: Nasional, C1, C2'),

                            ]),
                        
                    ]),
                
                Section::make('Akun Login Petugas')
                    ->relationship('user') // Pastikan relasi user() ada di Model Official -> belongsTo User
                    ->columnSpanFull()
                    ->components([
                        Grid::make()
                            ->columns(3)
                            ->components([
                                TextInput::make('name')
                                    ->label('Nama User')
                                    ->required(),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignoreRecord: true),
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    // ->hiddenOn('edit')
                                    ->required(),
                                Hidden::make('role')
                                    ->default('official'),

                            ]),
                        
                    ]),
            ]);
    }
}
