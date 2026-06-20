<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'name',
        'type',
        'category',
        'days',
        'event_date',
        'order',
        'start_time',
        'end_time',
        'late_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'days' => 'array',
        'event_date' => 'date',
    ];

    public function sessions()
    {
        return $this->hasMany(ActivitySession::class);
    }
}
