<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\GameMatch;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PublicMatch extends Component
{
    public GameMatch $game;
    public Tenant $tenant;
    public Event $event;

    public $timerSeconds;
    public $timerRunning;

    protected $listeners = ['refreshComponent' => '$refresh'];

    #[Layout('components.layouts.app')] 
    public function mount(Tenant $tenant, Event $event, GameMatch $game)
    {
        $this->tenant = $tenant;
        $this->event = $event;
        $this->game = $game;
        
        if($this->game->category->event_id !== $this->event->id) {
            abort(404);
        }

        $this->syncData();
    }
    
    public function rendering() 
    {
        $this->syncData();
    }

    public function syncData()
    {
        $this->game->load(['homeCompetitor', 'awayCompetitor', 'venue', 'events.player']); 
    
        $this->game->refresh();
        
        $meta = $this->game->meta_data ?? [];
        
        $this->timerSeconds = $meta['timer_seconds'] ?? ($this->game->category->rules_settings['period_duration'] * 60);
        $this->timerRunning = ($this->game->status === 'live');
    }

    public function render()
    {
        return view('livewire.public-match');
    }
}