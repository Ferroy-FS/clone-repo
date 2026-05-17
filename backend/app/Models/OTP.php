<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model {
    protected $fillable = ['email', 'code', 'type', 'is_used', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime', 'is_used' => 'boolean'];
}
