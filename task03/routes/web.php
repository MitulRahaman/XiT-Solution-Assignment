<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('pendingUsers', [UserController::class, 'pendingUsers'])->name('user.pendingUsers');
    Route::post('accept', [UserController::class, 'accept'])->name('user.accept');
    Route::post('decline', [UserController::class, 'decline'])->name('user.decline');
});

require __DIR__.'/auth.php';
