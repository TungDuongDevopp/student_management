<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'faculty_id',
        'code',
        'name',
        'credits'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
