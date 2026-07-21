<?php

namespace App\Http\Controllers;

use App\Models\Seminars_tbl;
use App\Models\SeminarAttendees_tbl;
use App\Models\SeminarCompletions_tbl;
use App\Models\Users_tbl;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeminarController extends Controller
{
    public function index()
    {
        $upcomingSeminars = Seminars_tbl::with(['attendees.user'])
            ->where('schedule_datetime', '>=', now())
            ->orderBy('schedule_datetime', 'asc')
            ->get();

        $pastSeminars = Seminars_tbl::with(['attendees.user'])
            ->where('schedule_datetime', '<', now())
            ->orderBy('schedule_datetime', 'desc')
            ->get();

        $users = Users_tbl::where('role', '!=', 'admin')
            ->orderBy('first_name')
            ->get()
            ->map(function ($user) {
                $completion = SeminarCompletions_tbl::where('user_id', $user->id)->first();
                $user->completion = $completion ?: (object) [
                    'pmes_completed' => false,
                    'fundamentals_completed' => false,
                    'finance_completed' => false,
                    'completed_at' => null,
                ];
                return $user;
            });

        return view('admin_components.seminars', compact('upcomingSeminars', 'pastSeminars', 'users'));
    }

    public function scheduleSeminar(Request $request)
    {
        $validated = $request->validate([
            'seminar_type' => 'required|in:pmes,fundamentals,finance',
            'schedule_datetime' => 'required|date|after:now',
            'delivery_type' => 'required|in:online,f2f',
            'online_link' => 'nullable|url|required_if:delivery_type,online',
            'meetup_place' => 'nullable|string|max:255|required_if:delivery_type,f2f',
            'exact_venue' => 'nullable|string|max:255|required_if:delivery_type,f2f',
            'attendees' => 'required|array|min:1',
            'attendees.*' => 'exists:users_tbls,id',
        ]);

        DB::beginTransaction();
        try {
            $seminar = Seminars_tbl::create([
                'seminar_type' => $validated['seminar_type'],
                'schedule_datetime' => $validated['schedule_datetime'],
                'delivery_type' => $validated['delivery_type'],
                'online_link' => $validated['online_link'] ?? null,
                'meetup_place' => $validated['meetup_place'] ?? null,
                'exact_venue' => $validated['exact_venue'] ?? null,
            ]);

            $attendees = [];
            foreach ($validated['attendees'] as $userId) {
                $attendees[] = [
                    'seminar_id' => $seminar->id,
                    'user_id' => $userId,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            SeminarAttendees_tbl::insert($attendees);

            DB::commit();

            AuditLog::log(
                'Scheduled Seminar',
                "Scheduled {$validated['seminar_type']} seminar on {$validated['schedule_datetime']} " .
                "with " . count($attendees) . " attendee(s)",
                'seminar',
                $seminar->id
            );

            return redirect()->route('seminars.index')->with('success', 'Seminar scheduled successfully with ' . count($attendees) . ' attendee(s).');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to schedule seminar: ' . $e->getMessage())->withInput();
        }
    }

    public function updateAttendanceAndCompletion(Request $request)
    {
        $validated = $request->validate([
            'seminar_id' => 'required|exists:seminars_tbls,id',
            'user_id' => 'required|exists:users_tbls,id',
            'status' => 'required|in:attended,absent',
        ]);

        $seminar = Seminars_tbl::findOrFail($validated['seminar_id']);
        $attendee = SeminarAttendees_tbl::where('seminar_id', $validated['seminar_id'])
            ->where('user_id', $validated['user_id'])
            ->firstOrFail();

        $attendee->status = $validated['status'];
        $attendee->save();

        AuditLog::log(
            'Updated Seminar Attendance',
            "Marked user #{$validated['user_id']} as {$validated['status']} for seminar #{$validated['seminar_id']}",
            'seminar_attendance',
            $validated['seminar_id']
        );

        if ($validated['status'] === 'attended') {
            $completion = SeminarCompletions_tbl::firstOrCreate(
                ['user_id' => $validated['user_id']],
                [
                    'pmes_completed' => false,
                    'fundamentals_completed' => false,
                    'finance_completed' => false,
                ]
            );

            $column = $seminar->seminar_type . '_completed';
            $completion->$column = true;
            $completion->save();

            $this->autoUpgradeIfComplete($validated['user_id'], $completion);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance marked as ' . $validated['status'] . '.',
        ]);
    }

    public function toggleManualCompletion(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users_tbls,id',
            'field' => 'required|in:pmes_completed,fundamentals_completed,finance_completed',
        ]);

        $completion = SeminarCompletions_tbl::firstOrCreate(
            ['user_id' => $validated['user_id']],
            [
                'pmes_completed' => false,
                'fundamentals_completed' => false,
                'finance_completed' => false,
            ]
        );

        $completion->{$validated['field']} = !$completion->{$validated['field']};
        $completion->save();

        AuditLog::log(
            'Toggled Seminar Completion',
            "Toggled {$validated['field']} for user #{$validated['user_id']} to " . ($completion->{$validated['field']} ? 'completed' : 'incomplete'),
            'seminar_completion',
            $validated['user_id']
        );

        $allComplete = $completion->pmes_completed && $completion->fundamentals_completed && $completion->finance_completed;

        if ($allComplete) {
            $completion->completed_at = $completion->completed_at ?: now();
            $completion->save();
            $this->autoUpgradeIfComplete($validated['user_id'], $completion);
        } else {
            $completion->completed_at = null;
            $completion->save();

            $user = Users_tbl::find($validated['user_id']);
            if ($user->role === 'member' && !$allComplete) {
                // Optionally downgrade - but spec says upgrade only; leave role as-is
            }
        }

        return response()->json([
            'success' => true,
            'field' => $validated['field'],
            'value' => $completion->{$validated['field']},
            'all_complete' => $allComplete,
            'completed_at' => $completion->completed_at ? $completion->completed_at->format('M d, Y h:i A') : null,
        ]);
    }

    private function autoUpgradeIfComplete($userId, $completion)
    {
        if ($completion->pmes_completed && $completion->fundamentals_completed && $completion->finance_completed) {
            $user = Users_tbl::find($userId);

            if (!$completion->completed_at) {
                $completion->completed_at = now();
                $completion->save();
            }

            if ($user && $user->role === 'pending') {
                $user->role = 'member';
                $user->save();

                $otherInfo = DB::table('otherinfo_tbls')->where('user_id', $userId)->first();
                if ($otherInfo) {
                    DB::table('otherinfo_tbls')
                        ->where('user_id', $userId)
                        ->update([
                            'membership_status' => 'Active',
                            'approval_status' => 'Approved',
                        ]);
                }
            }
        }
    }
}
