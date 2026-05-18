<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'schedule_id',
        'score_c',
        'score_b',
        'score_a',
        'final_score',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
