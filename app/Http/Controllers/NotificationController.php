<?php

namespace App\Http\Controllers;

use App\Models\Notifications_tbl;
use App\Models\Notification_settings_tbl;
use App\Models\Announcements_tbl;
use App\Models\AnnouncementComments_tbl;
use App\Models\ResignationRequest_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
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

        $announcements = Announcements_tbl::with(['user', 'comments.user', 'likes'])
            ->withCount('likes', 'comments')
            ->orderBy('created_at', 'desc')
            ->get();

        $resignees = ResignationRequest_tbl::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $allComments = AnnouncementComments_tbl::with(['user', 'announcement'])
            ->orderBy('created_at', 'desc')
            ->get();

        $perPage = 5;

        $inboxItems = $inbox->map(fn($n) => (object)['type' => 'notification', 'item' => $n, 'created_at' => $n->created_at])
            ->concat($resignees->map(fn($r) => (object)['type' => 'resignation', 'item' => $r, 'created_at' => $r->created_at]))
            ->sortByDesc('created_at')
            ->values();

        $inboxPage = (int) $request->get('inbox_page', 1);
        $inboxTotal = $inboxItems->count();
        $inboxLastPage = (int) ceil($inboxTotal / $perPage);
        $inboxPaginated = $inboxItems->slice(($inboxPage - 1) * $perPage, $perPage)->values();

        $socialItems = $social->map(fn($n) => (object)['type' => 'notification', 'item' => $n, 'created_at' => $n->created_at])
            ->concat($allComments->map(fn($c) => (object)['type' => 'comment', 'item' => $c, 'created_at' => $c->created_at]))
            ->sortByDesc('created_at')
            ->values();

        $socialPage = (int) $request->get('social_page', 1);
        $socialTotal = $socialItems->count();
        $socialLastPage = (int) ceil($socialTotal / $perPage);
        $socialPaginated = $socialItems->slice(($socialPage - 1) * $perPage, $perPage)->values();

        $currentUser = Auth::user();

        return view('admin_components.notifications', compact(
            'notifications', 'important', 'inbox', 'spam', 'social', 'settings',
            'announcements', 'currentUser',
            'inboxPaginated', 'inboxPage', 'inboxLastPage', 'inboxTotal',
            'socialPaginated', 'socialPage', 'socialLastPage', 'socialTotal'
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
