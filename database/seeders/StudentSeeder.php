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
            'Abdul Aziz',
            'Muhammad Farhan',
            'Rafi Ramadhan',
            'Fikri Maulana',
            'Arif Hidayat',
            'Zidan Alfarizi',
            'Naufal Hakim',
            'Rizky Saputra',
            'Aldi Pratama',
            'Iqbal Maulana',
            'Rendi Saputra',
            'Bagas Maulana',
            'Yusuf Ramadhan',
            'Daffa Al Ghifari',
            'Reza Pahlevi',
            'Akbar Firmansyah',
            'Hilmi Fauzan',
            'Fajar Nugraha',
        ];

        $putri = [
            'Aisyah Zahra',
            'Siti Nurhaliza',
            'Nabila Putri',
            'Ainun Kandinda Putri',
            'Aisyah Nada Aqilah',
            'Ainun Nurhasminah',
            'Syifa Aulia',
            'Nadya Ramadhani',
            'Putri Maharani',
            'Salsa Billa',
            'Nisa Rahma',
            'Rania Azzahra',
            'Khadijah Humaira',
            'Siti Aulia',
            'Nur Aini',
            'Fatimah Zahra',
            'Ratu Aisyah',
            'Anisa Putri',
            'Mutiara Nabila',
            'Dinda Safitri',
        ];

        foreach ($putra as $index => $nama) {
            Student::create([
                'name' => $nama,
                'nis' => 'P'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'kelas' => collect(['7A','7B','8A','8B','9A'])->random(),
                'kamar' => collect([
                    'Al Mukaromah',
                    'Al Ikhlas',
                    'Al Falah',
                    'Al Huda',
                ])->random(),
                'gender' => 'putra',
                'qr_token' => Str::uuid(),
                'is_active' => true,
            ]);
        }

        foreach ($putri as $index => $nama) {
            Student::create([
                'name' => $nama,
                'nis' => 'W'.str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'kelas' => collect(['7C','7D','8C','8D','9C'])->random(),
                'kamar' => collect([
                    'Ruqoyah',
                    'Aisyah',
                    'Khadijah',
                    'Hafsah',
                ])->random(),
                'gender' => 'putri',
                'qr_token' => Str::uuid(),
                'is_active' => true,
            ]);
        }
    }
}
