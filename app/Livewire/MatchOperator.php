<?php

namespace App\Livewire;

use App\Models\GameMatch;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Services\TournamentService;
use Livewire\Component;
use Livewire\Attributes\Layout;

class MatchOperator extends Component
{
    public GameMatch $game;
    
    public $homeScore;
    public $awayScore;
    public $homeFouls;
    public $awayFouls;
    public $period;
    public $status;
    public $timerSeconds; 
    public $timerRunning = false;

    public $homeRedCards;
    public $homeYellowCards;
    public $awayRedCards;
    public $awayYellowCards;

    public $showEventModal = false;
    public $eventType = ''; 
    public $selectedTeamId = null;
    public $selectedPlayerId = ''; 
    public $eventMinute = 0;

    public $showPenaltyModal = false;
    public $penHome = 0;
    public $penAway = 0;

    #[Layout('components.layouts.app')] 

    public function mount(GameMatch $game)
    {
        $user = auth()->user();
    
        $myOfficialId = $user->official->id ?? null;

        if ($game->referee_id !== $myOfficialId && $game->operator_id !== $myOfficialId) {
            
            if ($user->role !== 'admin') {
                abort(403, 'ANDA BUKAN PETUGAS DI MATCH INI');
            }
        }
        $this->game = $game->load(['homeCompetitor', 'awayCompetitor', 'events.player']);
        $this->syncState();
    }

    public function syncState()
    {
        $this->game->refresh();

        $this->homeScore = $this->game->home_score;
        $this->awayScore = $this->game->away_score;
        $this->status = $this->game->status;
        
        $meta = $this->game->meta_data ?? [];

        $this->homeFouls = $meta['home_fouls'] ?? 0;
        $this->awayFouls = $meta['away_fouls'] ?? 0;
        $this->homeRedCards = $meta['home_red_cards'] ?? 0;
        $this->homeYellowCards = $meta['home_yellow_cards'] ?? 0;
        $this->awayRedCards = $meta['away_red_cards'] ?? 0;
        $this->awayYellowCards = $meta['away_yellow_cards'] ?? 0;

        $this->period = $meta['period'] ?? 1;
        $defaultDuration = ($this->game->category->rules_settings['period_duration'] ?? 15) * 60;
        $this->timerSeconds = $meta['timer_seconds'] ?? $defaultDuration;
        $this->timerRunning = ($this->status === 'live');
    }

    public function syncTimer($seconds)
    {
        $meta = $this->game->meta_data ?? [];
        $meta['timer_seconds'] = $seconds;
        $this->game->update(['meta_data' => $meta]);
    }

    public function toggleTimer($currentSeconds = null)
    {
        if ($currentSeconds !== null) {
            $meta = $this->game->meta_data ?? [];
            $meta['timer_seconds'] = (int) $currentSeconds;
            $this->game->update(['meta_data' => $meta]);
            
            $this->timerSeconds = (int) $currentSeconds;
        }

        if ($this->game->status === 'scheduled') {
            $this->updateStatus('live');
        } elseif ($this->game->status === 'live') {
            $this->updateStatus('break');
        } else {
            $this->updateStatus('live');
        }
        
        $this->timerRunning = ($this->game->status === 'live');
    }


    public function addGoal($team, $amount = 1)
    {
        if ($team === 'home') {
            $this->game->increment('home_score', $amount);
        } else {
            $this->game->increment('away_score', $amount);
        }
        $this->syncState();
    }

    public function addFoul($team)
    {
        $meta = $this->game->meta_data ?? [];
        $currentFoul = $meta["{$team}_fouls"] ?? 0;
        $meta["{$team}_fouls"] = $currentFoul + 1;
        
        $this->game->update(['meta_data' => $meta]);
        
        $this->syncState();
    }

    public function addCard($team, $type)
    {
        $meta = $this->game->meta_data ?? [];
        $key = "{$team}_{$type}_cards";
        
        $current = $meta[$key] ?? 0;
        $meta[$key] = $current + 1;
        
        $this->game->update(['meta_data' => $meta]);
        $this->syncState();
    }

    public function changePeriod($amount)
    {
        $meta = $this->game->meta_data ?? [];
        $currentPeriod = $meta['period'] ?? 1;
        
        $newPeriod = $currentPeriod + $amount;
        if($newPeriod < 1) $newPeriod = 1;

        $meta['period'] = $newPeriod;

        if ($amount > 0) {
            $meta['home_fouls'] = 0;
            $meta['away_fouls'] = 0;
            $defaultDuration = ($this->game->category->rules_settings['period_duration'] ?? 15) * 60;
            $meta['timer_seconds'] = $defaultDuration;
        }

        $this->game->update(['meta_data' => $meta]);
        $this->syncState();
    }
    
    public function updateStatus($newStatus)
    {
        $stageName = $this->game->meta_data['group_stage'] ?? 'A';
        $isKnockout = strlen($stageName) > 1;

        if ($newStatus === 'finished' && 
            $isKnockout &&
            $this->game->home_score == $this->game->away_score 
        ) {
            $this->showPenaltyModal = true;
            return;
        }

        $this->game->update(['status' => $newStatus]);
        $this->status = $newStatus;

        if ($newStatus === 'finished') {
            if (!$isKnockout) {
                (new TournamentService())->handleMatchFinished($this->game);
            } 
            // Jika knockout, majukan pemenang
            else {
                $this->advanceWinnerToNextRound();
            }
        }
    }

    // public function finishWithPenalties()
    // {
    //     $meta = $this->game->meta_data ?? [];
    //     $meta['penalty_home'] = $this->penHome;
    //     $meta['penalty_away'] = $this->penAway;
        
        
    //     $this->game->update([
    //         'meta_data' => $meta,
    //         'status' => 'finished'
    //     ]);
        
    //     (new TournamentService())->handleMatchFinished($this->game);
        
    //     $this->showPenaltyModal = false;
    //     $this->status = 'finished';
    // }

    public function finishWithPenalties()
    {
        $meta = $this->game->meta_data ?? [];
        $meta['penalty_home'] = $this->penHome;
        $meta['penalty_away'] = $this->penAway;
        
        $this->game->update([
            'meta_data' => $meta,
            'status' => 'finished'
        ]);
        
        // Handle logika knockout
        $this->advanceWinnerToNextRound();
        
        $this->showPenaltyModal = false;
        $this->status = 'finished';
    }

    protected function advanceWinnerToNextRound()
    {
        $winnerId = null;
        
        if ($this->game->home_score > $this->game->away_score) {
            $winnerId = $this->game->home_competitor_id;
        } elseif ($this->game->away_score > $this->game->home_score) {
            $winnerId = $this->game->away_competitor_id;
        } 
        else {
            $penHome = $this->game->meta_data['penalty_home'] ?? 0;
            $penAway = $this->game->meta_data['penalty_away'] ?? 0;
            
            if ($penHome > $penAway) $winnerId = $this->game->home_competitor_id;
            elseif ($penAway > $penHome) $winnerId = $this->game->away_competitor_id;
        }

        if (!$winnerId) return; 
        
        $nextMatch = GameMatch::where('category_id', $this->game->category_id)
            ->where(function($query) {
                $query->whereJsonContains('meta_data->prev_match_home_id', $this->game->id)
                      ->orWhereJsonContains('meta_data->prev_match_away_id', $this->game->id);
            })
            ->first();

        if ($nextMatch) {
            $meta = $nextMatch->meta_data;
            
            if (($meta['prev_match_home_id'] ?? null) == $this->game->id) {
                $nextMatch->update(['home_competitor_id' => $winnerId]);
            } 
            elseif (($meta['prev_match_away_id'] ?? null) == $this->game->id) {
                $nextMatch->update(['away_competitor_id' => $winnerId]);
            }
        }
    }

    public function openEventModal($type, $teamId)
    {
        $this->eventType = $type;
        $this->selectedTeamId = $teamId;
        $this->selectedPlayerId = '';
        
        $periodDuration = $this->game->category->rules_settings['period_duration'];
        $totalSeconds = $periodDuration * 60;

        $currentSeconds = $this->game->meta_data['timer_seconds'] ?? $totalSeconds;

        $elapsedThisPeriod = ceil(($totalSeconds - $currentSeconds) / 60);

        if ($elapsedThisPeriod < 1) {
            $elapsedThisPeriod = 1;
        }

        if ($elapsedThisPeriod > $periodDuration) {
            $elapsedThisPeriod = $periodDuration;
        }

        $periodOffset = ($this->period - 1) * $periodDuration;

        $this->eventMinute = $elapsedThisPeriod + $periodOffset;

        $this->showEventModal = true;
    }
    
    public function saveEvent()
    {
        if (!$this->selectedPlayerId) {
            $this->addError('selectedPlayerId', 'Pilih pemain dulu!');
            return;
        }

       MatchEvent::create([
            'game_match_id' => $this->game->id,
            'competitor_id' => $this->selectedTeamId,
            'player_id' => $this->selectedPlayerId,
            'event_type' => $this->eventType,
            'minute' => $this->eventMinute,
        ]);

        if ($this->eventType === 'goal') {
            if ($this->selectedTeamId == $this->game->home_competitor_id) {
                $this->game->increment('home_score');
            } else {
                $this->game->increment('away_score');
            }
        }
        
        if (in_array($this->eventType, ['red_card', 'yellow_card', 'foul'])) {
            $meta = $this->game->meta_data ?? [];
            $key = ($this->selectedTeamId == $this->game->home_competitor_id) ? 'home' : 'away';
            
            if ($this->eventType === 'red_card') {
                $meta["{$key}_red_cards"] = ($meta["{$key}_red_cards"] ?? 0) + 1;
            }
            if ($this->eventType === 'foul') {
                $meta["{$key}_fouls"] = ($meta["{$key}_fouls"] ?? 0) + 1;
            }
            
            $this->game->update(['meta_data' => $meta]);
        }

        $this->showEventModal = false;
        $this->game->refresh();
        $this->game->load(['events.player']);
    }
    public function deleteEvent($eventId)
    {
        $event = MatchEvent::find($eventId);
        if (!$event) return;

        if ($event->event_type === 'goal') {
            if ($event->competitor_id == $this->game->home_competitor_id) {
                $this->game->decrement('home_score');
            } else {
                $this->game->decrement('away_score');
            }
        }

        $meta = $this->game->meta_data ?? [];
        $key = ($event->competitor_id == $this->game->home_competitor_id) ? 'home' : 'away';

        if ($event->event_type === 'red_card') {
            $currentVal = $meta["{$key}_red_cards"] ?? 0;
            $meta["{$key}_red_cards"] = max(0, $currentVal - 1);
        }
        if ($event->event_type === 'foul') {
            $currentVal = $meta["{$key}_fouls"] ?? 0;
            $meta["{$key}_fouls"] = max(0, $currentVal - 1);
        }

        $this->game->update(['meta_data' => $meta]);
        $event->delete();

        $this->game->refresh();
    }

    public function render()
    {
        return view('livewire.match-operator');
    }
}