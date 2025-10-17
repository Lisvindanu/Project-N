<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'season_id',
        'episode_number',
        'name',
        'overview',
        'still_path',
        'air_date',
        'runtime',
        'vote_average',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
