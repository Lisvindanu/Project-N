<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'video_id',
        'season_number',
        'name',
        'overview',
        'episode_count',
        'poster_path',
        'air_date',
    ];

    protected $casts = [
        'air_date' => 'date',
    ];

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number');
    }
}
