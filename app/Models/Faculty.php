<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'faculty_general_id',
        'code',
        'name'
    ];

    public function facultyGeneral()
    {
        return $this->belongsTo(FacultyGeneral::class, 'faculty_general_id');
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
