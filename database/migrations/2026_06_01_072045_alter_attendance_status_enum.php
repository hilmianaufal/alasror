<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE attendances
            MODIFY status ENUM(
                'hadir',
                'terlambat',
                'udzur',
                'sakit',
                'alpa',
                'pulang'
            ) NOT NULL DEFAULT 'hadir'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE attendances
            MODIFY status ENUM(
                'hadir',
                'terlambat'
            ) NOT NULL DEFAULT 'hadir'
        ");
    }
};