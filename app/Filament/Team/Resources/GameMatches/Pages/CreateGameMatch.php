<?php

namespace App\Filament\Team\Resources\GameMatches\Pages;

use App\Filament\Team\Resources\GameMatches\GameMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGameMatch extends CreateRecord
{
    protected static string $resource = GameMatchResource::class;
}
