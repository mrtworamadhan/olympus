<?php

namespace App\Filament\Team\Resources\MyCompetitors\Widgets;

use Filament\Widgets\Widget;
use App\Models\Competitor;
use App\Services\StandingsService;

class EventStandingsWidget extends Widget
{
    protected string $view = 'filament.team.resources.my-competitors.widgets.event-standings-widget';

    
    public ?Competitor $record = null;

    public $standings =[];

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        if (!$this->record) return [];

        $standings = [];
        
        if ($this->record->event) {
            $service = new StandingsService(); 
            $standings = $service->getStandings($this->record->event);
            
        }

        return [
            'standings' => $standings,
            'myTeamId' => $this->record->id, 
        ];
    }

}
