<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public int $created = 0;
    public int $updated = 0;
    public int $skipped = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nis = trim((string) ($row['nis'] ?? ''));
            $nama = trim((string) ($row['nama'] ?? ''));

            if ($nis === '' || $nama === '') {
                $this->skipped++;
                continue;
            }

            $student = Student::where('nis', $nis)->first();

            $data = [
                'name' => $nama,
                'kelas' => $row['jenjang'] ?? null,
                'kamar' => $row['kamar'] ?? null,
                'parent_phone' => $row['wa_ortu'] ?? null,
                'is_active' => true,
            ];

            if ($student) {
                $student->update($data);
                $this->updated++;
            } else {
                Student::create(array_merge($data, [
                    'nis' => $nis,
                    'qr_token' => (string) Str::uuid(),
                ]));

                $this->created++;
            }
        }
    }
}