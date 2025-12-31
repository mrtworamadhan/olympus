<?php

namespace App\Filament\Team\Resources\AvailableEvents\Pages;

use App\Filament\Team\Resources\AvailableEvents\AvailableEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAvailableEvent extends EditRecord
{
    protected static string $resource = AvailableEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
