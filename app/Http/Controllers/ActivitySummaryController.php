<?php

namespace App\Http\Controllers;

use App\Exports\ActivitySummaryExport;
use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivitySession;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ActivitySummaryController extends Controller
{
    public function daily(Request $request)
    {
        return $this->summary($request, 'umum', 'daily');
    }

    public function dailyDiniyah(Request $request)
    {
        return $this->summary($request, 'diniyah', 'daily');
    }

    private function summary(Request $request, string $category, string $period)
    {
        $date = $request->input('date', now()->toDateString());
        $gender = $request->input('gender');
        $kelas = $request->input('kelas');
        $kamar = $request->input('kamar');

        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $activities = Activity::where('category', $category)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $students = Student::query()
            ->where('is_active', true)
            ->when($gender, fn ($q) => $q->where('gender', $gender))
            ->when($kelas, fn ($q) => $q->where('kelas', $kelas))
            ->when($kamar, fn ($q) => $q->where('kamar', $kamar))
            ->orderBy('name')
            ->get();

        $sessionIds = ActivitySession::whereIn('activity_id', $activities->pluck('id'))
            ->whereBetween('started_at', [$start, $end])
            ->pluck('id');

        $attendances = ActivityAttendance::whereIn('activity_session_id', $sessionIds)->get();

        $totalActivity = $activities->count();
        $totalTarget = $students->count() * $totalActivity;

        $rows = $students->map(function ($student) use ($attendances, $totalActivity) {
            $studentAttendances = $attendances->where('student_id', $student->id);

            $hadir = $studentAttendances->where('status', 'hadir')->count();
            $telat = $studentAttendances->where('status', 'terlambat')->count();
            $izin = $studentAttendances->where('status', 'izin')->count();
            $sakit = $studentAttendances->where('status', 'sakit')->count();
            $pulang = $studentAttendances->where('status', 'pulang')->count();

            $recorded = $hadir + $telat + $izin + $sakit + $pulang;
            $alpa = max(0, $totalActivity - $recorded);

            return [
                'student' => $student,
                'hadir' => $hadir,
                'telat' => $telat,
                'izin' => $izin,
                'sakit' => $sakit,
                'pulang' => $pulang,
                'alpa' => $alpa,
                'total' => $recorded,
                'target' => $totalActivity,
            ];
        });

        $summary = [
            'total_santri' => $students->count(),
            'total_target' => $totalTarget,
            'hadir' => $rows->sum('hadir'),
            'telat' => $rows->sum('telat'),
            'izin' => $rows->sum('izin'),
            'sakit' => $rows->sum('sakit'),
            'pulang' => $rows->sum('pulang'),
            'alpa' => $rows->sum('alpa'),
        ];

        $kelasList = Student::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $kamarList = Student::whereNotNull('kamar')->distinct()->orderBy('kamar')->pluck('kamar');

        $view = $category === 'diniyah'
            ? 'rekap.diniyah.daily'
            : 'rekap.kegiatan.daily';

        return view($view, compact(
            'date',
            'gender',
            'kelas',
            'kamar',
            'activities',
            'rows',
            'summary',
            'kelasList',
            'kamarList',
            'category'
        ));
    }


public function weekly(Request $request)
{
    return $this->summaryWeekly($request, 'umum');
}

private function summaryWeekly(Request $request, string $category)
{
    $week = $request->input('week', now()->format('Y-\WW'));
    $gender = $request->input('gender');
    $kelas = $request->input('kelas');
    $kamar = $request->input('kamar');

    $start = \Carbon\Carbon::parse(str_replace('-W', 'W', $week))->startOfWeek();
    $end = $start->copy()->endOfWeek();

    $activities = Activity::where('category', $category)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();

    $students = Student::query()
        ->where('is_active', true)
        ->when($gender, fn ($q) => $q->where('gender', $gender))
        ->when($kelas, fn ($q) => $q->where('kelas', $kelas))
        ->when($kamar, fn ($q) => $q->where('kamar', $kamar))
        ->orderBy('name')
        ->get();

    $sessionIds = ActivitySession::whereIn('activity_id', $activities->pluck('id'))
        ->whereBetween('started_at', [
            $start->copy()->startOfDay(),
            $end->copy()->endOfDay(),
        ])
        ->pluck('id');

    $attendances = ActivityAttendance::whereIn('activity_session_id', $sessionIds)->get();

    $totalActivity = $activities->count() * 7;
    $totalTarget = $students->count() * $totalActivity;

    $rows = $students->map(function ($student) use ($attendances, $totalActivity) {
        $studentAttendances = $attendances->where('student_id', $student->id);

        $hadir = $studentAttendances->where('status', 'hadir')->count();
        $telat = $studentAttendances->where('status', 'terlambat')->count();
        $izin = $studentAttendances->where('status', 'izin')->count();
        $sakit = $studentAttendances->where('status', 'sakit')->count();
        $pulang = $studentAttendances->where('status', 'pulang')->count();

        $recorded = $hadir + $telat + $izin + $sakit + $pulang;
        $alpa = max(0, $totalActivity - $recorded);

        return [
            'student' => $student,
            'hadir' => $hadir,
            'telat' => $telat,
            'izin' => $izin,
            'sakit' => $sakit,
            'pulang' => $pulang,
            'alpa' => $alpa,
            'total' => $recorded,
            'target' => $totalActivity,
        ];
    });

    $summary = [
        'total_santri' => $students->count(),
        'total_target' => $totalTarget,
        'hadir' => $rows->sum('hadir'),
        'telat' => $rows->sum('telat'),
        'izin' => $rows->sum('izin'),
        'sakit' => $rows->sum('sakit'),
        'pulang' => $rows->sum('pulang'),
        'alpa' => $rows->sum('alpa'),
    ];

    $kelasList = Student::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
    $kamarList = Student::whereNotNull('kamar')->distinct()->orderBy('kamar')->pluck('kamar');

    return view('rekap.kegiatan.weekly', compact(
        'week',
        'start',
        'end',
        'gender',
        'kelas',
        'kamar',
        'activities',
        'rows',
        'summary',
        'kelasList',
        'kamarList',
        'category'
    ));
}

public function monthly(Request $request)
{
    return $this->summaryMonthly($request, 'umum');
}

private function summaryMonthly(Request $request, string $category)
{
    $month = (int) $request->input('month', now()->month);
    $year = (int) $request->input('year', now()->year);

    $gender = $request->input('gender');
    $kelas = $request->input('kelas');
    $kamar = $request->input('kamar');

    $start = Carbon::create($year, $month, 1)->startOfMonth();
    $end = $start->copy()->endOfMonth();

    $activities = Activity::where('category', $category)
        ->where('is_active', true)
        ->orderBy('order')
        ->get();

    $students = Student::query()
        ->where('is_active', true)
        ->when($gender, fn ($q) => $q->where('gender', $gender))
        ->when($kelas, fn ($q) => $q->where('kelas', $kelas))
        ->when($kamar, fn ($q) => $q->where('kamar', $kamar))
        ->orderBy('name')
        ->get();

    $sessionIds = ActivitySession::whereIn('activity_id', $activities->pluck('id'))
        ->whereBetween('started_at', [
            $start->copy()->startOfDay(),
            $end->copy()->endOfDay(),
        ])
        ->pluck('id');

    $attendances = ActivityAttendance::whereIn('activity_session_id', $sessionIds)->get();

    $daysInMonth = $start->daysInMonth;
    $totalActivity = $activities->count() * $daysInMonth;
    $totalTarget = $students->count() * $totalActivity;

    $rows = $students->map(function ($student) use ($attendances, $totalActivity) {
        $studentAttendances = $attendances->where('student_id', $student->id);

        $hadir = $studentAttendances->where('status', 'hadir')->count();
        $telat = $studentAttendances->where('status', 'terlambat')->count();
        $izin = $studentAttendances->where('status', 'izin')->count();
        $sakit = $studentAttendances->where('status', 'sakit')->count();
        $pulang = $studentAttendances->where('status', 'pulang')->count();

        $recorded = $hadir + $telat + $izin + $sakit + $pulang;
        $alpa = max(0, $totalActivity - $recorded);

        return [
            'student' => $student,
            'hadir' => $hadir,
            'telat' => $telat,
            'izin' => $izin,
            'sakit' => $sakit,
            'pulang' => $pulang,
            'alpa' => $alpa,
            'total' => $recorded,
            'target' => $totalActivity,
        ];
    });

    $summary = [
        'total_santri' => $students->count(),
        'total_target' => $totalTarget,
        'hadir' => $rows->sum('hadir'),
        'telat' => $rows->sum('telat'),
        'izin' => $rows->sum('izin'),
        'sakit' => $rows->sum('sakit'),
        'pulang' => $rows->sum('pulang'),
        'alpa' => $rows->sum('alpa'),
    ];

    $kelasList = Student::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
    $kamarList = Student::whereNotNull('kamar')->distinct()->orderBy('kamar')->pluck('kamar');

    return view('rekap.kegiatan.monthly', compact(
        'month',
        'year',
        'start',
        'end',
        'gender',
        'kelas',
        'kamar',
        'activities',
        'rows',
        'summary',
        'kelasList',
        'kamarList',
        'category'
    ));
}

public function diniyahWeekly(Request $request)
{
    return $this->summaryWeekly($request, 'diniyah');
}

public function diniyahMonthly(Request $request)
{
    return $this->summaryMonthly($request, 'diniyah');
}

public function exportExcel(Request $request, string $category, string $period)
{
    $gender = $request->input('gender');
    $kelas = $request->input('kelas');
    $kamar = $request->input('kamar');

    if ($period === 'daily') {
        $start = \Carbon\Carbon::parse($request->input('date', now()->toDateString()));
        $end = $start->copy();
    } elseif ($period === 'weekly') {
        $week = $request->input('week', now()->format('Y-\WW'));
        $start = \Carbon\Carbon::parse(str_replace('-W', 'W', $week))->startOfWeek();
        $end = $start->copy()->endOfWeek();
    } else {
        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $start = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
    }

    $label = $category === 'diniyah' ? 'Diniyah' : 'Kegiatan-Umum';

    return Excel::download(
        new ActivitySummaryExport(
            $category,
            $period,
            $start->toDateString(),
            $end->toDateString(),
            $gender,
            $kelas,
            $kamar
        ),
        'Rekap-'.$label.'-'.$period.'-'.$start->format('Y-m-d').'.xlsx'
    );
}
}
