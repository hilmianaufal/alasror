@extends('layouts.app')

@section('title','Import Santri')
@section('mobile_title','Import Santri')

@section('content')

<x-ui.page-header
  title="Import Data Santri"
  subtitle="Upload data santri massal menggunakan file Excel"
  icon="bi-file-earmark-spreadsheet"
>
  <x-slot:actions>
        <x-ui.button :href="route('students.import.template')" variant="secondary">
        <i class="bi bi-download"></i>
        Template
        </x-ui.button>

        <x-ui.button :href="route('students.index')" variant="secondary">
        Kembali
        </x-ui.button>

    
  </x-slot:actions>
</x-ui.page-header>

@if ($errors->any())
  <div class="mb-6 rounded-[1.5rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-black text-red-700">
    {{ $errors->first() }}
  </div>
@endif

<div class="grid gap-6 lg:grid-cols-12">

  <div class="lg:col-span-7">
    <x-ui.card>
      <div class="mb-6">
        <div class="text-lg font-black text-slate-900">
          Upload File Excel
        </div>
        <div class="mt-1 text-sm font-medium text-slate-500">
          Gunakan format .xlsx, .xls, atau .csv.
        </div>
      </div>

      <form method="POST" action="{{ route('students.import') }}" enctype="multipart/form-data">
        @csrf

        <label class="block cursor-pointer rounded-[2rem] border-2 border-dashed border-emerald-200 bg-emerald-50/60 p-8 text-center transition hover:bg-emerald-50">
          <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-white text-3xl text-emerald-600 shadow-xl shadow-emerald-100">
            <i class="bi bi-cloud-arrow-up"></i>
          </div>

          <div class="mt-4 text-base font-black text-emerald-950">
            Pilih file Excel
          </div>

          <div class="mt-1 text-sm font-semibold text-slate-500">
            Klik area ini untuk upload file santri
          </div>

          <input
            id="fileInput"
            type="file"
            name="file"
            accept=".xlsx,.xls,.csv"
            required
            class="hidden">
        </label>

        <div id="fileName" class="mt-4 hidden rounded-2xl bg-slate-50 px-4 py-3 text-sm font-bold text-slate-600"></div>

        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
          <x-ui.button type="submit" class="justify-center">
            <i class="bi bi-upload"></i>
            Import Sekarang
          </x-ui.button>

          <x-ui.button :href="route('students.index')" variant="secondary" class="justify-center">
            Batal
          </x-ui.button>
        </div>
      </form>
    </x-ui.card>
  </div>

  <div class="lg:col-span-5">
    <x-ui.card>
      <div class="mb-5">
        <div class="text-lg font-black text-slate-900">
          Format Excel
        </div>
        <div class="text-sm font-medium text-slate-500">
          Pastikan header kolom sesuai.
        </div>
      </div>

      <div class="overflow-hidden rounded-2xl border border-slate-100">
        <table class="w-full text-sm">
          <thead class="bg-slate-50 text-left text-xs font-black uppercase text-slate-400">
            <tr>
              <th class="px-4 py-3">Kolom</th>
              <th class="px-4 py-3">Contoh</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-slate-100 font-semibold text-slate-600">
            <tr>
              <td class="px-4 py-3 font-black text-slate-900">nis</td>
              <td class="px-4 py-3">2024001</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-black text-slate-900">nama</td>
              <td class="px-4 py-3">Ahmad Fauzan</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-black text-slate-900">jenjang</td>
              <td class="px-4 py-3">12 IPS</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-black text-slate-900">kamar</td>
              <td class="px-4 py-3">Ruqoyah</td>
            </tr>
            <tr>
              <td class="px-4 py-3 font-black text-slate-900">wa_ortu</td>
              <td class="px-4 py-3">6281234567890</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-5 rounded-2xl bg-amber-50 p-4 text-sm font-bold text-amber-700">
        Header wajib menggunakan huruf kecil sesuai format di atas.
      </div>
    </x-ui.card>
  </div>

</div>

@endsection

@push('scripts')
<script>
document.getElementById('fileInput')?.addEventListener('change', function () {
  const fileNameBox = document.getElementById('fileName');

  if (!this.files.length) {
    fileNameBox.classList.add('hidden');
    fileNameBox.textContent = '';
    return;
  }

  fileNameBox.textContent = 'File dipilih: ' + this.files[0].name;
  fileNameBox.classList.remove('hidden');
});
</script>
@endpush