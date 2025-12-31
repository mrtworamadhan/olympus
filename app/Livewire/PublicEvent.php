<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Event;
use App\Models\Game;
use App\Models\GameMatch;
use App\Models\MatchEvent;
use App\Models\Tenant;
use App\Services\StandingsService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;

class PublicEvent extends Component
{
    public Tenant $tenant;
    public Event $event;
    public Category $category;
    
    #[Url(as: 'tab', keep: true)] 
    public $activeTab = 'matches';

    #[Layout('components.layouts.app')] 
    public function mount(Tenant $tenant, Event $event, Category $category)
    {
        $this->tenant = $tenant;
        $this->event = $event;
        $this->category = $category;
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }
    public function getTopScorersProperty()
    {
        return $this->getStatsByType('goal');
    }

    public function getTopYellowCardsProperty()
    {
        return $this->getStatsByType('yellow_card'); 
    }

    public function getTopRedCardsProperty()
    {
        return $this->getStatsByType('red_card');
    }

    private function getStatsByType($type)
    {
        return MatchEvent::query()
            ->select('player_id', DB::raw('count(*) as total'))
            ->where('event_type', $type)
            ->whereHas('game', function($q) {
                $q->where('tenant_id', $this->event->tenant_id)
                  ->where('category_id', $this->category->id);
            })
            ->with(['player.competitor']) 
            ->groupBy('player_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function getKnockoutStagesProperty()
    {
        $games = GameMatch::with(['homeCompetitor', 'awayCompetitor', 'venue'])
            ->where('tenant_id', $this->event->tenant_id)
            ->where('category_id', $this->category->id) 
            ->where('status', '!=', 'cancelled')
            ->whereRaw("LENGTH(JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.group_stage'))) > 1")
            ->orderBy('id')
            ->get();

        $stages = [
            'Quarter Final (8 Besar)' => [],
            'Semi Final' => [],
            'Final' => []
        ];

        foreach($games as $game) {
            $stageName = $game->meta_data['group_stage'] ?? '';
            
            if ($stageName === 'Final') {
                $stages['Final'][] = $game;
            } 
            elseif (str_contains($stageName, 'Semi')) {
                $stages['Semi Final'][] = $game;
            } 
            elseif (str_contains($stageName, 'Quarter') || str_contains($stageName, '8 Besar')) {
                $stages['Quarter Final (8 Besar)'][] = $game;
            }
        }
        
        return array_filter($stages, fn($g) => count($g) > 0);
    }

    public function render(StandingsService $standingsService)
    {
        $standings = (new StandingsService())->getStandings($this->category);

        $games = GameMatch::with(['homeCompetitor', 'awayCompetitor', 'category', 'venue'])
            ->where('tenant_id', $this->event->tenant_id)
            ->where('category_id', $this->category->id)
            ->orderByRaw("FIELD(status, 'live', 'scheduled', 'finished')")
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->groupBy(function($game) {
                return $game->scheduled_at->format('d M Y'); 
            });

        return view('livewire.public-event', [
            'standings' => $standings,
            'groupedGames' => $games,
            'knockoutStages' => $this->knockoutStages 
        ]);
    }
}