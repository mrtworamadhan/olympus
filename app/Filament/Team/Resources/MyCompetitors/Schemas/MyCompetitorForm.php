<?php

namespace App\Filament\Team\Resources\MyCompetitors\Schemas;

use App\Models\Category;
use App\Models\Event;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class MyCompetitorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(auth()->id()),
                    
                Hidden::make('event_id')
                    ->default(fn() => request()->query('event_id'))
                    ->required()
                    ->afterStateHydrated(function (Hidden $component, Set $set, $state) {
                        if ($state) {
                            $event = Event::find($state);
                            if ($event) {
                                $set('tenant_id', $event->tenant_id);
                            }
                        }
                    }),

                Hidden::make('tenant_id')
                    ->required()
                    ->dehydrated(),
                
                FileUpload::make('logo')
                    ->disk('public')
                    ->directory('teams-logo')
                    ->avatar(),
                TextInput::make('name')
                    ->label('Nama Tim')
                    ->required(),

                Select::make('category_id')
                    ->label('Kategori Usia')
                    ->required()
                    ->options(function (Get $get, ?Model $record) {
                        $eventId = $get('event_id') ?? $record?->event_id;

                        if (!$eventId) {
                            return []; 
                        }

                        return Category::where('event_id', $eventId)
                            ->pluck('name', 'id');
                    })
                    
                    ->exists(table: Category::class, column: 'id'),

                TextInput::make('short_name')
                    ->label('Singkatan (3 Huruf)')
                    ->maxLength(3)
                    ->placeholder('BFC'),
                    
                
            ]);
    }
}
