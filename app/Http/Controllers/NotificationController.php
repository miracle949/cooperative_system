<?php

namespace App\Http\Controllers;

use App\Models\Notifications_tbl;
use App\Models\Notification_settings_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $notifications = Notifications_tbl::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $settings = Notification_settings_tbl::firstOrCreate(
            ['user_id' => $userId],
            [
                'mute_inbox' => false,
                'mute_spam' => false,
                'mute_social' => false,
            ]
        );

        $important = $notifications->where('is_important', true);
        $inbox = $notifications->where('category', 'inbox');
        $spam = $notifications->where('category', 'spam');
        $social = $notifications->where('category', 'social');

        return view('admin_components.notifications', compact(
            'notifications', 'important', 'inbox', 'spam', 'social', 'settings'
        ));
    }

    public function toggleImportant(Request $request, $id)
    {
        $notification = Notifications_tbl::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->is_important = !$notification->is_important;
        $notification->save();

        AuditLog::log(
            $notification->is_important ? 'Marked Notification Important' : 'Unmarked Notification Important',
            "Toggled important status for notification #{$id}",
            'notification',
            $id
        );

        return response()->json([
            'success' => true,
            'is_important' => $notification->is_important,
        ]);
    }

    public function toggleMute(Request $request)
    {
        $request->validate([
            'field' => 'required|in:mute_inbox,mute_spam,mute_social',
        ]);

        $settings = Notification_settings_tbl::firstOrCreate(
            ['user_id' => Auth::id()],
            [
                'mute_inbox' => false,
                'mute_spam' => false,
                'mute_social' => false,
            ]
        );

        $field = $request->field;
        $settings->$field = !$settings->$field;
        $settings->save();

        AuditLog::log(
            $settings->$field ? 'Muted Notification Category' : 'Unmuted Notification Category',
            "Toggled mute for {$field}",
            'notification_settings',
            Auth::id()
        );

        return response()->json([
            'success' => true,
            'field' => $field,
            'value' => $settings->$field,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = Notifications_tbl::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        AuditLog::log(
            'Marked Notification Read',
            "Marked notification #{$id} as read",
            'notification',
            $id
        );

        return response()->json([
            'success' => true,
        ]);
    }
}
