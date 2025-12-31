<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameMatch extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'meta_data' => 'array', 
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function homeCompetitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class, 'home_competitor_id');
    }

    public function awayCompetitor(): BelongsTo
    {
        return $this->belongsTo(Competitor::class, 'away_competitor_id');
    }
    public function events()
    {
        return $this->hasMany(MatchEvent::class);
    }
    public function referee()
    {
        return $this->belongsTo(Official::class, 'referee_id');
    }

    public function operator()
    {
        return $this->belongsTo(Official::class, 'operator_id');
    }
}