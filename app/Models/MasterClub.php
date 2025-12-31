<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterClub extends Model
{
    protected $guarded = [];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function players()
    {
        return $this->hasMany(MasterPlayer::class);
    }
}
