<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    public $timestamps = false;
    protected $fillable = [
        'role_id',
        'username',
        'password',
        'is_locked',
        'login_attempts',
        'locked_until'
    ];

    protected $hidden = [
        'password',
        'is_locked',
        'login_attempts',
        'locked_until'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
