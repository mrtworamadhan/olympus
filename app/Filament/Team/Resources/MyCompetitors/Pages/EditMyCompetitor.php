<?php

namespace App\Filament\Team\Resources\MyCompetitors\Pages;

use App\Filament\Team\Resources\MyCompetitors\MyCompetitorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMyCompetitor extends EditRecord
{
    protected static string $resource = MyCompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
