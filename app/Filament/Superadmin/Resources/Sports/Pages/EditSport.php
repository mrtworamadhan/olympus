<?php

namespace App\Filament\Superadmin\Resources\Sports\Pages;

use App\Filament\Superadmin\Resources\Sports\SportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSport extends EditRecord
{
    protected static string $resource = SportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
