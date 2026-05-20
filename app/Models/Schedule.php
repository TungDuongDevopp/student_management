<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'subject_id',
        'teacher_id',
        'semester_id',
        'group_code',
        'max_capacity',
        'current_capacity',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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

    public function getRoomNamesAttribute()
    {
        return $this->sessions->map(function ($s) {
            return $s->room ? (($s->room->block ? $s->room->block . '.' : '') . $s->room->name) : null;
        })->filter()->unique()->implode(', ') ?: 'Chưa xếp phòng';
    }
}
