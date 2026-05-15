<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'code',
        'name'
    ];

    public function config()
    {
        return $this->hasOne(FacultyConfig::class);
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
