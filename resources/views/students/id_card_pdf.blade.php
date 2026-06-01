<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      margin: 0;
      padding: 0;
      background: #ffffff;
    }

    .card {
      width: 240px;
      height: 390px;
      background: linear-gradient(135deg, #047857, #10b981, #84cc16);
      border-radius: 24px;
      padding: 14px;
      color: #ffffff;
      position: relative;
    }

    .logo {
      position: absolute;
      right: 14px;
      top: 14px;
      width: 42px;
      height: 42px;
      background: #ffffff;
      border-radius: 12px;
      object-fit: contain;
      padding: 4px;
    }

    .label {
      font-size: 9px;
      letter-spacing: 3px;
      font-weight: bold;
      opacity: .8;
      text-transform: uppercase;
    }

    .school {
      margin-top: 4px;
      font-size: 15px;
      font-weight: bold;
    }

    .photo-wrap {
      text-align: center;
      margin-top: 34px;
    }

    .photo {
      width: 104px;
      height: 104px;
      border-radius: 22px;
      object-fit: cover;
      border: 4px solid #ffffff;
    }

    .name {
      margin-top: 14px;
      text-align: center;
      font-size: 20px;
      font-weight: bold;
    }

    .nis {
      margin-top: 3px;
      text-align: center;
      font-size: 11px;
      font-weight: bold;
      opacity: .85;
    }

    .meta {
      margin-top: 20px;
      display: table;
      width: 100%;
      border-spacing: 8px 0;
    }

    .meta-box {
      display: table-cell;
      background: rgba(255,255,255,.22);
      border-radius: 14px;
      padding: 10px 6px;
      text-align: center;
    }

    .meta-label {
      font-size: 8px;
      text-transform: uppercase;
      opacity: .7;
      font-weight: bold;
    }

    .meta-value {
      margin-top: 4px;
      font-size: 11px;
      font-weight: bold;
    }

    .qr-box {
      margin-top: 18px;
      background: #ffffff;
      color: #111827;
      border-radius: 18px;
      padding: 12px;
    }

    .qr {
      width: 80px;
      height: 80px;
      float: left;
      margin-right: 10px;
    }

    .qr-title {
      font-size: 9px;
      color: #94a3b8;
      font-weight: bold;
      text-transform: uppercase;
      margin-top: 12px;
    }

    .qr-desc {
      font-size: 11px;
      font-weight: bold;
      margin-top: 4px;
    }

    .footer {
      clear: both;
      margin-top: 16px;
      display: table;
      width: 100%;
      font-size: 9px;
      font-weight: bold;
      opacity: .8;
    }

    .left { display: table-cell; }
    .right { display: table-cell; text-align: right; }
  </style>
</head>
<body>

<div class="card">
  <img src="{{ public_path('images/logo.png') }}" class="logo">

  <div class="label">Student ID</div>
  <div class="school">Pondok Digital</div>

  <div class="photo-wrap">
    <img src="{{ $student->photo ? public_path($student->photo) : public_path('images/default.jpg') }}" class="photo">
  </div>

  <div class="name">{{ $student->name }}</div>
  <div class="nis">NIS {{ $student->nis }}</div>

  <div class="meta">
    <div class="meta-box">
      <div class="meta-label">Jenjang</div>
      <div class="meta-value">{{ $student->kelas ?: '-' }}</div>
    </div>

    <div class="meta-box">
      <div class="meta-label">Kamar</div>
      <div class="meta-value">{{ $student->kamar ?: '-' }}</div>
    </div>
  </div>

  <div class="qr-box">
    <img src="{{ route('students.qr.show', $student) }}" class="qr">

    <div class="qr-title">Scan QR</div>
    <div class="qr-desc">Absensi Santri</div>

    <div class="footer">
      <div class="left">Valid Permanent</div>
      <div class="right">{{ now()->format('Y') }}</div>
    </div>
  </div>
</div>

</body>
</html>