<?php

namespace App\Filament\Official\Resources\GameMatches\Pages;

use App\Filament\Official\Resources\GameMatches\GameMatchResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewGameMatch extends ViewRecord
{
    protected static string $resource = GameMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
