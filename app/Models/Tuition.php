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
