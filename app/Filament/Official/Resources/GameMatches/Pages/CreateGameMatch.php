<?php

namespace App\Filament\Official\Resources\GameMatches\Pages;

use App\Filament\Official\Resources\GameMatches\GameMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGameMatch extends CreateRecord
{
    protected static string $resource = GameMatchResource::class;
}
