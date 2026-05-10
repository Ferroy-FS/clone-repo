<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerBooking extends Model
{
    protected $table = 'trainer_bookings';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'member_id',
        'trainer_id',
        'booking_date',
        'start_time',
        'end_time',
        'session_type',
        'location',
        'member_notes',
        'status',
        'total_price',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function trainer()
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }
}
