<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerDetail extends Model
{
    protected $table = 'trainer_details';

    protected $fillable = [
        'user_id',
        'specialization',
        'biography',
        'experience_years',
        'hourly_rate',
        'avg_rating',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'avg_rating' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(TrainerBooking::class, 'trainer_id');
    }

    public function earnings()
    {
        return $this->hasMany(TrainerEarning::class, 'trainer_id');
    }
}