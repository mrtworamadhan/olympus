<?php

namespace App\Filament\App\Resources\GameMatches\Pages;

use App\Filament\App\Resources\GameMatches\GameMatchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGameMatch extends CreateRecord
{
    protected static string $resource = GameMatchResource::class;
}
