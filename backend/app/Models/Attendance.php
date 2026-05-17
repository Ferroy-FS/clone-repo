<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'check_in_time',
        'check_out_time',
        'attendance_type',
        'booking_id'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime'
    ];

    // RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function booking()
    {
        return $this->belongsTo(TrainerBooking::class,'booking_id');
    }

    // HELPER FUNCTIONS

    public function isCheckedOut()
    {
        return $this->check_out_time != null;
    }

    public function durationMinutes()
    {
        if(!$this->check_out_time){
            return null;
        }

        return $this->check_in_time->diffInMinutes($this->check_out_time);
    }

    public function durationHours()
    {
        if(!$this->check_out_time){
            return null;
        }

        return $this->check_in_time->diffInHours($this->check_out_time);
    }

    // SCOPES

    public function scopeToday($query)
    {
        return $query->whereDate('check_in_time', today());
    }

    public function scopeActive($query)
    {
        return $query->whereNull('check_out_time');
    }

}