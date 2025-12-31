<?php

namespace App\Filament\Team\Resources\MasterPlayers;

use App\Filament\Team\Resources\MasterPlayers\Pages\CreateMasterPlayer;
use App\Filament\Team\Resources\MasterPlayers\Pages\EditMasterPlayer;
use App\Filament\Team\Resources\MasterPlayers\Pages\ListMasterPlayers;
use App\Filament\Team\Resources\MasterPlayers\Schemas\MasterPlayerForm;
use App\Filament\Team\Resources\MasterPlayers\Tables\MasterPlayersTable;
use App\Models\MasterPlayer;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MasterPlayerResource extends Resource
{
    protected static ?string $model = MasterPlayer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Players';

    protected static string | UnitEnum | null $navigationGroup  = 'Team Management';
    protected static ?int $navigationSort = 2;

     public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return MasterPlayerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MasterPlayersTable::configure($table);
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
            'index' => ListMasterPlayers::route('/'),
            'create' => CreateMasterPlayer::route('/create'),
            'edit' => EditMasterPlayer::route('/{record}/edit'),
        ];
    }
}
