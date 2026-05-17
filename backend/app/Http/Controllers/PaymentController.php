<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\TrainerBooking;

class PaymentController extends Controller
{
    // ================= GET USER PAYMENTS (MEMBER) =================
    public function index(Request $request, $user_id = null)
    {
        $realUserId = $user_id ?? ($request->user()->id ?? null);

        if (!$realUserId) {
            return response()->json([
                'status' => false,
                'message' => 'user_id is required'
            ], 400);
        }

        $payments = Payment::where('user_id', $realUserId)
            ->with('booking')
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $payments
        ]);
    }

    // ================= GET ALL PAYMENTS (ADMIN) =================
    public function allPayments()
    {
        $data = Payment::with('booking')
            ->orderBy('payment_date', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // ================= GET PAYMENTS BY TRAINER =================
    public function trainerPayments($trainer_id)
    {
        $payments = Payment::whereHas('booking', function ($q) use ($trainer_id) {
            $q->where('trainer_id', $trainer_id);
        })
        ->with('booking')
        ->orderBy('payment_date', 'desc')
        ->get();

        return response()->json([
            'status' => true,
            'data' => $payments
        ]);
    }

    // ================= CREATE PAYMENT =================
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'booking_id' => 'required'
        ]);

        $booking = TrainerBooking::find($request->booking_id);

        if (!$booking) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found'
            ], 404);
        }

        $payment = Payment::create([
            'invoice_number' => 'INV-' . time(),
            'user_id' => $request->user_id,
            'booking_id' => $booking->id,
            'payment_type' => 'trainer_booking',
            'amount' => $booking->total_price,
            'payment_status' => 'pending',
            'payment_date' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Payment created',
            'data' => $payment
        ]);
    }

    // ================= CASH PAYMENT =================
    public function payCash($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->update([
            'payment_method' => 'cash',
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Cash payment success',
            'data' => $payment
        ]);
    }

    // ================= TRANSFER PAYMENT =================
    public function payTransfer(Request $request, $id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->update([
            'payment_method' => 'transfer',
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_name' => $request->bank_account_name,
            'transfer_proof_url' => $request->transfer_proof_url,
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Transfer success',
            'data' => $payment
        ]);
    }

    // ================= QRIS PAYMENT =================
    public function payQris($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json([
                'status' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        $payment->update([
            'payment_method' => 'qris',
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'QRIS payment success',
            'data' => $payment
        ]);
    }
}