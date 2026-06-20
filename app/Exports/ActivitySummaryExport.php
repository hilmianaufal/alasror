<?php

namespace App\Exports;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivitySession;
use App\Models\Student;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivitySummaryExport implements FromArray, WithHeadings
{
    public function __construct(
        public string $category,
        public string $period,
        public string $startDate,
        public string $endDate,
        public ?string $gender = null,
        public ?string $kelas = null,
        public ?string $kamar = null,
    ) {}

    public function headings(): array
    {
        return [
            'Periode',
            'Kategori',
            'NIS',
            'Nama',
            'Jenis Santri',
            'Jenjang',
            'Kamar',
            'Hadir',
            'Telat',
            'Izin',
            'Sakit',
            'Pulang',
            'Alpa',
            'Total',
        ];
    }

    public function array(): array
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        $activities = Activity::where('category', $this->category)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $students = Student::where('is_active', true)
            ->when($this->gender, fn ($q) => $q->where('gender', $this->gender))
            ->when($this->kelas, fn ($q) => $q->where('kelas', $this->kelas))
            ->when($this->kamar, fn ($q) => $q->where('kamar', $this->kamar))
            ->orderBy('name')
            ->get();

        $sessionIds = ActivitySession::whereIn('activity_id', $activities->pluck('id'))
            ->whereBetween('started_at', [$start, $end])
            ->pluck('id');

        $attendances = ActivityAttendance::whereIn('activity_session_id', $sessionIds)->get();

        $days = $start->diffInDays($end) + 1;
        $target = $activities->count() * $days;

        $rows = [];

        foreach ($students as $student) {
            $studentAttendances = $attendances->where('student_id', $student->id);

            $hadir = $studentAttendances->where('status', 'hadir')->count();
            $telat = $studentAttendances->where('status', 'terlambat')->count();
            $izin = $studentAttendances->where('status', 'izin')->count();
            $sakit = $studentAttendances->where('status', 'sakit')->count();
            $pulang = $studentAttendances->where('status', 'pulang')->count();

            $recorded = $hadir + $telat + $izin + $sakit + $pulang;
            $alpa = max(0, $target - $recorded);

            $rows[] = [
                $start->format('d-m-Y') . ' s/d ' . $end->format('d-m-Y'),
                $this->category === 'diniyah' ? 'Diniyah' : 'Kegiatan Umum',
                $student->nis,
                $student->name,
                $student->gender === 'putra' ? 'Putra' : ($student->gender === 'putri' ? 'Putri' : '-'),
                $student->kelas ?? '-',
                $student->kamar ?? '-',
                $hadir,
                $telat,
                $izin,
                $sakit,
                $pulang,
                $alpa,
                $recorded . '/' . $target,
            ];
        }

        return $rows;
    }
}
