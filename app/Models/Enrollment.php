<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'schedule_id',
        'enrollment_receipt_id',
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

    public function grade()
    {
        return $this->hasOne(Grade::class);
    }

    public function receipt()
    {
        return $this->belongsTo(EnrollmentReceipt::class, 'enrollment_receipt_id');
    }
}
