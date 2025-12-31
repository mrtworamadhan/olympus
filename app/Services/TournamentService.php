<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Game;
use App\Models\GameMatch;
use Illuminate\Support\Str;

class TournamentService
{
    /**
     * Trigger Utama: Dipanggil saat match status berubah jadi 'finished'
     */
    public function handleMatchFinished(GameMatch $game)
    {
        $stage = $game->meta_data['group_stage'] ?? null;

        if (!$stage) return;

        if (strlen($stage) == 1) { 
            $this->processGroupStageFinish($game->category_id, $stage);
        } 
        else {
            $this->processKnockoutProgression($game);
        }
    }

    /**
     * LOGIC 1: Update Bracket jika Grup Selesai
     */
    private function processGroupStageFinish($categoryId, $groupName)
    {
        $category = Category::find($categoryId);
        if (!$category) return;

        $teams = \App\Models\Competitor::where('event_id', $category->event_id)
            ->where('group_name', $groupName)
            ->get();

        $standings = $teams->map(function($team) use ($categoryId) {
            $stats = (new \App\Services\StandingsService())->calculateTeamStats($team, $categoryId);
            return [
                'id' => $team->id,
                'points' => $stats['points'],
                'goal_diff' => $stats['goal_diff'],
                'goals_for' => $stats['goals_for'],
                'name' => $team->name
            ];
        })->sort(function ($a, $b) {
            if ($a['points'] !== $b['points']) return $b['points'] <=> $a['points'];
            if ($a['goal_diff'] !== $b['goal_diff']) return $b['goal_diff'] <=> $a['goal_diff'];
            return $b['goals_for'] <=> $a['goals_for'];
        })->values();

        $winner = $standings[0] ?? null;
        $runnerUp = $standings[1] ?? null;

        if (!$winner) return;

        $targetGames = GameMatch::where('category_id', $categoryId)
            ->where('status', 'scheduled') // Hanya update yang belum main
            ->whereRaw("LENGTH(JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.group_stage'))) > 1") // Match Knockout
            ->get();

        foreach ($targetGames as $game) {
            $meta = $game->meta_data;
            $updated = false;

            if (isset($meta['placeholder_home'])) {
                if ($meta['placeholder_home'] === "Juara Grup $groupName" && $winner) {
                    $game->home_competitor_id = $winner['id'];
                    $updated = true;
                }
                if ($meta['placeholder_home'] === "Runner Up Grup $groupName" && $runnerUp) {
                    $game->home_competitor_id = $runnerUp['id'];
                    $updated = true;
                }
            }

            if (isset($meta['placeholder_away'])) {
                if ($meta['placeholder_away'] === "Juara Grup $groupName" && $winner) {
                    $game->away_competitor_id = $winner['id'];
                    $updated = true;
                }
                if ($meta['placeholder_away'] === "Runner Up Grup $groupName" && $runnerUp) {
                    $game->away_competitor_id = $runnerUp['id'];
                    $updated = true;
                }
            }

            if ($updated) $game->save();
        }
    }

    /**
     * LOGIC 2: Pemenang Knockout Lanjut ke Babak Berikutnya
     */
    private function processKnockoutProgression(GameMatch $finishedGame)
    {
        $winnerId = null;
        if ($finishedGame->home_score > $finishedGame->away_score) {
            $winnerId = $finishedGame->home_competitor_id;
        } elseif ($finishedGame->away_score > $finishedGame->home_score) {
            $winnerId = $finishedGame->away_competitor_id;
        } else {
            // Kalo Seri, Cek Penalti di Meta Data
            $meta = $finishedGame->meta_data;
            $penHome = $meta['penalty_home'] ?? 0;
            $penAway = $meta['penalty_away'] ?? 0;

            if ($penHome > $penAway) {
                $winnerId = $finishedGame->home_competitor_id;
            } elseif ($penAway > $penHome) {
                $winnerId = $finishedGame->away_competitor_id;
            }
        }

        if (!$winnerId) return;
        $stageName = $finishedGame->meta_data['group_stage'];
        
        $stageGames = GameMatch::where('category_id', $finishedGame->category_id)
            ->whereJsonContains('meta_data->group_stage', $stageName)
            ->orderBy('id')
            ->pluck('id')
            ->values();
            
        $myIndex = $stageGames->search($finishedGame->id);
        $humanIndex = $myIndex + 1;

        $shortCode = '';
        if (Str::contains($stageName, 'Quarter') || Str::contains($stageName, '8 Besar')) $shortCode = 'QF';
        if (Str::contains($stageName, 'Semi')) $shortCode = 'SF';
        
        $targetLabel = "Pemenang $shortCode $humanIndex"; // Contoh: "Pemenang QF 1"

        // 3. Cari Match Masa Depan yang menunggu label ini
        $futureGames = GameMatch::where('category_id', $finishedGame->category_id)
            ->where('status', 'scheduled')
            ->where('id', '>', $finishedGame->id) // Match masa depan pasti ID lebih besar
            ->get();

        foreach ($futureGames as $targetGame) {
            $meta = $targetGame->meta_data;
            $updated = false;

            if (($meta['placeholder_home'] ?? '') === $targetLabel) {
                $targetGame->home_competitor_id = $winnerId;
                $updated = true;
            }
            if (($meta['placeholder_away'] ?? '') === $targetLabel) {
                $targetGame->away_competitor_id = $winnerId;
                $updated = true;
            }

            if ($updated) $targetGame->save();
        }
    }
}