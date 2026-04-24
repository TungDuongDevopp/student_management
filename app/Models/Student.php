<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'account_id',
        'classroom_id',
        'student_code',
        'name',
        'email'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function tuitions()
    {
        return $this->hasMany(Tuition::class);
    }
}
