<?php

namespace App\Http\Controllers;

use App\Exports\ActivityRecapExport;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivitySession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ActivityRecapController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $activityId = $request->input('activity_id');
        $kelas = $request->input('kelas');
        $kamar = $request->input('kamar');

    $activities = Activity::orderBy('order')->get();

        $selectedActivity = $activityId
            ? Activity::find($activityId)
            : $activities->first();

        $kelasList = Student::whereNotNull('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        $kamarList = Student::whereNotNull('kamar')
            ->distinct()
            ->orderBy('kamar')
            ->pluck('kamar');

        $studentsQuery = Student::query()
            ->where('is_active', true)
            ->when($kelas, fn ($q) => $q->where('kelas', $kelas))
            ->when($kamar, fn ($q) => $q->where('kamar', $kamar));

        $totalStudents = (clone $studentsQuery)->count();

        $session = null;
        $attendances = collect();
        $absentStudents = (clone $studentsQuery)
            ->orderBy('name')
            ->get();

        $hadirCount = 0;
        $terlambatCount = 0;
        $izinCount = 0;
        $sakitCount = 0;
        $pulangCount = 0;
        $belumCount = $totalStudents;

        if ($selectedActivity) {
            $session = ActivitySession::where('activity_id', $selectedActivity->id)
                ->whereDate('started_at', $date)
                ->latest('id')
                ->first();

            if ($session) {
                $attQuery = ActivityAttendance::query()
                    ->with('student')
                    ->where('activity_session_id', $session->id)
                    ->when($kelas, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('kelas', $kelas)))
                    ->when($kamar, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('kamar', $kamar)));

                $hadirCount = (clone $attQuery)->where('status', 'hadir')->count();
                $terlambatCount = (clone $attQuery)->where('status', 'terlambat')->count();
                $izinCount = (clone $attQuery)->where('status', 'izin')->count();
                $sakitCount = (clone $attQuery)->where('status', 'sakit')->count();
                $pulangCount = (clone $attQuery)->where('status', 'pulang')->count();

                $sudahCount = $hadirCount + $terlambatCount + $izinCount + $sakitCount + $pulangCount;
                $belumCount = max(0, $totalStudents - $sudahCount);

                $attendances = (clone $attQuery)
                    ->orderByDesc('scanned_at')
                    ->get();

                $presentIds = $attendances->pluck('student_id');

                $absentStudents = (clone $studentsQuery)
                    ->whereNotIn('id', $presentIds)
                    ->orderBy('name')
                    ->get();
            }
        }

        return view('activities.recap', compact(
            'date',
            'activityId',
            'kelas',
            'kamar',
            'kelasList',
            'kamarList',
            'activities',
            'selectedActivity',
            'session',
            'attendances',
            'absentStudents',
            'totalStudents',
            'hadirCount',
            'terlambatCount',
            'izinCount',
            'sakitCount',
            'pulangCount',
            'belumCount'
        ));
    }

    public function exportExcel(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $activityId = (int) $request->input('activity_id');
        $kelas = $request->input('kelas');
        $kamar = $request->input('kamar');

        $activity = Activity::findOrFail($activityId);

        $filename = 'Rekap-Kegiatan-' . $activity->name . '-' . $date
            . ($kelas ? '-Jenjang-' . $kelas : '')
            . ($kamar ? '-Kamar-' . $kamar : '')
            . '.xlsx';

        return Excel::download(
            new ActivityRecapExport(
                $date,
                $activity->id,
                $activity->name,
                $kelas,
                $kamar
            ),
            $filename
        );
    }

    public function markStatus(Request $request)
    {
        $data = $request->validate([
            'activity_id' => ['required', 'exists:activities,id'],
            'student_id' => ['required', 'exists:students,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:izin,sakit,pulang'],
        ]);

        $activity = Activity::findOrFail($data['activity_id']);

        $startedAt = Carbon::parse($data['date'] . ' ' . $activity->start_time);
        $endedAt = Carbon::parse($data['date'] . ' ' . $activity->end_time);

        $session = ActivitySession::where('activity_id', $activity->id)
            ->whereDate('started_at', $data['date'])
            ->latest('id')
            ->first();

        if (!$session) {
            $session = ActivitySession::create([
                'activity_id' => $activity->id,
                'started_at' => $startedAt,
                'ended_at' => $endedAt,
                'status' => 'live',
            ]);
        }

        ActivityAttendance::updateOrCreate(
            [
                'activity_session_id' => $session->id,
                'student_id' => $data['student_id'],
            ],
            [
                'scanned_at' => now(),
                'status' => $data['status'],
            ]
        );

        return back()->with('success', 'Status santri berhasil diperbarui.');
    }

    public function cancelStatus(Request $request)
    {
        $data = $request->validate([
            'attendance_id' => ['required', 'exists:activity_attendances,id'],
        ]);

        $attendance = ActivityAttendance::findOrFail($data['attendance_id']);

        if (in_array($attendance->status, ['izin', 'sakit', 'pulang'])) {
            $attendance->delete();

            return back()->with('success', 'Status kegiatan berhasil dibatalkan.');
        }

        return back()->with('error', 'Absensi scan tidak bisa dibatalkan dari sini.');
    }
}