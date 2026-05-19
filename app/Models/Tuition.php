<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tuition extends Model
{
    protected $fillable = [
        'student_id',
        'semester_id',
        'total_amount',
        'paid_amount'
    ];

    protected $appends = ['total_credits'];

    public function getTotalCreditsAttribute()
    {
        // Load relation if not already loaded to prevent N+1 queries, but default is fine
        return $this->enrollments->sum(function ($enrollment) {
            return $enrollment->schedule?->subject?->credits ?? 0;
        });
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'tuition_id');
    }
}
