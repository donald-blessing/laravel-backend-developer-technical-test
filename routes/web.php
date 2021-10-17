<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';

Route::get('/dashboard/users', [UserController::class, 'index'])->middleware(['auth'])->name('user.index');
Route::delete('/dashboard/users/{id}/delete', [UserController::class, 'destroy'])->middleware(['auth'])->name('delete-user');
Route::delete('/dashboard/users/{id}/profile', [UserController::class, 'show'])->middleware(['auth'])->name('user.profile');
