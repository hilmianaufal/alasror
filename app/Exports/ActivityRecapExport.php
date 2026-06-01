<?php

namespace App\Exports;

use App\Models\ActivityAttendance;
use App\Models\ActivitySession;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivityRecapExport implements FromArray, WithHeadings
{
    public function __construct(
        public string $date,
        public int $activityId,
        public string $activityName,
        public ?string $kelas = null,
        public ?string $kamar = null,
    ) {}

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kegiatan',
            'NIS',
            'Nama',
            'Jenjang',
            'Kamar',
            'Status',
            'Jam Scan',
        ];
    }

    public function array(): array
    {
        $session = ActivitySession::where('activity_id', $this->activityId)
            ->whereDate('started_at', $this->date)
            ->first();

        $studentsQuery = Student::query()
            ->where('is_active', true)
            ->when($this->kelas, fn ($q) => $q->where('kelas', $this->kelas))
            ->when($this->kamar, fn ($q) => $q->where('kamar', $this->kamar));

        $students = (clone $studentsQuery)
            ->orderBy('name')
            ->get();

        $rows = [];

        if (!$session) {
            foreach ($students as $student) {
                $rows[] = $this->makeRow($student, null);
            }

            return $rows;
        }

        $attendances = ActivityAttendance::with('student')
            ->where('activity_session_id', $session->id)
            ->when($this->kelas, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('kelas', $this->kelas)))
            ->when($this->kamar, fn ($q) => $q->whereHas('student', fn ($s) => $s->where('kamar', $this->kamar)))
            ->get()
            ->keyBy('student_id');

        foreach ($students as $student) {
            $attendance = $attendances->get($student->id);

            $rows[] = $this->makeRow($student, $attendance);
        }

        return $rows;
    }

    private function makeRow(Student $student, ?ActivityAttendance $attendance): array
    {
        return [
            $this->date,
            $this->activityName,
            $student->nis,
            $student->name,
            $student->kelas ?? '-',
            $student->kamar ?? '-',
            $attendance ? $this->statusLabel($attendance->status) : 'Alpa',
            $attendance?->scanned_at?->format('H:i:s'),
        ];
    }

    private function statusLabel(?string $status): string
    {
        return match ($status) {
            'hadir' => 'Hadir',
            'terlambat' => 'Telat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'pulang' => 'Pulang',
            default => ucfirst((string) $status),
        };
    }
}