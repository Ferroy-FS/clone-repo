<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $table = 'users';
    protected $primaryKey = 'id';

    public $timestamps = false;

    // MASS ASSIGNMENT
    protected $fillable = [
        'email',
        'full_name',
        'birth_date',
        'phone',
        'profile_picture_url',
        'role_id',
        'created_at',
        'last_login',
        'is_active'
    ];

    // HIDDEN
   
    protected $hidden = [
        // keep empty for now
    ];

    // CASTS

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean'
    ];

   // RELATIONSHIPS
    // login credentials
    public function authentication()
    {
        return $this->hasOne(Authentication::class, 'user_id');
    }

    // member profile
    public function memberDetail()
    {
        return $this->hasOne(MemberDetail::class, 'user_id');
    }

    // trainer profile
    public function trainerDetail()
    {
        return $this->hasOne(TrainerDetail::class, 'user_id');
    }

    // payments
    public function payments()
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    // attendance
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    // bookings
    public function trainerBookings()
    {
        return $this->hasMany(TrainerBooking::class, 'member_id');
    }

    // ROLE HELPERS
   
    public function isAdmin()
    {
        return $this->role_id == 1;
    }

    public function isTrainer()
    {
        return $this->role_id == 2;
    }

    public function isMember()
    {
        return $this->role_id == 3;
    }

   // SCOPES
  
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}