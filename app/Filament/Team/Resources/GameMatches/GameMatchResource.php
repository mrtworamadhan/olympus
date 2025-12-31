<?php

namespace App\Filament\Team\Resources\GameMatches;

use App\Filament\Team\Resources\GameMatches\Pages\CreateGameMatch;
use App\Filament\Team\Resources\GameMatches\Pages\EditGameMatch;
use App\Filament\Team\Resources\GameMatches\Pages\ListGameMatches;
use App\Filament\Team\Resources\GameMatches\Schemas\GameMatchForm;
use App\Filament\Team\Resources\GameMatches\Tables\GameMatchesTable;
use App\Models\GameMatch;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GameMatchResource extends Resource
{
    protected static ?string $model = GameMatch::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    protected static ?string $navigationLabel = 'Matches Schedule';

    protected static ?string $recordTitleAttribute = 'name';

    public static function canCreate(): bool { return false; }

    public static function getEloquentQuery(): Builder
    {
        $myTeamId = Auth::user()->competitor->id ?? 0;

        return parent::getEloquentQuery()
            ->where(function ($query) use ($myTeamId) {
                $query->where('home_competitor_id', $myTeamId)
                      ->orWhere('away_competitor_id', $myTeamId);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
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
