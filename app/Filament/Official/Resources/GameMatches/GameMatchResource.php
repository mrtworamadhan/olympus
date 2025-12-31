<?php

namespace App\Filament\Official\Resources\GameMatches;

use App\Filament\Official\Resources\GameMatches\Pages\CreateGameMatch;
use App\Filament\Official\Resources\GameMatches\Pages\EditGameMatch;
use App\Filament\Official\Resources\GameMatches\Pages\ListGameMatches;
use App\Filament\Official\Resources\GameMatches\Pages\ViewGameMatch;
use App\Filament\Official\Resources\GameMatches\Schemas\GameMatchForm;
use App\Filament\Official\Resources\GameMatches\Schemas\GameMatchInfolist;
use App\Filament\Official\Resources\GameMatches\Tables\GameMatchesTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Match Shedules';

    public static function getEloquentQuery(): Builder
    {
        $myOfficialId = Auth::user()->official->id ?? 0;

        return parent::getEloquentQuery()
            ->where(function ($query) use ($myOfficialId) {
                $query->where('referee_id', $myOfficialId)
                      ->orWhere('operator_id', $myOfficialId);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return GameMatchForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return GameMatchInfolist::configure($schema);
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
        ];
    }
}
