<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'tuition_id',
        'amount',
        'payment_date',
        'status'
    ];

    public function tuition()
    {
        return $this->belongsTo(Tuition::class);
    }
}
