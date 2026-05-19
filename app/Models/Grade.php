<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'enrollment_id',
        'score_c',
        'score_b',
        'score_a'
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function getFinalScoreAttribute()
    {
        if ($this->score_a !== null && $this->score_b !== null && $this->score_c !== null) {
            $final = ($this->score_c * 0.1) + ($this->score_b * 0.3) + ($this->score_a * 0.6);
            return round($final, 2);
        }
        return null;
    }
}
