<?php

namespace App\Filament\App\Resources\Venues\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VenueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lapangan')
                    ->required()
                    ->placeholder('Contoh: Lapangan 1 (Utama)'),
                TextInput::make('location')
                    ->label('Lokasi / Keterangan')
                    ->placeholder('Lantai 2, Sayap Kiri'),
            ]);
    }
}
