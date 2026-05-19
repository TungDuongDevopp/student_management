<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentReceipt extends Model
{
    protected $fillable = [
        'student_id',
        'semester_id',
        'total_credits',
        'total_fee',
        'paid_fee',
        'payment_status'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
