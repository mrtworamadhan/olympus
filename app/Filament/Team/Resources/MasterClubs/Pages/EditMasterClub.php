<?php

namespace App\Filament\Team\Resources\MasterClubs\Pages;

use App\Filament\Team\Resources\MasterClubs\MasterClubResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMasterClub extends EditRecord
{
    protected static string $resource = MasterClubResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
