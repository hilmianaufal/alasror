<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('order')->get();

        return view('activities.index', compact('activities'));
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(Request $request, Activity $activity)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:routine,manual'],
            'days' => ['nullable', 'array'],
            'days.*' => ['integer', 'between:0,6'],
            'category' => ['required', 'in:umum,diniyah'],
            'event_date' => ['nullable', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'late_minutes' => ['required', 'integer', 'min:0', 'max:180'],
            'is_active' => ['nullable'],
        ]);

        $activity->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'days' => $data['type'] === 'routine' ? ($data['days'] ?? []) : null,
            'event_date' => $data['type'] === 'manual' ? ($data['event_date'] ?? null) : null,
            'start_time' => strlen($data['start_time']) === 5 ? $data['start_time'].':00' : $data['start_time'],
            'end_time' => strlen($data['end_time']) === 5 ? $data['end_time'].':00' : $data['end_time'],
            'late_minutes' => (int) $data['late_minutes'],
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Jadwal kegiatan berhasil diperbarui.');
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'in:routine,manual'],
            'days' => ['nullable', 'array'],
            'days.*' => ['integer', 'between:0,6'],
            'event_date' => ['nullable', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'category' => ['required', 'in:umum,diniyah'],
            'late_minutes' => ['required', 'integer', 'min:0', 'max:180'],
            'is_active' => ['nullable'],
        ]);

        Activity::create([
            'name' => $data['name'],
            'type' => $data['type'],
            'days' => $data['type'] === 'routine'
                ? ($data['days'] ?? [])
                : null,

            'event_date' => $data['type'] === 'manual'
                ? ($data['event_date'] ?? null)
                : null,

            'order' => Activity::max('order') + 1,

            'start_time' => strlen($data['start_time']) === 5
                ? $data['start_time'].':00'
                : $data['start_time'],

            'end_time' => strlen($data['end_time']) === 5
                ? $data['end_time'].':00'
                : $data['end_time'],

            'late_minutes' => (int) $data['late_minutes'],
            'is_active' => (bool) $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }


    public function destroy(Activity $activity)
        {
            $activity->delete();

            return redirect()
                ->route('activities.index')
                ->with('success', 'Kegiatan berhasil dihapus.');
        }
}
