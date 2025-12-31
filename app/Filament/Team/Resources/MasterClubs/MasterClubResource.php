<?php

namespace App\Filament\Team\Resources\MasterClubs;

use App\Filament\Team\Resources\MasterClubs\Pages\CreateMasterClub;
use App\Filament\Team\Resources\MasterClubs\Pages\EditMasterClub;
use App\Filament\Team\Resources\MasterClubs\Pages\ListMasterClubs;
use App\Filament\Team\Resources\MasterClubs\Schemas\MasterClubForm;
use App\Filament\Team\Resources\MasterClubs\Tables\MasterClubsTable;
use App\Models\MasterClub;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MasterClubResource extends Resource
{
    protected static ?string $model = MasterClub::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static string | UnitEnum | null $navigationGroup  = 'Team Management';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MasterClubForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterClubsTable::configure($table);
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
            'index' => ListMasterClubs::route('/'),
            'create' => CreateMasterClub::route('/create'),
            'edit' => EditMasterClub::route('/{record}/edit'),
        ];
    }
}
