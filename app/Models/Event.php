<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Casting: Pastikan Laravel baca kolom tanggal sebagai Date object, bukan string biasa
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function games(): HasMany
    {
        return $this->hasMany(GameMatch::class);
    }

    public function galleries()
    {
        return $this->hasMany(EventGallery::class)->orderBy('sort_order');
    }

    public function sponsors()
    {
        return $this->hasMany(EventSponsor::class);
    }
}