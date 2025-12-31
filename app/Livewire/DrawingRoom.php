<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Competitor;
use App\Models\GameMatch;
use App\Models\Venue;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;

class DrawingRoom extends Component
{
    public Category $category;
    public $groups = [];
    public $unassignedTeams = [];
    public $meetFormat = 'single';
    public $isAnimating = false;

    #[Layout('components.layouts.app')] 
    public function mount(Category $category)
    {
        $this->category = $category;
        $this->loadTeams();
    }

    public function loadTeams()
    {
        $teams = Competitor::where('category_id', $this->category->id)
                            ->where('status', 'approved')
                            ->get();
        
        $this->groups = [];
        $this->unassignedTeams = [];

       if ($this->category->format_type == 'knockout') {
            $this->unassignedTeams = $teams;
        } else {
            $letters = range('A', 'Z');
            for ($i = 0; $i < $this->category->total_groups; $i++) {
                $groupName = $letters[$i];
                $this->groups[$groupName] = [];
            }

            foreach ($teams as $team) {
                if (in_array($team->group_name, array_keys($this->groups))) {
                    $this->groups[$team->group_name][] = $team;
                } else {
                    $this->unassignedTeams[] = $team;
                }
            }
        }
    }

    public function autoDraw()
    {
        $allTeams = Competitor::where('event_id', $this->category->event_id)->get();
        $shuffled = $allTeams->shuffle();
        if ($this->category->format_type == 'knockout') {
            session()->flash('success', 'Urutan tim diacak! Siap generate bracket.');
        } else {
            $groupKeys = array_keys($this->groups);
            $totalGroups = count($groupKeys);
            
            foreach ($shuffled as $index => $team) {
                $targetGroup = $groupKeys[$index % $totalGroups];
                $team->update(['group_name' => $targetGroup]);
            }
        }
        
        $this->loadTeams();
        if($this->category->format_type != 'knockout') {
            session()->flash('success', 'Drawing Otomatis Selesai!');
        }
    }

    public function resetDrawing()
    {
        Competitor::where('category_id', $this->category->id)
            ->update(['group_name' => null]);
        $this->loadTeams();
    }

    public function moveTeam($teamId, $targetGroup)
    {
        $team = Competitor::find($teamId);
        $team->update(['group_name' => $targetGroup ?: null]); 
        $this->loadTeams();
    }

    public function generateSchedule()
    {
        if ($this->category->format_type == 'knockout') {
            return $this->generatePureKnockoutSchedule();
        }

        $unassignedCount = Competitor::where('event_id', $this->category->event_id)
            ->where('status','approved')
            ->whereNull('group_name')
            ->count();

        if ($unassignedCount > 0) {
            session()->flash('error', 'Semua tim harus masuk grup dulu sebelum generate jadwal!');
            return;
        }

        $defaultVenue = Venue::where('tenant_id', $this->category->event->tenant_id)->first();
        $startDate = Carbon::parse($this->category->event->start_date)->addDay()->setHour(8);
        $totalMatchesCreated = 0;

        foreach ($this->groups as $groupName => $teams) {
            
            $teamIds = collect($teams)->pluck('id')->values()->toArray();
            
            if (count($teamIds) % 2 != 0) {
                $teamIds[] = null; 
            }

            $numTeams = count($teamIds);
            if ($numTeams < 2) continue;

            $totalRounds = $numTeams - 1;
            $matchesPerRound = $numTeams / 2;

            $currentTeamIds = $teamIds; 
            
            for ($round = 0; $round < $totalRounds; $round++) {
                
                for ($match = 0; $match < $matchesPerRound; $match++) {
                    $homeId = $currentTeamIds[$match];
                    $awayId = $currentTeamIds[$numTeams - 1 - $match];

                    if ($homeId !== null && $awayId !== null) {
                        $this->createMatch($homeId, $awayId, $defaultVenue, $startDate, $groupName);
                        $totalMatchesCreated++;
                        $startDate->addHour();
                    }
                }
                
                $fixed = $currentTeamIds[0];
                $rotators = array_slice($currentTeamIds, 1);
                array_unshift($rotators, array_pop($rotators)); 
                $currentTeamIds = array_merge([$fixed], $rotators);
            }

            if ($this->meetFormat === 'double') {
                
                $currentTeamIds = $teamIds; 

                $startDate->addDays(2)->setHour(8);

                for ($round = 0; $round < $totalRounds; $round++) {
                    
                    for ($match = 0; $match < $matchesPerRound; $match++) {
                        $homeId = $currentTeamIds[$match];
                        $awayId = $currentTeamIds[$numTeams - 1 - $match];

                        if ($homeId !== null && $awayId !== null) {
                            $this->createMatch($awayId, $homeId, $defaultVenue, $startDate, $groupName);
                            $totalMatchesCreated++;
                            $startDate->addHour();
                        }
                    }
                    
                    $fixed = $currentTeamIds[0];
                    $rotators = array_slice($currentTeamIds, 1);
                    array_unshift($rotators, array_pop($rotators));
                    $currentTeamIds = array_merge([$fixed], $rotators);
                }
            }
        }

        $this->generateKnockoutBracket($defaultVenue, $startDate);

        return redirect()->route('match.scheduler', ['category' => $this->category->id])
            ->with('success', "Sukses! $totalMatchesCreated jadwal pertandingan dibuat.");
    }

    private function generateKnockoutBracket($venue, $startDate)
    {
        $startDate->addDays(2)->setHour(15); 

        $format = $this->category->knockout_stage_type;
        $totalGroups = count($this->groups);
        
        if ($totalGroups == 2) {
            $bracketName = 'Semi Final';
            
            $this->createPlaceholderMatch('Juara Grup A', 'Runner Up Grup B', $bracketName, $venue, $startDate);
            
            $startDate->addHour();
            $this->createPlaceholderMatch('Juara Grup B', 'Runner Up Grup A', $bracketName, $venue, $startDate);
            
            $startDate->addDay();
            $this->createPlaceholderMatch('Pemenang SF 1', 'Pemenang SF 2', 'Final', $venue, $startDate);
        }
        
        elseif ($totalGroups == 4) {
            $bracketName = 'Quarter Final (8 Besar)';
            
            $this->createPlaceholderMatch('Juara Grup A', 'Runner Up Grup B', $bracketName, $venue, $startDate); $startDate->addHour();
            $this->createPlaceholderMatch('Juara Grup C', 'Runner Up Grup D', $bracketName, $venue, $startDate); $startDate->addHour();
            $this->createPlaceholderMatch('Juara Grup B', 'Runner Up Grup A', $bracketName, $venue, $startDate); $startDate->addHour();
            $this->createPlaceholderMatch('Juara Grup D', 'Runner Up Grup C', $bracketName, $venue, $startDate);
            
            $startDate->addDay();
            $this->createPlaceholderMatch('Pemenang QF 1', 'Pemenang QF 2', 'Semi Final', $venue, $startDate);
            $this->createPlaceholderMatch('Pemenang QF 3', 'Pemenang QF 4', 'Semi Final', $venue, $startDate);
            
            $startDate->addDay();
            $this->createPlaceholderMatch('Pemenang SF 1', 'Pemenang SF 2', 'Final', $venue, $startDate);
        }
    }

    public function generatePureKnockoutSchedule()
    {
        $teams = Competitor::where('category_id', $this->category->id)
            ->where('status', 'approved')
            ->inRandomOrder()
            ->get();

        $count = $teams->count();
        if ($count < 2) {
            session()->flash('error', 'Minimal butuh 2 tim untuk sistem gugur.');
            return;
        }
        
        $defaultVenue = Venue::where('tenant_id', $this->category->event->tenant_id)->first();
        $startDate = Carbon::parse($this->category->event->start_date)->addDay()->setHour(8);
        
        $currentStageName = $this->getStageNameByTeamCount($count);

        $matchesCreated = 0;

        $currentRoundMatches = [];

        for ($i = 0; $i < $count; $i += 2) {
            if (isset($teams[$i]) && isset($teams[$i+1])) {
                $match = GameMatch::create([
                    'tenant_id' => $this->category->event->tenant_id,
                    'category_id' => $this->category->id,
                    'event_id' => $this->category->event->id,
                    'venue_id' => $defaultVenue?->id,
                    'home_competitor_id' => $teams[$i]->id,
                    'away_competitor_id' => $teams[$i+1]->id,
                    'scheduled_at' => $startDate->copy(),
                    'status' => 'scheduled',
                    'meta_data' => [
                        'group_stage' => $currentStageName,
                        'knockout_number' => count($currentRoundMatches) + 1 
                    ]
                ]);
                
                $currentRoundMatches[] = $match;
                $startDate->addHour();
            } else {
                // Handle BYE jika ganjil (Logic sederhana: Loloskan langsung/Skip)
                // Untuk sekarang kita asumsikan jumlah genap dulu biar rapi
            }
        }

        while (count($currentRoundMatches) > 1) {
            
            $nextRoundMatches = [];
            
            $startDate->addDay()->setHour(8); 

            $nextMatchCount = count($currentRoundMatches) / 2;
            $nextStageName = $this->getStageNameByMatchCount($nextMatchCount);

            for ($i = 0; $i < count($currentRoundMatches); $i += 2) {
                
                $prevMatch1 = $currentRoundMatches[$i] ?? null;
                $prevMatch2 = $currentRoundMatches[$i+1] ?? null;

                if ($prevMatch1 && $prevMatch2) {
                    
                    $placeholderMatch = GameMatch::create([
                        'tenant_id' => $this->category->event->tenant_id,
                        'category_id' => $this->category->id,
                        'event_id' => $this->category->event->id,
                        'venue_id' => $defaultVenue?->id,
                        'home_competitor_id' => null,
                        'away_competitor_id' => null,
                        'scheduled_at' => $startDate->copy(),
                        'status' => 'scheduled',
                        'meta_data' => [
                            'group_stage' => $nextStageName,
                            
                            'prev_match_home_id' => $prevMatch1->id,
                            'prev_match_away_id' => $prevMatch2->id,
                            
                            'placeholder_home' => '-',
                            'placeholder_away' => '-',
                        ]
                    ]);

                    $nextRoundMatches[] = $placeholderMatch;
                    $startDate->addHour();
                }
            }

            $currentRoundMatches = $nextRoundMatches;
        }

        return redirect()->route('match.scheduler', ['category' => $this->category->id])
            ->with('success', "Drawing Knockout Selesai! Bracket terbentuk sempurna sampai Final.");
    }

    private function getStageNameByTeamCount($count)
    {
        if ($count > 16) return 'Round of 32';
        if ($count > 8) return 'Round of 16';
        if ($count > 4) return 'Quarter Final (8 Besar)';
        if ($count > 2) return 'Semi Final';
        return 'Final';
    }

    private function getStageNameByMatchCount($count)
    {
        if ($count == 8) return 'Round of 16';
        if ($count == 4) return 'Quarter Final (8 Besar)';
        if ($count == 2) return 'Semi Final';
        if ($count == 1) return 'Final';
        return 'Knockout Round';
    }

    private function generateNextKnockoutLayers($currentStage, $matchCount, $venue, $date)
    {
        if ($currentStage == 'Quarter Final (8 Besar)') {
            $date->addDay();
            $this->createPlaceholderMatch('Pemenang QF 1', 'Pemenang QF 2', 'Semi Final', $venue, $date);
            $this->createPlaceholderMatch('Pemenang QF 3', 'Pemenang QF 4', 'Semi Final', $venue, $date);
            
            $date->addDay();
            $this->createPlaceholderMatch('Pemenang SF 1', 'Pemenang SF 2', 'Final', $venue, $date);
        }
    }

    private function createPlaceholderMatch($homeLabel, $awayLabel, $stageName, $venue, $date)
    {
        GameMatch::create([
            'tenant_id' => $this->category->event->tenant_id,
            'category_id' => $this->category->id,
            'venue_id' => $venue?->id,
            'home_competitor_id' => null,
            'away_competitor_id' => null,
            'home_score' => 0,
            'away_score' => 0,
            'scheduled_at' => $date->copy(),
            'status' => 'scheduled',
            'meta_data' => [
                'group_stage' => $stageName,
                'placeholder_home' => $homeLabel,
                'placeholder_away' => $awayLabel,
            ]
        ]);
    }

    private function createMatch($homeId, $awayId, $venue, $date, $group)
    {
        GameMatch::create([
            'tenant_id' => $this->category->event->tenant_id,
            'category_id' => $this->category->id,
            'venue_id' => $venue?->id,
            'home_competitor_id' => $homeId,
            'away_competitor_id' => $awayId,
            'scheduled_at' => $date,
            'status' => 'scheduled',
            'meta_data' => ['group_stage' => $group, 'period' => 1]
        ]);
    }

    public function render()
    {
        return view('livewire.drawing-room');
    }
}