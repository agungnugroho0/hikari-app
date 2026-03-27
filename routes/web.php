<?php

use App\Http\Controllers\BillingStatementController;
use App\Http\Controllers\NafudaController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentDocumentController;
use App\Livewire\Auth\Login;
use App\Livewire\Pages\daftarSiswaGuru;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Dokumen;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Jobfair;
use App\Livewire\Pages\Kelas;
use App\Livewire\Pages\Laporan;
use App\Livewire\Pages\Presensi;
use App\Livewire\Pages\SendingOrganizer as So;
use App\Livewire\Pages\Setelan;
use App\Livewire\Pages\Siswa;
use App\Livewire\Pages\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', Login::class)->name('login');
Route::middleware(['auth', 'akses:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('pages::dashboard');
    Route::get('/siswa', Siswa::class)->name('pages::siswa');
    Route::get('/jobfair', Jobfair::class)->name('pages::jobfair');
    Route::get('/nafuda/{nis}', [NafudaController::class, 'download'])->name('pdf');
    Route::get('/staff', Staff::class)->name('pages::staff');
    Route::get('/laporan', Laporan::class)->name('pages::laporan');
    Route::get('/dokumen', Dokumen::class)->name('pages::dokumen');
    Route::get('/kelas', Kelas::class)->name('kelas');
    Route::get('/so', So::class)->name('so');
    Route::get('/laporan/formulir-nilai', [ReportController::class, 'monthlyScoreSheet'])->name('reports.monthly-score');
    Route::get('/laporan/absensi', [ReportController::class, 'monthlyAttendanceSheet'])->name('reports.attendance');
    Route::get('/billing-statement/{nis}', [BillingStatementController::class, 'download'])->name('billing.statement');
    Route::get('/dokumen/{type}/{nis}', [StudentDocumentController::class, 'download'])->name('documents.download');
    Route::get('/setting', Setelan::class)->name('setelan');
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
Route::middleware(['auth', 'akses:guru'])->group(function () {
    Route::get('/sensei/dashboard', Home::class)->name('home');
    Route::get('/sensei/siswa', daftarSiswaGuru::class)->name('siswa');
    Route::get('/sensei/presensi', Presensi::class)->name('presensi');
    // Route::get('/sensei/siswa',function(){dd('ini siswa');})->name('siswa');
});
Route::middleware(['auth', 'akses:dev'])->group(function () {
    Route::get('/dev/dashboard', function () {
        dd('berhasil dev');
    });
});
