<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi
    protected $guarded = [];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
    public function venues(): HasMany
    {
        return $this->hasMany(Venue::class);
    }
}