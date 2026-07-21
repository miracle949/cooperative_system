<?php

namespace App\Http\Controllers;

use App\Models\Announcements_tbl;
use App\Models\AnnouncementComments_tbl;
use App\Models\AnnouncementLikes_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcements_tbl::with(['user', 'comments.user', 'likes'])
            ->withCount('likes', 'comments')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentUser = Auth::user();

        return view('admin_components.announcements', compact('announcements', 'currentUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $announcement = Announcements_tbl::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        AuditLog::log(
            'Created Announcement',
            "Created announcement: {$request->title}",
            'announcement',
            $announcement->id
        );

        return redirect()->route('announcements.index')->with('success', 'Announcement published successfully.');
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = AnnouncementComments_tbl::create([
            'announcement_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        $comment->load('user');

        AuditLog::log(
            'Posted Comment',
            "Posted comment on announcement #{$id}",
            'announcement_comment',
            $comment->id
        );

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'id' => $comment->user->id,
                    'first_name' => $comment->user->first_name,
                    'last_name' => $comment->user->last_name,
                    'role' => $comment->user->role,
                ],
            ],
        ]);
    }

    public function deleteComment(Request $request, $id, $commentId)
    {
        $comment = AnnouncementComments_tbl::where('announcement_id', $id)
            ->where('id', $commentId)
            ->firstOrFail();

        $comment->delete();

        AuditLog::log(
            'Deleted Comment',
            "Deleted comment #{$commentId} from announcement #{$id}",
            'announcement_comment',
            $commentId
        );

        $count = AnnouncementComments_tbl::where('announcement_id', $id)->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    public function deleteAnnouncement(Request $request, $id)
    {
        $announcement = Announcements_tbl::findOrFail($id);
        $announcement->comments()->delete();
        $announcement->likes()->delete();
        $announcement->delete();

        AuditLog::log(
            'Deleted Announcement',
            "Deleted announcement #{$id}: {$announcement->title}",
            'announcement',
            $id
        );

        return response()->json([
            'success' => true,
        ]);
    }

    public function toggleLike(Request $request, $id)
    {
        $userId = Auth::id();
        $existing = AnnouncementLikes_tbl::where('announcement_id', $id)
            ->where('user_id', $userId)
            ->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
            AuditLog::log(
                'Unliked Announcement',
                "Unliked announcement #{$id}",
                'announcement_like',
                $id
            );
        } else {
            AnnouncementLikes_tbl::create([
                'announcement_id' => $id,
                'user_id' => $userId,
            ]);
            $liked = true;
            AuditLog::log(
                'Liked Announcement',
                "Liked announcement #{$id}",
                'announcement_like',
                $id
            );
        }

        $count = AnnouncementLikes_tbl::where('announcement_id', $id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $count,
        ]);
    }
}
