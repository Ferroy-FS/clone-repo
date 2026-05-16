<?php

namespace App\Http\Controllers;

use App\Http\Requests\Common\NotificationIndexRequest;
use App\Models\Notification;
use App\Support\ApiResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(NotificationIndexRequest $request)
    {
        $notifications = Notification::query()
            ->visibleTo($request->user())
            ->orderByDesc('id')
            ->paginate($request->perPage());

        return ApiResponse::success('Notifications loaded.', $notifications);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::query()
            ->visibleTo($request->user())
            ->unread()
            ->count();

        return ApiResponse::success('Unread notifications loaded.', ['count' => $count]);
    }

    public function markAsReadIndividual(Request $request, Notification $notification)
    {
        $belongsToCurrentUser = (int) $notification->user_id === (int) $request->user()->id;
        $isGlobalNotification = is_null($notification->user_id);

        if (! $belongsToCurrentUser && ! $isGlobalNotification) {
            return ApiResponse::error('You are not allowed to modify this notification.', [], 403);
        }

        $notification->update(['is_read' => true]);

        return ApiResponse::success('Notification marked as read.', $notification->fresh());
    }

    public function markRead(Request $request)
    {
        $id = $request->input('id');
        $type = $request->input('type'); 

        if ($id === 'all') {
            $query = Notification::query()->where('is_read', false);
            
            if ($type === 'trainer') {

                $query->where('user_id', $request->user()->id);
            } else {
                $query->where('user_id', $request->user()->id);
            }
            
            $query->update(['is_read' => true]);
            return ApiResponse::success('All notifications marked as read.');
        }

        $notification = Notification::find($id);
        if (!$notification) {
            return ApiResponse::error('Notification not found.', [], 404);
        }

        if ((int)$notification->user_id !== (int)$request->user()->id) {
            return ApiResponse::error('Unauthorized.', [], 403);
        }

        $notification->update(['is_read' => true]);
        return ApiResponse::success('Notification marked as read.');
    }

    public function trainerNotifications(Request $request)
    {

        $notifications = Notification::where('user_id', $request->user()->id)
            ->whereIn('notification_type', ['rent', 'hire', 'schedule', 'classes'])
            ->orderByDesc('id')
            ->get();

        return ApiResponse::success('Trainer notifications loaded.', $notifications);
    }

    public function clearAll(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->delete();
        return ApiResponse::success('All notifications cleared.');
    }
}
