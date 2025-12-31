<?php

namespace App\Filament\Team\Resources\MasterClubs\Pages;

use App\Filament\Team\Resources\MasterClubs\MasterClubResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMasterClubs extends ListRecords
{
    protected static string $resource = MasterClubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Add Club'),
        ];
    }
}
