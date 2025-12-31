<?php

namespace App\Filament\App\Resources\Competitors\Pages;

use App\Filament\App\Resources\Competitors\CompetitorResource;
use App\Filament\App\Resources\Competitors\RelationManagers\PlayersRelationManager;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class InfoListCompetitors extends ViewRecord
{
    protected static string $resource = CompetitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    public static function getRelations(): array
    {
        return [
            
            PlayersRelationManager::class,
            
        ];
    }
}
