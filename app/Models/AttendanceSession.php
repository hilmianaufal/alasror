<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $fillable = ['date','prayer_id','status'];

    protected $casts = [
        'date' => 'date',
    ];

    public function prayer()
    {
        return $this->belongsTo(Prayer::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }


}
