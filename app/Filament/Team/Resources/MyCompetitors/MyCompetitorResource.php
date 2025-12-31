<?php

namespace App\Filament\Team\Resources\MyCompetitors;

use App\Filament\Team\Resources\MyCompetitors\Pages\CreateMyCompetitor;
use App\Filament\Team\Resources\MyCompetitors\Pages\EditMyCompetitor;
use App\Filament\Team\Resources\MyCompetitors\Pages\InfoListMyCompetitor;
use App\Filament\Team\Resources\MyCompetitors\Pages\ListMyCompetitors;
use App\Filament\Team\Resources\MyCompetitors\Schemas\MyCompetitorForm;
use App\Filament\Team\Resources\MyCompetitors\Tables\MyCompetitorsTable;
use App\Models\Competitor;
use BackedEnum;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyCompetitorResource extends Resource
{
    protected static ?string $model = Competitor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'My Teams';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return MyCompetitorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyCompetitorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PlayersRelationManager::class,
            RelationManagers\GamesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyCompetitors::route('/'),
            // 'edit' => EditMyCompetitor::route('/{record}/edit'),
            'view' => InfoListMyCompetitor::route('/{record}'),
        ];
    }
}
