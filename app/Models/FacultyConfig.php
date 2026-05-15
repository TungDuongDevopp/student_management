<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyConfig extends Model
{
    protected $fillable = [
        'faculty_id',
        'max_credits',
        'tuition_fee_per_credit'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
