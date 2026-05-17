<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
    protected $table = 'authentications';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email',
        'password_hash',
        'provider',
        'last_login',
        'is_active',
        'failed_login_attempts',
        'locked_until',
        'password_updated_at'
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'locked_until' => 'datetime',
        'password_updated_at' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isLocked()
    {
        if (!$this->locked_until) {
            return false;
        }

        return now()->lessThan($this->locked_until);
    }

    public function resetLoginAttempts()
    {
        $this->failed_login_attempts = 0;
        $this->save();
    }
}