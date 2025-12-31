<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchEvent extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(GameMatch::class, 'game_match_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function competitor()
    {
        return $this->belongsTo(Competitor::class);
    }
}