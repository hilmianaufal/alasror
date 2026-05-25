<?php

namespace App\Services;

use App\Models\Prayer;
use Illuminate\Support\Carbon;

class PrayerTimeService
{
    /**
     * Ambil sholat yang sedang aktif berdasarkan jam sekarang.
     * Return: Prayer|null
     */
    public function getActivePrayer(?Carbon $now = null): ?Prayer
    {
        $now = $now ?: now();

        return Prayer::where('is_active', true)
            ->get()
            ->first(function ($prayer) use ($now) {
                $start = Carbon::createFromTimeString($prayer->start_time);
                $end   = Carbon::createFromTimeString($prayer->end_time);

                return $now->between($start, $end);
            });
    }

    /**
     * Untuk badge status per sholat (soon/live/closed) berdasarkan jam sekarang.
     */
    public function getPrayerStatus(Prayer $prayer, ?Carbon $now = null): string
    {
        $now = $now ?: now();
        $time = $now->format('H:i:s');

        if ($time < $prayer->start_time) return 'soon';
        if ($time > $prayer->end_time) return 'closed';
        return 'live';
    }

    /**
     * Cek terlambat: sekarang > start_time + late_minutes
     */
    public function isLate($prayer): bool
    {
        if ((int) $prayer->late_minutes <= 0) {
            return false;
        }

        $now = now();

        $start = \Carbon\Carbon::createFromTimeString($prayer->start_time);
        $lateLimit = $start->copy()->addMinutes((int) $prayer->late_minutes);

        return $now->greaterThan($lateLimit);
    }

}
