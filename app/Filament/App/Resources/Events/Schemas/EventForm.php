<?php

namespace App\Filament\App\Resources\Events\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('logo')
                    ->directory('event-logo')
                    ->disk('public')
                    ->image(),
                TextInput::make('name')
                    ->label('Nama Turnamen')
                    ->required(),
                TextInput::make('slug')
                    ->label('URL Slug')
                    ->required()
                    ->maxLength(255),
                DatePicker::make('start_date')->required(),
                DatePicker::make('end_date')->required(),
                Toggle::make('is_active')
                    ->label('Publikasikan')
                    ->default(true),
                
                Section::make('Tampilan Halaman Publik (Landing Page)')
                ->components([
                    FileUpload::make('banner_image')
                        ->label('Banner Utama / Flyer')
                        ->image()
                        ->directory('event-banners')
                        ->disk('public')
                        ->columnSpanFull(),

                    RichEditor::make('public_description')
                        ->label('Deskripsi / Sambutan')
                        ->columnSpanFull(),

                    ColorPicker::make('primary_color')
                        ->label('Warna Tema Event')
                        ->helperText('Kosongkan jika ingin mengikuti warna Tenant default.'),

                    Toggle::make('is_registration_open')
                        ->label('Buka Pendaftaran Tim')
                        ->default(true)
                        ->inline(false),
                ])
                ->columnSpanFull()
                ->collapsible(),
            ]);
    }
}
