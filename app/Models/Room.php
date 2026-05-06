<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'block',
        'name',
        'description'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
