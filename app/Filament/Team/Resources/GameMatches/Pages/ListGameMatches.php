<?php

namespace App\Filament\Team\Resources\GameMatches\Pages;

use App\Filament\Team\Resources\GameMatches\GameMatchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGameMatches extends ListRecords
{
    protected static string $resource = GameMatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
