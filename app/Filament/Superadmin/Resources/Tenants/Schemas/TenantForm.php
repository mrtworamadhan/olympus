<?php

namespace App\Filament\Superadmin\Resources\Tenants\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->label('URL Slug (Subdomain)')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('primary_color')
                    ->label('Warna Tema')
                    ->default('#000000'),
                FileUpload::make('logo')
                    ->directory('tenants-logo')
                    ->disk('public')
                    ->image(),
            ]);
    }
}
