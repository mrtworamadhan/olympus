<?php

namespace App\Filament\Team\Resources\AvailableEvents;

use App\Filament\Team\Resources\AvailableEvents\Pages\CreateAvailableEvent;
use App\Filament\Team\Resources\AvailableEvents\Pages\EditAvailableEvent;
use App\Filament\Team\Resources\AvailableEvents\Pages\ListAvailableEvents;
use App\Filament\Team\Resources\AvailableEvents\Schemas\AvailableEventForm;
use App\Filament\Team\Resources\AvailableEvents\Tables\AvailableEventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AvailableEventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Events Active';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }

    public static function form(Schema $schema): Schema
    {
        return AvailableEventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AvailableEventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAvailableEvents::route('/'),
        ];
    }
}
