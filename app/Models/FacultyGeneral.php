<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyGeneral extends Model
{
    protected $fillable = [
        'name',
        'max_credits',
        'tuition_fee_per_credit'
    ];

    public function faculties()
    {
        return $this->hasMany(Faculty::class, 'faculty_general_id');
    }
}
