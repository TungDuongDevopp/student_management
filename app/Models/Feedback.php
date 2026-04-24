<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks';

    protected $fillable = [
        'account_id',
        'content',
        'created_at'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
