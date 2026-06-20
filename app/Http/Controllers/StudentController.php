<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $kelas = $request->query('kelas');
        $kamar = $request->query('kamar');
        $gender = $request->query('gender');

        $students = Student::query()
            ->when($q, fn($qr) => $qr->where(function($w) use ($q) {
                $w->where('name', 'like', "%$q%")
                ->orWhere('nis', 'like', "%$q%");
            }))
            ->when($kelas, fn($qr) => $qr->where('kelas', $kelas))
            ->when($kamar, fn($qr) => $qr->where('kamar', $kamar))
            ->when($gender, fn($qr) => $qr->where('gender', $gender))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $kelasList = Student::whereNotNull('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $kamarList = Student::whereNotNull('kamar')->distinct()->orderBy('kamar')->pluck('kamar');

        return view('students.index', compact(
            'students',
            'q',
            'kelas',
            'kamar',
            'gender',
            'kelasList',
            'kamarList'
        ));
    }

    public function create()
    {
        return view('students.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nis'       => ['required','string','max:50','unique:students,nis'],
            'name'      => ['required','string','max:120'],
            'kelas'     => ['nullable','string','max:50'],
            'kamar'     => ['nullable','string','max:50'],
             'parent_phone' => ['nullable', 'string', 'max:30'],
             'gender' => ['nullable', 'in:putra,putri'],
            'is_active' => ['nullable','boolean'],
            'photo'     => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        if ($request->hasFile('photo')) {
            $dir = public_path('uploads/students');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move($dir, $filename);

            $data['photo'] = 'uploads/students/' . $filename;
        }

        $student = Student::create($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Santri berhasil ditambahkan.');
    }

    public function show(Student $student)
    {
        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'nis'       => ['required','string','max:50', Rule::unique('students','nis')->ignore($student->id)],
            'name'      => ['required','string','max:120'],
            'kelas'     => ['nullable','string','max:50'],
            'kamar'     => ['nullable','string','max:50'],
            'gender' => ['nullable', 'in:putra,putri'],
            'is_active' => ['nullable','boolean'],
             'parent_phone' => ['nullable', 'string', 'max:30'],
            'photo'     => ['nullable','image','mimes:jpg,jpeg,png','max:2048'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        if ($request->hasFile('photo')) {
            if ($student->photo && file_exists(public_path($student->photo))) {
                unlink(public_path($student->photo));
            }

            $dir = public_path('uploads/students');

            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $file = $request->file('photo');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move($dir, $filename);

            $data['photo'] = 'uploads/students/' . $filename;
        }

        $student->update($data);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->photo && file_exists(public_path($student->photo))) {
            unlink(public_path($student->photo));
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Santri berhasil dihapus.');
    }


        public function searchRealtime(Request $request)
    {
        $q = $request->input('q');
        $kelas = $request->input('kelas');
        $kamar = $request->input('kamar');

        $students = Student::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($s) use ($q) {
                    $s->where('name', 'like', "%{$q}%")
                    ->orWhere('nis', 'like', "%{$q}%");
                });
            })
            ->when($kelas, fn ($query) => $query->where('kelas', $kelas))
            ->when($kamar, fn ($query) => $query->where('kamar', $kamar))
            ->latest()
            ->limit(30)
            ->get()
            ->map(fn ($student) => [
                'id' => $student->id,
                'name' => $student->name,
                'nis' => $student->nis,
                'kelas' => $student->kelas,
                'gender' => $student->gender,
                'kamar' => $student->kamar,
                'is_active' => $student->is_active,
                'photo_url' => $student->photoUrl(),
                'show_url' => route('students.show', $student),
                'edit_url' => route('students.edit', $student),
            ]);

        return response()->json([
            'students' => $students,
        ]);
    }

    public function importForm()
    {
        return view('students.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv'],
        ]);

        $import = new StudentsImport();

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('students.index')
            ->with(
                'success',
                "Import selesai. Baru: {$import->created}, Update: {$import->updated}, Dilewati: {$import->skipped}."
            );
    }
    public function downloadTemplate()
    {
        $export = new class implements FromArray, WithHeadings {
            public function headings(): array
            {
                return [
                    'nis',
                    'nama',
                    'jenjang',
                    'kamar',
                    'jenis_santri',
                    'wa_ortu',
                ];
            }

            public function array(): array
            {
                return [
                        [
                            '2024001',
                            'Ahmad Fauzan',
                            '12 IPS',
                            'Ruqoyah',
                            'putra',
                            '6281234567890',
                        ],
                        [
                            '2024002',
                            'Fatimah Zahra',
                            '11 IPA',
                            'Aisyah',
                            'putri',
                            '6289876543210',
                        ],
                    ];
            }
        };

        return Excel::download($export, 'template-import-santri.xlsx');
    }
}
