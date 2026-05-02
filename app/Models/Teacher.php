<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'account_id',
        'faculty_id',
        'teacher_code',
        'name',
        'email',
        'images'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
