<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $putra = [
            'Ahmad Fauzan',
            'Muhammad Rizki',
        ];

        $putri = [
            'Aisyah Zahra',
            'Siti Nurhaliza',
            'Nabila Putri',
        ];

        foreach ($putra as $index => $nama) {
            Student::create([
                'name' => $nama,
                'nis' => 'P' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'kelas' => collect(['7A', '8A', '9A'])->random(),
                'kamar' => collect([
                    'Al Mukaromah',
                    'Al Ikhlas',
                ])->random(),
                'gender' => 'putra',
                'qr_token' => Str::uuid(),
                'is_active' => true,
            ]);
        }

        foreach ($putri as $index => $nama) {
            Student::create([
                'name' => $nama,
                'nis' => 'W' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'kelas' => collect(['7C', '8C', '9C'])->random(),
                'kamar' => collect([
                    'Ruqoyah',
                    'Aisyah',
                ])->random(),
                'gender' => 'putri',
                'qr_token' => Str::uuid(),
                'is_active' => true,
            ]);
        }
    }
}
