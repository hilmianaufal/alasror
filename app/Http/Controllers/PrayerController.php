<?php

namespace App\Http\Controllers;

use App\Models\Prayer;
use Illuminate\Http\Request;

class PrayerController extends Controller
{
    public function index()
    {
        $prayers = Prayer::orderBy('order')->get();

        return view('prayers.index', compact('prayers'));
    }

    public function create()
    {
        return view('prayers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:prayers,name'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'late_minutes' => ['required', 'integer', 'min:0', 'max:180'],
            'order' => ['required', 'integer', 'min:1', 'max:99'],
            'is_active' => ['nullable'],
        ]);

        Prayer::create([
            'name' => $data['name'],
            'start_time' => $this->normalizeTime($data['start_time']),
            'end_time' => $this->normalizeTime($data['end_time']),
            'late_minutes' => (int) $data['late_minutes'],
            'order' => (int) $data['order'],
            'is_active' => (bool) ($request->input('is_active') ?? false),
        ]);

        return redirect()
            ->route('prayers.index')
            ->with('success', 'Jadwal sholat berhasil ditambahkan.');
    }

    public function edit(Prayer $prayer)
    {
        return view('prayers.edit', compact('prayer'));
    }

    public function update(Request $request, Prayer $prayer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:prayers,name,' . $prayer->id],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'late_minutes' => ['required', 'integer', 'min:0', 'max:180'],
            'order' => ['required', 'integer', 'min:1', 'max:99'],
            'is_active' => ['nullable'],
        ]);

        $prayer->update([
            'name' => $data['name'],
            'start_time' => $this->normalizeTime($data['start_time']),
            'end_time' => $this->normalizeTime($data['end_time']),
            'late_minutes' => (int) $data['late_minutes'],
            'order' => (int) $data['order'],
            'is_active' => (bool) ($request->input('is_active') ?? false),
        ]);

        return redirect()
            ->route('prayers.index')
            ->with('success', 'Jadwal sholat diperbarui.');
    }

    public function destroy(Prayer $prayer)
    {
        $prayer->delete();

        return redirect()
            ->route('prayers.index')
            ->with('success', 'Jadwal sholat berhasil dihapus.');
    }

    private function normalizeTime(string $time): string
    {
        return strlen($time) === 5 ? $time . ':00' : $time;
    }
}
