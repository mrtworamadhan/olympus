<?php

namespace App\Filament\App\Resources\GameMatches;

use App\Filament\App\Resources\GameMatches\Pages\CreateGameMatch;
use App\Filament\App\Resources\GameMatches\Pages\EditGameMatch;
use App\Filament\App\Resources\GameMatches\Pages\ListGameMatches;
use App\Filament\App\Resources\GameMatches\Schemas\GameMatchForm;
use App\Filament\App\Resources\GameMatches\Tables\GameMatchesTable;
use App\Models\GameMatch;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GameMatchResource extends Resource
{
    protected static ?string $model = GameMatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCubeTransparent;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string | UnitEnum | null $navigationGroup  = 'Event Database';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Matches';

    public static function form(Schema $schema): Schema
    {
        return GameMatchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GameMatchesTable::configure($table);
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
            'index' => ListGameMatches::route('/'),
            'create' => CreateGameMatch::route('/create'),
            'edit' => EditGameMatch::route('/{record}/edit'),
        ];
    }
}
