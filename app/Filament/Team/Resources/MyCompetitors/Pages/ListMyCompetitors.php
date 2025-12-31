<?php

namespace App\Filament\Team\Resources\MyCompetitors\Pages;

use App\Filament\Team\Resources\MyCompetitors\MyCompetitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMyCompetitors extends ListRecords
{
    protected static string $resource = MyCompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
