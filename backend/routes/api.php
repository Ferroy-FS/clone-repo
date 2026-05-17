<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrainerEarningController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\OtpController;

// TEST
Route::get('/test', function () {
    return response()->json([
        'status' => 'active',
        'message' => 'Your Gym API is working successfully'
    ]);
});

// AUTH
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// OTP ROUTES (PUBLIC)
Route::prefix('otp')->group(function () {
    Route::post('/login/send',    [OtpController::class, 'sendLoginOtp']);
    Route::post('/login/verify',  [OtpController::class, 'verifyLoginOtp']);
    Route::post('/forgot/send',   [OtpController::class, 'sendForgotOtp']);
    Route::post('/forgot/verify', [OtpController::class, 'verifyForgotOtp']);
    Route::post('/reset',         [OtpController::class, 'resetPassword']);
});

// AUTHENTICATED USER
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return response()->json([
            'status' => true,
            'user' => $request->user()
        ]);
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // ADMIN
    Route::prefix('admin')->group(function () {
        Route::get('/users', [AdminController::class, 'index']);
        Route::get('/members', [AdminController::class, 'members']);
        Route::get('/trainers', [AdminController::class, 'trainers']);
        Route::get('/users/{id}', [AdminController::class, 'show']);
        Route::post('/users', [AdminController::class, 'store']);
        Route::put('/users/{id}', [AdminController::class, 'update']);
        Route::delete('/users/{id}', [AdminController::class, 'destroy']);
    });

    // MEMBERS
    Route::prefix('members')->group(function () {
        Route::get('/all', [MemberController::class, 'allMembers']);
        Route::get('/trainer/{trainer_id}', [MemberController::class, 'membersByTrainer']);
        Route::get('/{user_id}', [MemberController::class, 'profile']);
        Route::post('/create', [MemberController::class, 'createProfile']);
        Route::put('/update/{user_id}', [MemberController::class, 'updateProfile']);
        Route::delete('/delete/{user_id}', [MemberController::class, 'destroy']);
    });

    // TRAINERS
    Route::prefix('trainers')->group(function () {
        Route::get('/', [TrainerController::class, 'listTrainers']);
        Route::get('/{id}', [TrainerController::class, 'trainerDetail']);
        Route::post('/create', [TrainerController::class, 'createTrainer']);
        Route::delete('/delete/{user_id}', [TrainerController::class, 'destroy']);
    });

    // BOOKINGS
    Route::prefix('bookings')->group(function () {
        Route::get('/all', [BookingController::class, 'allBookings']);
        Route::get('/user/{user_id}', [BookingController::class, 'index']);
        Route::get('/trainer/{trainer_id}', [BookingController::class, 'trainerBookings']);
        Route::post('/', [BookingController::class, 'store']);
        Route::delete('/cancel/{id}', [BookingController::class, 'cancelBooking']);
    });

    // PAYMENTS
    Route::prefix('payments')->group(function () {
        Route::get('/all', [PaymentController::class, 'allPayments']);
        Route::get('/user/{user_id}', [PaymentController::class, 'index']);
        Route::post('/', [PaymentController::class, 'store']);
        Route::post('/cash/{id}', [PaymentController::class, 'payCash']);
        Route::post('/transfer/{id}', [PaymentController::class, 'payTransfer']);
        Route::post('/qris/{id}', [PaymentController::class, 'payQris']);
    });

    // ATTENDANCE
    Route::prefix('attendance')->group(function () {
        Route::get('/all', [AttendanceController::class, 'all']);
        Route::get('/my', [AttendanceController::class, 'myAttendance']);
        Route::get('/user/{user_id}', [AttendanceController::class, 'index']);
        Route::post('/checkin', [AttendanceController::class, 'checkIn']);
        Route::post('/checkout', [AttendanceController::class, 'checkOut']);
    });

    // TRAINER EARNINGS
    Route::prefix('trainer-earnings')->group(function () {
        Route::get('/my', [TrainerEarningController::class, 'myEarnings']);
        Route::get('/all', [TrainerEarningController::class, 'allEarnings']);
        Route::get('/trainer/{id}', [TrainerEarningController::class, 'trainerEarnings']);
    });

});