<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSession extends Model
{
    protected $fillable = [
        'schedule_id',
        'room_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
