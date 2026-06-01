<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ActivityRecapController;
use App\Http\Controllers\ActivityScanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MonthlyRecapController;
use App\Http\Controllers\PrayerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QrScanController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentQrController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WeeklyRecapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('permission:view_reports')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Scan QR - Petugas & Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:scan_qr')->group(function () {
        Route::get('/scan', [QrScanController::class, 'index'])->name('scan.index');
        Route::post('/scan', [QrScanController::class, 'store'])->name('scan.store');

        Route::get('/kegiatan/scan', [ActivityScanController::class, 'index'])->name('activities.scan');
        Route::post('/kegiatan/scan', [ActivityScanController::class, 'store'])->name('activities.scan.store');
    });

    /*
    |--------------------------------------------------------------------------
    | Santri
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage_students')->group(function () {
        Route::get('/students/search/realtime', [StudentController::class, 'searchRealtime'])
            ->name('students.search.realtime');

        Route::resource('students', StudentController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Santri QR & ID Card
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage_students')->group(function () {
        Route::get('/students/{student}/qr', [StudentQrController::class, 'show'])
            ->name('students.qr.show');

        Route::get('/students/{student}/qr/download', [StudentQrController::class, 'download'])
            ->name('students.qr.download');

        Route::get('/students/{student}/id-card', [StudentQrController::class, 'idCard'])
            ->name('students.id-card');

        Route::get('/students/{student}/attendance', [StudentAttendanceController::class, 'show'])
            ->name('students.attendance.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Jadwal Sholat
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage_prayers')->group(function () {
        Route::get('/jadwal-sholat', [PrayerController::class, 'index'])->name('prayers.index');
        Route::get('/jadwal-sholat/{prayer}/edit', [PrayerController::class, 'edit'])->name('prayers.edit');
        Route::put('/jadwal-sholat/{prayer}', [PrayerController::class, 'update'])->name('prayers.update');
    });

    /*
    |--------------------------------------------------------------------------
    | Kegiatan
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage_activities')->group(function () {
        Route::get('/kegiatan', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/kegiatan/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/kegiatan', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/kegiatan/{activity}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/kegiatan/{activity}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/kegiatan/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Rekap Sholat
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/export/excel', [RekapController::class, 'exportExcel'])->name('rekap.export.excel');
        Route::get('/rekap/export/pdf', [RekapController::class, 'exportPdf'])->name('rekap.export.pdf');

        Route::get('/rekap-bulanan', [MonthlyRecapController::class, 'index'])->name('rekap.monthly');
        Route::get('/rekap-bulanan/export/excel', [MonthlyRecapController::class, 'exportExcel'])->name('rekap.monthly.export.excel');
        Route::get('/rekap-bulanan/export/pdf', [MonthlyRecapController::class, 'exportPdf'])->name('rekap.monthly.export.pdf');
        Route::post('/rekap/status', [RekapController::class, 'markStatus'])
                    ->name('rekap.mark-status')
                    ->middleware(['auth','permission:view_reports']);
        Route::post('/rekap/status', [RekapController::class, 'markStatus'])
            ->name('rekap.mark-status')
            ->middleware(['auth','permission:view_reports']);
        Route::post('/rekap/status/cancel', [RekapController::class, 'cancelStatus'])
            ->name('rekap.cancel-status')
            ->middleware(['auth','permission:view_reports']);
    });

    /*
    |--------------------------------------------------------------------------
    | Rekap Kegiatan
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:view_reports')->group(function () {
        Route::get('/rekap-kegiatan', [ActivityRecapController::class, 'index'])
            ->name('activities.recap');

        Route::get('/rekap-kegiatan/export/excel', [ActivityRecapController::class, 'exportExcel'])
            ->name('activities.recap.export.excel');

        Route::post('/rekap-kegiatan/status', [ActivityRecapController::class, 'markStatus'])
            ->name('activities.recap.mark-status');
        Route::post('/rekap-kegiatan/status/cancel', [ActivityRecapController::class, 'cancelStatus'])
                ->name('activities.recap.cancel-status');
        Route::get('/students-import', [StudentController::class, 'importForm'])
    ->name('students.import.form');

        Route::post('/students-import', [StudentController::class, 'import'])
            ->name('students.import');
        Route::get('/students-import/template', [StudentController::class, 'downloadTemplate'])
    ->name('students.import.template');
    });

    Route::get('/rekap-mingguan', [WeeklyRecapController::class, 'index'])
    ->name('rekap.weekly');

    Route::get('/rekap-mingguan/export/excel', [WeeklyRecapController::class, 'exportExcel'])
    ->name('rekap.weekly.export.excel');
    Route::get('/students/{student}/id-card/png', [StudentQrController::class, 'idCardPng'])
        ->name('students.id-card.png');

    /*
    |--------------------------------------------------------------------------
    | User Management
    |--------------------------------------------------------------------------
    */

    Route::middleware('permission:manage_users')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
});