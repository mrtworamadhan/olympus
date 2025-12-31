<?php

namespace App\Filament\Team\Resources\MasterPlayers\Pages;

use App\Filament\Team\Resources\MasterPlayers\MasterPlayerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMasterPlayers extends ListRecords
{
    protected static string $resource = MasterPlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
