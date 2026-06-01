<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #0f172a;
        }

        .header {
            text-align: center;
            margin-bottom: 18px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 12px;
            color: #475569;
        }

        .meta {
            margin-bottom: 14px;
            width: 100%;
        }

        .meta td {
            padding: 3px 0;
        }

        .stats {
            width: 100%;
            margin-bottom: 16px;
            border-collapse: collapse;
        }

        .stats td {
            border: 1px solid #e2e8f0;
            padding: 8px;
            text-align: center;
            font-weight: bold;
        }

        .stats .label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
        }

        .stats .value {
            font-size: 16px;
            margin-top: 4px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #064e3b;
            color: white;
            padding: 8px 6px;
            font-size: 10px;
            text-align: left;
        }

        table.data td {
            border: 1px solid #e2e8f0;
            padding: 7px 6px;
            vertical-align: top;
        }

        .center {
            text-align: center;
        }

        .status {
            font-weight: bold;
        }

        .hadir { color: #047857; }
        .terlambat { color: #b45309; }
        .udzur { color: #2563eb; }
        .sakit { color: #dc2626; }
        .pulang { color: #334155; }
        .alpa { color: #b91c1c; }

        .footer {
            margin-top: 18px;
            font-size: 10px;
            color: #64748b;
            text-align: right;
        }
    </style>
</head>

<body>

<div class="header">
    <div class="title">Rekap Absensi Sholat</div>
    <div class="subtitle">
        {{ $selectedPrayer?->name ?? '-' }} • {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
    </div>
</div>

<table class="meta">
    <tr>
        <td><strong>Sholat</strong></td>
        <td>: {{ $selectedPrayer?->name ?? '-' }}</td>
        <td><strong>Jenjang</strong></td>
        <td>: {{ $kelas ?: 'Semua' }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td>: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</td>
        <td><strong>Kamar</strong></td>
        <td>: {{ $kamar ?: 'Semua' }}</td>
    </tr>
</table>

<table class="stats">
    <tr>
        <td>
            <div class="label">Total</div>
            <div class="value">{{ $totalStudents }}</div>
        </td>
        <td>
            <div class="label">Hadir</div>
            <div class="value">{{ $hadirCount }}</div>
        </td>
        <td>
            <div class="label">Telat</div>
            <div class="value">{{ $terlambatCount }}</div>
        </td>
        <td>
            <div class="label">Udzur</div>
            <div class="value">{{ $udzurCount }}</div>
        </td>
        <td>
            <div class="label">Sakit</div>
            <div class="value">{{ $sakitCount }}</div>
        </td>
        <td>
            <div class="label">Pulang</div>
            <div class="value">{{ $pulangCount }}</div>
        </td>
        <td>
            <div class="label">Alpa</div>
            <div class="value">{{ $belumCount }}</div>
        </td>
    </tr>
</table>

<table class="data">
    <thead>
        <tr>
            <th style="width: 28px;">No</th>
            <th style="width: 75px;">NIS</th>
            <th>Nama</th>
            <th style="width: 70px;">Jenjang</th>
            <th style="width: 70px;">Kamar</th>
            <th style="width: 75px;">Status</th>
            <th style="width: 70px;">Jam</th>
        </tr>
    </thead>

   <tbody>
      @foreach($students as $student)
        @php
          $attendance = $attendancesByStudent->get($student->id);

          if ($attendance) {
              $status = match($attendance->status) {
                  'hadir' => 'Hadir',
                  'terlambat' => 'Telat',
                  'udzur' => 'Udzur',
                  'sakit' => 'Sakit',
                  'pulang' => 'Pulang',
                  default => ucfirst($attendance->status),
              };

              $jam = $attendance->scanned_at
                  ? \Carbon\Carbon::parse($attendance->scanned_at)->format('H:i:s')
                  : '-';
          } else {
              $status = 'Alpa';
              $jam = '-';
          }
        @endphp

        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $student->nis }}</td>
          <td>{{ $student->name }}</td>
          <td>{{ $student->kelas ?: '-' }}</td>
          <td>{{ $student->kamar ?: '-' }}</td>
          <td>{{ $status }}</td>
          <td>{{ $jam }}</td>
        </tr>
      @endforeach
      </tbody>
</table>

<div class="footer">
    Dicetak pada {{ now()->translatedFormat('d F Y H:i') }}
</div>

</body>
</html>