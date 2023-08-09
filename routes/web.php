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

Route::prefix('member')->group(function () {
    Route::get('whitelabel',  [UserController::class, 'whitelabel'])->name('whitelabel');
    Route::get('create',  [UserController::class, 'create'])->name('create');
    Route::get('md',  [UserController::class, 'md'])->name('md');
    Route::get('md/create',  [UserController::class, 'mdcreate'])->name('mdcreate');
    Route::get('distributor',  [UserController::class, 'distributor'])->name('distributor');
    Route::get('distributor/create',  [UserController::class, 'dcreate'])->name('dcreate');
    Route::get('retailer',  [UserController::class, 'retailer'])->name('retailer');
    Route::get('retailer/create',  [UserController::class, 'rcreate'])->name('rcreate');
    Route::get('allmember',  [UserController::class, 'allmember'])->name('allmember');
    Route::get('allmember/create',  [UserController::class, 'allmcreate'])->name('allmcreate');
    Route::get('kycsubmit',  [UserController::class, 'kycsubmit'])->name('kycsubmit');
    Route::get('kycsubmitcreate',  [UserController::class, 'kycsubmitcreate'])->name('kycsubmitcreate');
    Route::get('kycreject',  [UserController::class, 'kycreject'])->name('kycreject');
    Route::get('kycrejectcreate',  [UserController::class, 'kycrejectcreate'])->name('kycrejectcreate');
    Route::get('kycpending',  [UserController::class, 'kycpending'])->name('kycpending');
    Route::get('kycpendingcreate',  [UserController::class, 'kycpendingcreate'])->name('kycpendingcreate');
});

Route::prefix('fund')->group(function () {
    Route::get('tr',  [UserController::class, 'tr'])->name('tr');
    Route::get('request',  [UserController::class, 'request'])->name('request');
    Route::get('requestreport',  [UserController::class, 'requestreport'])->name('requestreport');
    Route::get('allfundreport',  [UserController::class, 'allfundreport'])->name('allfundreport');
});

Route::prefix('investment-fund')->group(function () {
    Route::get('fundrequest',  [UserController::class, 'fundrequest'])->name('fundrequest');
    Route::get('fundreport',  [UserController::class, 'fundreport'])->name('fundreport');
});

Route::prefix('investment-service')->group(function () {
    Route::get('banner',  [UserController::class, 'banner'])->name('banner');
    Route::get('video',  [UserController::class, 'video'])->name('video');
    Route::get('investment',  [UserController::class, 'investment'])->name('investment');
});

Route::prefix('aeps-fund')->group(function () {
    Route::get('pendingreq',  [UserController::class, 'pendingreq'])->name('pendingreq');
    Route::get('pendingpayoutreq',  [UserController::class, 'pendingpayoutreq'])->name('pendingpayoutreq');
    Route::get('reqreport',  [UserController::class, 'reqreport'])->name('reqreport');
});

Route::prefix('matm-fund')->group(function () {
    Route::get('matmrequest',  [UserController::class, 'mrequest'])->name('mrequest');
    Route::get('matmpendingreq',  [UserController::class, 'mpendingreq'])->name('mpendingreq');
    Route::get('matmreqreport',  [UserController::class, 'mreqreport'])->name('mreqreport');
});

Route::prefix('agentlist')->group(function () {
    Route::get('aeps',  [UserController::class, 'aeps'])->name('aeps');
    Route::get('uti',  [UserController::class, 'uti'])->name('uti');
});

Route::prefix('tr-report')->group(function () {
    Route::get('aepsstatement',  [UserController::class, 'aepsstatement'])->name('aepsstatement');
    Route::get('billpaystatement',  [UserController::class, 'billpaystatement'])->name('billpaystatement');
    Route::get('cmsreport',  [UserController::class, 'cmsreport'])->name('cmsreport');
    Route::get('dmtstatement',  [UserController::class, 'dmtstatement'])->name('dmtstatement');
    Route::get('loanstatement',  [UserController::class, 'loanstatement'])->name('loanstatement');
    Route::get('matmstatement',  [UserController::class, 'matmstatement'])->name('matmstatement');
    Route::get('panstatement',  [UserController::class, 'panstatement'])->name('panstatement');
    Route::get('rechargestatement',  [UserController::class, 'rechargestatement'])->name('rechargestatement');
});