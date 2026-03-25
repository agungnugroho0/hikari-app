<?php

use App\Http\Controllers\BillingStatementController;
use App\Http\Controllers\NafudaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentDocumentController;
use App\Livewire\Auth\Login;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Dokumen;
use App\Livewire\Pages\Jobfair;
use App\Livewire\Pages\Laporan;
use App\Livewire\Pages\Setelan;
use App\Livewire\Pages\Siswa;
use App\Livewire\Pages\Staff;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', Login::class)->name('login');
Route::middleware(['auth', 'akses:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('pages::dashboard');
    Route::get('/siswa', Siswa::class)->name('pages::siswa');
    Route::get('/jobfair', Jobfair::class)->name('pages::jobfair');
    Route::get('/laporan', Laporan::class)->name('pages::laporan');
    Route::get('/dokumen', Dokumen::class)->name('pages::dokumen');
    Route::get('/laporan/formulir-nilai', [ReportController::class, 'monthlyScoreSheet'])->name('reports.monthly-score');
    Route::get('/laporan/absensi', [ReportController::class, 'monthlyAttendanceSheet'])->name('reports.attendance');
    Route::get('/nafuda/{nis}', [NafudaController::class, 'download'])->name('pdf');
    Route::get('/billing-statement/{nis}', [BillingStatementController::class, 'download'])->name('billing.statement');
    Route::get('/dokumen/{type}/{nis}', [StudentDocumentController::class, 'download'])->name('documents.download');
    Route::get('/staff', Staff::class)->name('pages::staff');
    Route::get('/setting',Setelan::class)->name('setelan');
});
Route::middleware(['auth', 'akses:guru'])->group(function () {
    Route::get('/guru/dashboard', function () {
        dd('berhasil guru');
    });
});
Route::middleware(['auth', 'akses:dev'])->group(function () {
    Route::get('/dev/dashboard', function () {
        dd('berhasil dev');
    });
});
