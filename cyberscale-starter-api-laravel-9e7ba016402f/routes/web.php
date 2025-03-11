<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;

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

// Route::get(
//     '/auth/disconnected', function () {
//         return __('auth.disconnected');
//     }
// )->name('auth.disconnected');


// Route::get('/', function () {
//     return view('welcome'); // or your desired view


Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');


Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

