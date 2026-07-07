<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">

<style>
@page { margin: 0; }

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: DejaVu Sans, Arial, sans-serif;
  background: #ffffff;
}

.card {
  position: relative;
  width: 300px;
  height: 480px;
  overflow: hidden;
  background: #064e3b;
  color: #ffffff;
}

.bg-1 {
  position: absolute;
  inset: 0;
  background: #047857;
}

.bg-2 {
  position: absolute;
  right: -80px;
  top: -60px;
  width: 220px;
  height: 220px;
  border-radius: 999px;
  background: #84cc16;
}

.bg-3 {
  position: absolute;
  left: -70px;
  bottom: -70px;
  width: 210px;
  height: 210px;
  border-radius: 999px;
  background: #10b981;
}

.pattern {
  position: absolute;
  left: 0;
  top: 0;
  width: 300px;
  height: 480px;
  border: 8px solid rgba(255,255,255,0.22);
}

.inner {
  position: relative;
  z-index: 10;
  padding: 18px;
}

.header {
  height: 68px;
}

.logo {
  position: absolute;
  right: 18px;
  top: 18px;
  width: 54px;
  height: 54px;
  border-radius: 16px;
  background: #ffffff;
  padding: 7px;
}

.logo img {
  width: 40px;
  height: 40px;
  object-fit: contain;
}

.small-title {
  font-size: 9px;
  font-weight: 900;
  letter-spacing: 4px;
  color: #bbf7d0;
}

.brand {
  margin-top: 6px;
  font-size: 19px;
  font-weight: 900;
}

.sub {
  margin-top: 3px;
  font-size: 9px;
  font-weight: 700;
  color: #d1fae5;
}

.photo-box {
  margin: 18px auto 0;
  width: 120px;
  height: 120px;
  border-radius: 30px;
  background: #ffffff;
  padding: 6px;
  border: 4px solid #bbf7d0;
}

.photo-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 23px;
}

.name {
  margin-top: 16px;
  text-align: center;
  font-size: 21px;
  font-weight: 900;
  line-height: 1.15;
}

.nis {
  margin-top: 5px;
  text-align: center;
  font-size: 11px;
  font-weight: 800;
  color: #d1fae5;
}

.meta {
  margin-top: 18px;
  width: 100%;
  border-collapse: separate;
  border-spacing: 8px 0;
}

.meta td {
  width: 50%;
  text-align: center;
  background: rgba(255,255,255,0.16);
  border: 1px solid rgba(255,255,255,0.25);
  border-radius: 16px;
  padding: 10px 5px;
}

.meta-label {
  font-size: 8px;
  font-weight: 900;
  color: #bbf7d0;
}

.meta-value {
  margin-top: 5px;
  font-size: 12px;
  font-weight: 900;
  color: #ffffff;
}

.qr-card {
  margin-top: 20px;
  background: #ffffff;
  border-radius: 22px;
  padding: 13px;
  color: #0f172a;
  border: 4px solid #dcfce7;
}

.qr-table {
  width: 100%;
  border-collapse: collapse;
}

.qr-img {
    width: 200px;
    height: 200px;
    border: 5px solid red;
}

.qr-title {
  font-size: 9px;
  font-weight: 900;
  color: #94a3b8;
  letter-spacing: 1px;
}

.qr-main {
  margin-top: 5px;
  font-size: 13px;
  font-weight: 900;
  color: #0f172a;
}

.qr-token {
  margin-top: 7px;
  font-size: 7.5px;
  font-weight: 700;
  color: #64748b;
  line-height: 1.35;
}

.footer {
  margin-top: 13px;
  font-size: 9px;
  font-weight: 800;
  color: #d1fae5;
}

.left { float: left; }
.right { float: right; }
</style>
</head>

<body>
@php
  $logoPath = public_path('images/logo.png');

  $photoPath = $student->photo && file_exists(public_path($student->photo))
      ? public_path($student->photo)
      : public_path('images/default.jpg');
@endphp

<div class="card">
  <div class="bg-1"></div>
  <div class="bg-2"></div>
  <div class="bg-3"></div>
  <div class="pattern"></div>

  <div class="inner">
    <div class="header">
      <div class="small-title">STUDENT ID</div>
      <div class="brand">SIABSEN</div>
      <div class="sub">Pondok Pesantren Al Asror</div>

      <div class="logo">
        @if(file_exists($logoPath))
          <img src="{{ $logoPath }}">
        @endif
      </div>
    </div>

    <div class="photo-box">
      <img src="{{ $photoPath }}">
    </div>

    <div class="name">
      {{ $student->name }}
    </div>

    <div class="nis">
      NIS {{ $student->nis }}
    </div>

    <table class="meta">
      <tr>
        <td>
          <div class="meta-label">JENJANG</div>
          <div class="meta-value">{{ $student->kelas ?: '-' }}</div>
        </td>
        <td>
          <div class="meta-label">KAMAR</div>
          <div class="meta-value">{{ $student->kamar ?: '-' }}</div>
        </td>
      </tr>
    </table>

    <div class="qr-card">
      <table class="qr-table">
        <tr>
          <td style="width:290px;">
            <img src="{{ route('students.qr.show', $student) }}" class="qr-img">
          </td>
          <td>
            <div class="qr-title">SCAN QR</div>
            <div class="qr-main">Absensi Santri</div>
            <div class="qr-token">
              {{ \Illuminate\Support\Str::limit($student->qr_token, 44) }}
            </div>
          </td>
        </tr>
      </table>
    </div>

    <div class="footer">
      <span class="left">Valid Permanent</span>
      <span class="right">{{ now()->format('Y') }}</span>
    </div>
  </div>
</div>

</body>
</html>
