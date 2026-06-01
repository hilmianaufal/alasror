<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityAttendance extends Model
{
    protected $fillable = [
        'activity_session_id',
        'student_id',
        'scanned_at',
        'status',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(ActivitySession::class, 'activity_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

}