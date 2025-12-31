<?php

namespace App\Filament\App\Resources\Officials;

use App\Filament\App\Resources\Officials\Pages\CreateOfficial;
use App\Filament\App\Resources\Officials\Pages\EditOfficial;
use App\Filament\App\Resources\Officials\Pages\ListOfficials;
use App\Filament\App\Resources\Officials\Schemas\OfficialForm;
use App\Filament\App\Resources\Officials\Tables\OfficialsTable;
use App\Models\Official;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OfficialResource extends Resource
{
    protected static ?string $model = Official::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | UnitEnum | null $navigationGroup  = 'Event Management';


    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Officials';

    public static function form(Schema $schema): Schema
    {
        return OfficialForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficialsTable::configure($table);
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
            'index' => ListOfficials::route('/'),
            'create' => CreateOfficial::route('/create'),
            'edit' => EditOfficial::route('/{record}/edit'),
        ];
    }
}
