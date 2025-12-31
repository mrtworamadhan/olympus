<?php

namespace App\Filament\Team\Resources\AvailableEvents\Pages;

use App\Filament\Team\Resources\AvailableEvents\AvailableEventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAvailableEvent extends CreateRecord
{
    protected static string $resource = AvailableEventResource::class;
}
