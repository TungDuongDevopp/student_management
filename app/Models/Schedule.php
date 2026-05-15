<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'room_id',
        'semester_id',
        'group_code',
        'max_capacity'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }


    public function sessions()
    {
        return $this->hasMany(ScheduleSession::class)->orderBy('day_of_week')->orderBy('start_time');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
