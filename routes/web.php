<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaRplController;
use App\Http\Controllers\AddController;
use App\Http\Controllers\AktivitasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\MateriTkjController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\UserAdminController;
use App\Http\Controllers\UserPembimbingController;
use App\Http\Controllers\UserSiswaController;
use App\Http\Controllers\MateriRplController;
use App\Http\Controllers\DashboardSiswaController;



Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');

Route::get('/tables', function () {
    return view('tables');
})->name('tables')->middleware('auth');

Route::get('/wallet', function () {
    return view('wallet');
})->name('wallet')->middleware('auth');

Route::get('/RTL', function () {
    return view('RTL');
})->name('RTL')->middleware('auth');

Route::get('/profile', function () {
    return view('account-pages.profile');
})->name('profile')->middleware('auth');

Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');


Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest');

Route::resource('siswa', SiswaController::class);
Route::post('/siswa/store-multiple', [SiswaController::class, 'storeMultiple'])->name('siswa.storeMultiple');
Route::post('/siswa/start/{id}', [SiswaController::class, 'start'])->name('siswa.start');
Route::post('/siswa/stop/{id}', [SiswaController::class, 'stop'])->name('siswa.stop');
Route::post('/siswa/toggle/{id}', [SiswaController::class, 'toggle'])->name('siswa.toggle');

Route::resource('siswarpl', SiswaRplController::class);

Route::prefix('siswarpl')->name('siswarpl.')->group(function () {
    Route::post('/store', [SiswaRplController::class, 'storeMultiple'])->name('storeMultiple');
    Route::post('/start/{id}', [SiswaRplController::class, 'start'])->name('start');
    Route::post('/stop/{id}', [SiswaRplController::class, 'stop'])->name('stop');
    Route::post('/toggle/{id}', [SiswaRplController::class, 'toggle'])->name('toggle');
});

Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');



Route::resource('materitkj', MateriTkjController::class)->middleware(['auth', 'role:pembimbing']);

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
});

Route::resource('jurusan', JurusanController::class)->middleware(['auth', 'role:admin']);
Route::resource('materi', MateriController::class)->middleware(['auth', 'role:siswa']);



Route::resource('useradmin' , UserPembimbingController::class)->middleware(['auth', 'role:pembimbing']);
Route::resource('userpembimbing' , UserPembimbingController::class)->middleware(['auth', 'role:pembimbing']);
Route::resource('usersiswa' , UserSiswaController::class)->middleware(['auth', 'role:pembimbing']);

Route::resource('monitoring', MonitoringController::class)->middleware(['auth', 'role:pembimbing']); 


Route::put('/siswarpl/{id}/updateTime', [SiswaRplController::class, 'updateTime'])->name('siswarpl.updateTime');
Route::put('/siswa/{id}/updateTime', [SiswaController::class, 'updateTime'])->name('siswa.updateTime');


Route::resource('aktivitas', AktivitasController::class);
Route::resource('materirpl', MateriRplController::class);
Route::resource('Dashboardsiswa', DashboardSiswaController::class); 