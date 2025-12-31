<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Competitor extends Model
{
    protected $guarded = [];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
    public function event() { 
        return $this->belongsTo(Event::class); 
    }
    public function players()
    {
        return $this->hasMany(Player::class);
    }
    public function games()
    {
        return $this->hasMany(GameMatch::class);
    }
    public function matches()
    {
        return $this->hasMany(GameMatch::class, 'home_competitor_id'); 
    }
}
