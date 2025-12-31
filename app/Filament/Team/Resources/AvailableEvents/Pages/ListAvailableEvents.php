<?php

namespace App\Filament\Team\Resources\AvailableEvents\Pages;

use App\Filament\Team\Resources\AvailableEvents\AvailableEventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAvailableEvents extends ListRecords
{
    protected static string $resource = AvailableEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
