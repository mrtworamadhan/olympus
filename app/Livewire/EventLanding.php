<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\Attributes\Layout;

class EventLanding extends Component
{
    public Tenant $tenant;
    public Event $event;

    #[Url(as: 'team', keep: true)] 
    public $activeTeamId = '';

    public $showTeamModal = false;
    public $selectedTeam = null;

    #[Layout('components.layouts.app')]
    public function mount(Tenant $tenant, Event $event)
    {
        $this->tenant = $tenant;
        $this->event = $event;
        $this->event->load(['galleries', 'sponsors']);
        if (request()->query('team')) {
            $this->openTeamModal(request()->query('team'));
        }
    }

    public function render()
    {
        $competitors = \App\Models\Competitor::where('event_id', $this->event->id)
            ->where('status', 'approved') // HANYA YANG APPROVED
            ->with('category') // Load relasi kategori biar bisa ditampilkan
            ->latest()
            ->get();
        return view('livewire.event-landing', [
            'categories' => $this->event->categories()->withCount(['competitors', 'matches'])->get(),
            'competitors' => $competitors
        ]);
    }
    public function openTeamModal($teamId)
    {
        $this->activeTeamId = $teamId;
        
        $this->selectedTeam = \App\Models\Competitor::with(['category', 'players' => function($query) {
            $query->orderBy('number', 'asc');
        }])->find($teamId);

        if ($this->selectedTeam) {
            $this->showTeamModal = true;
        }
    }

    public function closeTeamModal()
    {
        $this->showTeamModal = false;
        $this->selectedTeam = null;
        $this->activeTeamId = '';
    }
}