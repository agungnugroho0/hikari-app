<?php

use App\Http\Controllers\NafudaController;
use App\Livewire\Auth\Login;
use App\Livewire\Pages\Jobfair;
use App\Livewire\Pages\Siswa;
use App\Livewire\Pages\Staff;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', Login::class)->name('login');
Route::middleware(['auth', 'akses:admin'])->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('pages::dashboard');
    Route::get('/siswa', Siswa::class)->name('pages::siswa');
    Route::get('/jobfair', Jobfair::class)->name('pages::jobfair');
    Route::get('/nafuda/{nis}', [NafudaController::class, 'download'])->name('pdf');
    Route::get('/staff', Staff::class)->name('pages::staff');
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
