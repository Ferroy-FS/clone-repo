<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Payment;
use App\Models\TrainerBooking;
use App\Models\TrainerDetail;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function summary()
    {
        return ApiResponse::success('Dashboard summary loaded.', $this->summaryPayload());
    }

    public function stream(Request $request): StreamedResponse
    {
        return response()->stream(function () {
            for ($i = 0; $i < 60; $i++) {
                echo "event: dashboard\n";
                echo 'data: '.json_encode($this->summaryPayload())."\n\n";

                @ob_flush();
                flush();

                sleep(5);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    private function summaryPayload(): array
    {
        return [
            'users_total' => User::query()->count(),
            'new_registrations_today' => User::query()->whereDate('created_at', today())->count(),
            'trainers_total' => TrainerDetail::query()->count(),
            'members_total' => User::query()->whereHas('role', fn ($q) => $q->where('name', 'member'))->count(),
            'schedules_today' => TrainerBooking::query()->whereDate('booking_date', today())->count(),
            'transactions_pending' => Schema::hasTable('payments')
                ? Payment::query()->where('payment_status', 'pending')->count()
                : 0,
            'unread_notifications' => Notification::query()->where('is_read', false)->count(),
            'recent_activity' => User::query()
                ->latest('id')
                ->limit(5)
                ->get(['id', 'full_name', 'email', 'created_at']),
            'updated_at' => now()->toISOString(),
        ];
    }
}
