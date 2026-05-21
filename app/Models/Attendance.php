<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'enrollment_id',
        'schedule_session_id',
        'attendance_date',
        'status'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function scheduleSession()
    {
        return $this->belongsTo(ScheduleSession::class);
    }
}
