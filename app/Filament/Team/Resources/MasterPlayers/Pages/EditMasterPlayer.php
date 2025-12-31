<?php

namespace App\Filament\Team\Resources\MasterPlayers\Pages;

use App\Filament\Team\Resources\MasterPlayers\MasterPlayerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMasterPlayer extends EditRecord
{
    protected static string $resource = MasterPlayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
