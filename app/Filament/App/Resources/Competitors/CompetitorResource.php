<?php

namespace App\Filament\App\Resources\Competitors;

use App\Filament\App\Resources\Competitors\Pages\CreateCompetitor;
use App\Filament\App\Resources\Competitors\Pages\EditCompetitor;
use App\Filament\App\Resources\Competitors\Pages\InfoListCompetitors;
use App\Filament\App\Resources\Competitors\Pages\ListCompetitors;
use App\Filament\App\Resources\Competitors\Schemas\CompetitorForm;
use App\Filament\App\Resources\Competitors\Tables\CompetitorsTable;
use App\Models\Competitor;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CompetitorResource extends Resource
{
    protected static ?string $model = Competitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup  = 'Event Database';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Teams';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'submitted')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Schema $schema): Schema
    {
        return CompetitorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CompetitorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlayersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCompetitors::route('/'),
            'create' => CreateCompetitor::route('/create'),
            // 'edit' => EditCompetitor::route('/{record}/edit'),
            'view' => InfoListCompetitors::route('/{record}'),
        ];
    }
}
