<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerBooking;
use App\Models\Payment;

class BookingController extends Controller
{
    // ================= GET ALL BOOKINGS (ADMIN) =================
    public function allBookings()
    {
        $data = TrainerBooking::with(['trainer', 'member'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // ================= GET BOOKINGS BY MEMBER =================
    public function index($user_id)
    {
        $data = TrainerBooking::where('member_id', $user_id)
            ->with(['trainer'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // ================= GET BOOKINGS BY TRAINER =================
    public function trainerBookings($trainer_id)
    {
        $data = TrainerBooking::where('trainer_id', $trainer_id)
            ->with(['member'])
            ->orderBy('booking_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // ================= CREATE BOOKING =================
   public function store(Request $request)
{
    $request->validate([
        'member_id' => 'required',
        'trainer_id' => 'required',
        'booking_date' => 'required|date'
    ]);

    // CHECK IF USER HAS PAID MEMBERSHIP
    $hasPaid = Payment::where('user_id', $request->member_id)
        ->where('payment_status', 'paid')
        ->whereMonth('payment_date', now()->month)
        ->whereYear('payment_date', now()->year)
        ->exists();

    if (!$hasPaid) {
        return response()->json([
            'status' => false,
            'message' => 'Please pay membership before booking'
        ], 403);
    }

    $booking = TrainerBooking::create([
        'member_id' => $request->member_id,
        'trainer_id' => $request->trainer_id,
        'booking_date' => $request->booking_date,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'session_type' => $request->session_type,
        'location' => $request->location,
        'member_notes' => $request->member_notes,
        'status' => $request->status ?? 'pending',
        'total_price' => $request->total_price,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Booking created successfully',
        'data' => $booking
    ]);
}

    // ================= CANCEL BOOKING =================
    public function cancelBooking($id)
    {
        $booking = TrainerBooking::find($id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $booking->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Booking cancelled'
        ]);
    }
}