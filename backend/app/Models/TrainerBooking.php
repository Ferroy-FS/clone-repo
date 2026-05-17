<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerBooking extends Model
{
    protected $table = 'trainer_bookings';

    protected $fillable = [
        'member_id',
        'trainer_id',
        'booking_date',
        'start_time',
        'end_time',
        'session_type',
        'location',
        'member_note',
        'total_price',
        'status'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'total_price' => 'decimal:2'
    ];

    //  RELATION: Booking belongs to user (member)
    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    // RELATION: Booking belongs to trainer
    public function trainer()
    {
        return $this->belongsTo(user::class, 'trainer_id');
    }

    // RELATION: Booking has one payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id');
    }
}

