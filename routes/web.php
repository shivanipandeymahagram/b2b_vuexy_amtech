<?php

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

Route::get('/', [UserController::class, 'home'])->name('dashboard');

Route::prefix('resource')->group(function () {
    Route::get('scheme',  [UserController::class, 'scheme'])->name('scheme');
    Route::get('company',  [UserController::class, 'company'])->name('company');
    Route::get('companyprofile',  [UserController::class, 'companyprofile'])->name('companyprofile');
});
