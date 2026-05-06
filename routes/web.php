<?php

use App\Livewire\Account\ChangePassword;
use App\Livewire\Admin\Users\ManagePasswords;
use App\Livewire\TrackingApp;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Semua permintaan ke '/' akan ditangani oleh komponen TrackingApp
Route::get('/', TrackingApp::class);
Route::get('/input-kendaraan', \App\Livewire\PublicTruckInput::class)->name('public.input');

Route::middleware('auth')->group(function () {
    Route::get('/account/password', \App\Livewire\Account\ChangePassword::class)
        ->name('account.password');

    Route::get('/admin/users/passwords', \App\Livewire\Admin\Users\ManagePasswords::class)
        ->name('admin.users.passwords');
});