<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainerEarning;
use App\Models\TrainerBooking;
use App\Models\Payment;
use App\Models\User;

class TrainerEarningController extends Controller
{
    
    //  EARNINGS (TRAINER)
    public function myEarnings()
    {
        $user = auth()->user();

        if (!$user || !$user->isTrainer()) {
            return response()->json([
                'message' => 'Unauthorized - Trainer only'
            ], 403);
        }

        $earnings = TrainerEarning::with(['payment', 'booking'])
            ->where('trainer_id', $user->id)
            ->get();

        return response()->json([
            'status' => true,
            'total_earnings' => $earnings->sum('trainer_amount'),
            'total_sessions' => $earnings->count(),
            'data' => $earnings
        ]);
    }

    //  GENERATE EARNING FROM PAYMENT
    public function generateFromPayment($paymentId)
    {
        $payment = Payment::with('booking')->find($paymentId);

        if (!$payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        if ($payment->payment_status !== 'paid') {
            return response()->json(['message' => 'Payment not completed'], 400);
        }

        $booking = $payment->booking;

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        // Prevent duplicate
        $exists = TrainerEarning::where('payment_id', $payment->id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Already recorded'], 400);
        }

        // Commission logic (example 50%)
        $commissionRate = 0.5;
        $trainerAmount = $payment->amount * $commissionRate;

        $earning = TrainerEarning::create([
            'trainer_id' => $booking->trainer_id,
            'payment_id' => $payment->id,
            'booking_id' => $booking->id,
            'commission_rate' => $commissionRate,
            'trainer_amount' => $trainerAmount,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'your Earning was successfully created',
            'data' => $earning
        ]);
    }

    // 3. EARNINGS BY TRAINER (ADMIN)
    public function trainerEarnings($trainerId)
    {
        $trainer = User::find($trainerId);

        if (!$trainer || !$trainer->isTrainer()) {
            return response()->json([
                'message' => 'Trainer not found'
            ], 404);
        }

        $earnings = TrainerEarning::where('trainer_id', $trainerId)->get();

        return response()->json([
            'trainer' => $trainer,
            'total_earnings' => $earnings->sum('trainer_amount'),
            'total_sessions' => $earnings->count(),
            'data' => $earnings
        ]);
    }
    // 4. ALL TRAINERS (ADMIN DASHBOARD)
    public function allEarnings()
    {
        $trainers = User::where('role_id', 2)->get();

        $result = [];

        foreach ($trainers as $trainer) {

            $earnings = TrainerEarning::where('trainer_id', $trainer->id)->get();

            $result[] = [
                'trainer_id' => $trainer->id,
                'trainer_name' => $trainer->full_name,
                'total_earnings' => $earnings->sum('trainer_amount'),
                'total_sessions' => $earnings->count()
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $result
        ]);
    }

    // 5. FILTER BY DATE
    public function earningsByDate(Request $request)
    {
        $query = TrainerEarning::with('booking');

        if ($request->trainer_id) {
            $query->where('trainer_id', $request->trainer_id);
        }

        if ($request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $data = $query->get();

        return response()->json([
            'total_earnings' => $data->sum('trainer_amount'),
            'total_sessions' => $data->count(),
            'data' => $data
        ]);
    }
}