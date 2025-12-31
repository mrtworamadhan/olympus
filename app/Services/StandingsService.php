<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Competitor;
use App\Models\GameMatch;

class StandingsService
{
    /**
     * * @param EventCategory $category
     * @return array
     */
    public function getStandings(Category $category)
    {
        $teams = Competitor::where('category_id', $category->id)
            ->where('status', 'approved')
            ->whereNotNull('group_name') 
            ->where('group_name', '!=', '')
            ->orderBy('group_name', 'asc') 
            ->get();
        
        $standings = [];

        foreach ($teams as $team) {
            $stats = $this->calculateTeamStats($team, $category->id);
            
            $team->stats = $stats;
            
            $standings[$team->group_name][] = $team;
        }

        foreach ($standings as $group => $teamsInGroup) {
            $standings[$group] = collect($teamsInGroup)->sort(function ($a, $b) {
                if ($a->stats['points'] !== $b->stats['points']) {
                    return $b->stats['points'] <=> $a->stats['points'];
                }
                if ($a->stats['goal_diff'] !== $b->stats['goal_diff']) {
                    return $b->stats['goal_diff'] <=> $a->stats['goal_diff'];
                }
                return $b->stats['goals_for'] <=> $a->stats['goals_for'];
            })->values();
        }

        ksort($standings);

        return $standings;
    }

    public function calculateTeamStats($team, $categoryId)
    {
        $stats = [
            'played' => 0,
            'won' => 0,
            'drawn' => 0,
            'lost' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'goal_diff' => 0,
            'points' => 0,
        ];

        $games = GameMatch::where('status', 'finished')
            ->where('category_id', $categoryId)
            ->where(function($q) use ($team) {
                $q->where('home_competitor_id', $team->id)
                  ->orWhere('away_competitor_id', $team->id);
            })
            ->get();

        foreach ($games as $game) {
            $stageName = $game->meta_data['group_stage'] ?? '';
            if (strlen($stageName) > 1) { 
                continue;
            }

            $stats['played']++;
            
            $isHome = $game->home_competitor_id == $team->id;
            
            $myScore = $isHome ? $game->home_score : $game->away_score;
            $enemyScore = $isHome ? $game->away_score : $game->home_score;

            $stats['goals_for'] += $myScore;
            $stats['goals_against'] += $enemyScore;

            if ($myScore > $enemyScore) {
                $stats['won']++;
                $stats['points'] += 3;
            } elseif ($myScore == $enemyScore) {
                $stats['drawn']++;
                $stats['points'] += 1;
            } else {
                $stats['lost']++;
            }
        }

        $stats['goal_diff'] = $stats['goals_for'] - $stats['goals_against'];

        return $stats;
    }
}