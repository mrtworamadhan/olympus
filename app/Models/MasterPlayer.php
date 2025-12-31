<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterPlayer extends Model
{
    protected $guarded = [];

    public function club(): BelongsTo
    {
        return $this->belongsTo(MasterClub::class, 'master_club_id');
    }

    public function user() { 
        return $this->belongsTo(User::class, 'user_id'); 
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }
}
