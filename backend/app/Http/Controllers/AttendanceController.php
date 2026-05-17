<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\User;

class AttendanceController extends Controller
{
    // ================= GET ALL ATTENDANCE (ADMIN) =================
    public function all()
    {
        $data = Attendance::with('user')
            ->orderBy('check_in_time', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'All attendance retrieved successfully',
            'data' => $data
        ]);
    }

    // ================= GET ATTENDANCE BY USER =================
    public function index($user_id)
    {
        $data = Attendance::where('user_id', $user_id)
            ->orderBy('check_in_time', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'User attendance retrieved successfully',
            'data' => $data
        ]);
    }

    // ================= MY ATTENDANCE (TOKEN USER) =================
    public function myAttendance(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        $data = Attendance::where('user_id', $request->user()->id)
            ->orderBy('check_in_time', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'My attendance retrieved successfully',
            'data' => $data
        ]);
    }

    // ================= CHECK IN =================
    public function checkIn(Request $request)
    {
        $user = $request->user();
        $userId = $user->id ?? $request->user_id;

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'user_id is required'
            ], 400);
        }

        // If token user not found, fallback to manual user_id
        if (!$user) {
            $user = User::find($userId);
        }

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        }

        // ================= MEMBER PAYMENT CHECK =================
        // Only member role must pay membership before gym attendance
        if ($user->role_id == 3) {
            $hasPaid = Payment::where('user_id', $userId)
                ->where('payment_status', 'paid')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->exists();

            if (!$hasPaid) {
                return response()->json([
                    'status' => false,
                    'message' => 'Please pay membership first'
                ], 403);
            }
        }

        // ================= PREVENT DOUBLE CHECK-IN TODAY =================
        $existing = Attendance::where('user_id', $userId)
            ->whereDate('check_in_time', now()->toDateString())
            ->first();

        if ($existing) {
            return response()->json([
                'status' => false,
                'message' => 'You already checked in today'
            ], 400);
        }

        // ================= CREATE ATTENDANCE =================
        $attendance = Attendance::create([
            'user_id' => $userId,
            'check_in_time' => now(),
            'attendance_type' => $user->role_id == 2 ? 'trainer' : 'gym'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-in success',
            'data' => $attendance
        ]);
    }

    // ================= CHECK OUT =================
    public function checkOut(Request $request)
    {
        $userId = $request->user()->id ?? $request->user_id;

        if (!$userId) {
            return response()->json([
                'status' => false,
                'message' => 'user_id is required'
            ], 400);
        }

        // Only today's active attendance can be checked out
        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('check_in_time', now()->toDateString())
            ->whereNull('check_out_time')
            ->latest()
            ->first();

        if (!$attendance) {
            return response()->json([
                'status' => false,
                'message' => 'No active check-in found for today'
            ], 404);
        }

        $attendance->update([
            'check_out_time' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Check-out success',
            'data' => $attendance
        ]);
    }
}