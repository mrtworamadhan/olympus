<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\GameMatch;
use App\Models\Venue;
use App\Models\Official;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

class MatchScheduler extends Component
{
    public Category $category;
    
    public $venues;
    public $referees;
    public $operators;

    public $inputs = []; 

    #[Layout('components.layouts.app')]
    public function mount(Category $category)
    {
        $this->category = $category;
        $this->venues = Venue::where('tenant_id', $category->event->tenant_id)->get();
        
        $this->referees = Official::where('role', 'referee')->get(); 
        $this->operators = Official::where('role', 'operator')->get();
        
        $this->prepareInputs();
    }

    #[Computed]
    public function gamesByGroup()
    {
        return GameMatch::with(['homeCompetitor', 'awayCompetitor', 'venue'])
            ->where('category_id', $this->category->id)
            ->where('status', 'scheduled')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.group_stage')) REGEXP '^[A-Z]$'")
            ->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.group_stage')) ASC") 
            ->orderBy('id', 'asc')
            ->get()
            ->groupBy(function($game) {
                return $game->meta_data['group_stage'];
            });
    }

    #[Computed]
    public function knockoutStages()
    {
        $games = GameMatch::with(['homeCompetitor', 'awayCompetitor', 'venue'])
            ->where('category_id', $this->category->id)
            ->where('status', '!=', 'cancelled')
            ->whereRaw("LENGTH(JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.group_stage'))) > 1") 
            ->get();

        if ($games->isEmpty()) return [];

        $grouped = $games->groupBy(function($game) {
            return $game->meta_data['group_stage'] ?? 'Unknown';
        });

        $stagePriority = [
            'Round of 64' => 64,
            'Round of 32' => 32,
            'Round of 16' => 16,
            'Knockout Stage 1' => 100,
            'Quarter Final (8 Besar)' => 8,
            'Quarter Final' => 8,
            'Semi Final' => 4,
            'Final' => 2,
            '3rd Place' => 0 
        ];

        $sortedStages = $grouped->sortByDesc(function ($games, $key) use ($stagePriority) {
            if (isset($stagePriority[$key])) {
                return $stagePriority[$key];
            }
            return count($games) * 10; 
        });

        return $sortedStages;
    }

    public function prepareInputs()
    {
        $setInput = function($game) {
            if (!isset($this->inputs[$game->id])) {
                $this->inputs[$game->id] = [
                    'venue_id' => $game->venue_id,
                    'referee_id' => $game->referee_id,
                    'operator_id' => $game->operator_id,
                    'date' => $game->scheduled_at ? $game->scheduled_at->format('Y-m-d') : null,
                    'time' => $game->scheduled_at ? $game->scheduled_at->format('H:i') : null,
                ];
            }
        };

        foreach ($this->gamesByGroup->flatten() as $game) {
            $setInput($game);
        }
        foreach ($this->knockoutStages as $stage => $games) {
            foreach($games as $game) {
                $setInput($game);
            }
        }
    }

    
    public function applyVenueToGroup($groupName, $venueId)
    {
        if(!$venueId) return;
        
        $games = $this->gamesByGroup[$groupName] ?? collect();
        
        foreach($games as $game) {
            $this->inputs[$game->id]['venue_id'] = $venueId;
        }
        $this->saveAll(); 
    }

    public function applyOfficialsToGroup($groupName, $type, $officialId)
    {
        if(!$officialId) return;
        $games = $this->gamesByGroup[$groupName] ?? collect();
        
        foreach($games as $game) {
            if ($type === 'referee') {
                $this->inputs[$game->id]['referee_id'] = $officialId;
            } elseif ($type === 'operator') {
                $this->inputs[$game->id]['operator_id'] = $officialId;
            }
        }
    }

    public function applyTimeSequence($groupName, $startDate, $startTime, $matchDuration = 30, $breakDuration = 0)    
    {
        $games = $this->gamesByGroup[$groupName] ?? collect();
        $currentDate = Carbon::parse("$startDate $startTime");

        $matchDuration = (int) $matchDuration; 
        $breakDuration = (int) $breakDuration;
        $totalInterval = $matchDuration + $breakDuration;

        foreach($games as $game) {
            $this->inputs[$game->id]['date'] = $currentDate->format('Y-m-d');
            $this->inputs[$game->id]['time'] = $currentDate->format('H:i');
            
            $currentDate->addMinutes($totalInterval);
        }
    }

    public function saveAll()
    {
        foreach ($this->inputs as $gameId => $data) {
            $dateTime = ($data['date'] && $data['time']) 
                ? Carbon::parse($data['date'] . ' ' . $data['time']) 
                : null;
            
            GameMatch::where('id', $gameId)->update([
                'venue_id' => $data['venue_id'] ?: null,
                'referee_id' => $data['referee_id'] ?: null,
                'operator_id' => $data['operator_id'] ?: null,
                'scheduled_at' => $dateTime,
            ]);
        }
        
        session()->flash('success', 'Jadwal dan Perangkat Pertandingan berhasil disimpan!');
        
        $this->prepareInputs();
    }

    public function moveMatch($gameId, $direction) 
    {
        $currentGame = GameMatch::find($gameId);
        $group = $currentGame->meta_data['group_stage'];
        
        $gamesInGroup = GameMatch::where('category_id', $this->category->id)
            ->where('status', 'scheduled')
            ->whereJsonContains('meta_data->group_stage', $group)
            ->orderBy('scheduled_at')
            ->get();

        $currentIndex = $gamesInGroup->search(function($item) use ($gameId) {
            return $item->id == $gameId;
        });

        if ($currentIndex === false) return;

        $targetIndex = ($direction === 'up') ? $currentIndex - 1 : $currentIndex + 1;

        if (isset($gamesInGroup[$targetIndex])) {
            $targetGame = $gamesInGroup[$targetIndex];

            $timeA = $currentGame->scheduled_at;
            $timeB = $targetGame->scheduled_at;

            $currentGame->update(['scheduled_at' => $timeB]);
            $targetGame->update(['scheduled_at' => $timeA]);

            $this->prepareInputs();
            
            session()->flash('success', 'Urutan pertandingan berhasil ditukar!');
        }
    }

    public function render()
    {
        return view('livewire.match-scheduler', [
            'gamesByGroup' => $this->gamesByGroup,
            'knockoutStages' => $this->knockoutStages,
        ]);
    }
}