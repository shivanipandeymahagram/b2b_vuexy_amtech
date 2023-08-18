<?php


use App\Http\Controllers\CallbackController;
use App\Http\Controllers\AepsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('paysprint/agent/onboard', [CallbackController::class,'paysprintOnboard']);
Route::group(['prefix'=> 'callback/update'], function() {
    Route::any('{api}', [CallbackController::class,'callback']);
});
Route::group(['prefix' => 'checkaeps'], function() {
    Route::any('icici/initiate', [AepsController::class,'iciciaepslog']);
    Route::any('icici/update', [AepsController::class, 'iciciaepslogupdate'])->middleware('transactionlog:aeps');
});

Route::any('paysprint/service/update/callback', [CallbackController::class,'paysprintcallback']);
Route::any('runpaisa/callback/runpaisaPg', [CallbackController::class,'runpaisaPg']);   
Route::any('android/getroles', [Android\UserController::class,'getroles']);
Route::any('android/secheme/list', [Android\UserController::class,'getroles']);
Route::any('getbal/{token}', [Api\ApiController::class,'getbalance']);
Route::any('getip', [Api\ApiController::class,'getip']);
Route::any('ambikarechargeupdate/callback', [CallbackController::class,'ambikarechargeupdate']); 

/*Recharge Api*/
Route::any('getprovider', [Api\RechargeController::class,'getProvider']);
Route::any('recharge/pay', [Api\RechargeController::class,'payment'])->middleware('transactionlog:recharge');
Route::any('recharge/status', [Api\RechargeController::class,'status']);


Route::any('android/employeelist', [Android\UserController::class,'getemployeelist']);
// Route::any('android/contactpost', 'Android\UserController@contactpost')->name('contactpost');

/*Android App Apis*/
Route::any('android/slider', [Android\UserController::class,'slider']);
Route::any('android/auth/user/register', [Android\UserController::class,'registration']);
Route::any('android/auth', [Android\UserController::class,'login']);
Route::any('android/auth/logout', [Android\UserController::class,'logout']);
Route::any('android/auth/reset/request', [Android\UserController::class ,'passwordResetRequest']);
Route::any('android/auth/reset', [Android\UserController::class,'passwordReset']);
Route::any('android/auth/password/change', [Android\UserController::class,'changepassword']);

// Profile Android 
Route::any('android/getstate', [Android\UserController::class,'getState']);
Route::any('android/auth/profile/change', [Android\UserController::class,'changeProfile']);

Route::any('android/getbalance', [Android\UserController::class,'getbalance']);
Route::any('android/aeps/initiate', [Android\UserController::class, 'aepsInitiate']);
Route::any('android/aeps/status', [Android\UserController::class, 'aepsStatus']);
Route::any('android/secure/microatm/initiate', 'Android\UserController@microatmInitiate')->middleware('transactionlog:matm');
Route::any('android/secure/microatm/update', 'Android\UserController@microatmUpdate')->middleware('transactionlog:amtmupdate');

Route::any('android/transaction', [Android\TransactionController::class, 'transaction']);
Route::any('android/fundrequest', [Android\FundController::class,'transaction'])->middleware('transactionlog:fund');
Route::any('android/upi/merchent', [Android\FundController::class,'createvpa']);
Route::any('android/tpin/getotp', [Android\UserController::class,'getotp']);
Route::any('android/tpin/generate', [Android\UserController::class,'setpin']);
Route::any('android/fundrequest/cyrus', [Android\CyrusFundController::class,'transaction'])->middleware('transactionlog:fund');
Route::any('android/fundrequest/runpaisa', [Android\CyrusFundController::class,'transactionRunpaisa'])->middleware('transactionlog:fund');

/*Recharge Android Api*/

Route::any('android/recharge/providers', [Android\RechargeController::class,'providersList']);
Route::any('android/recharge/pay', [Android\RechargeController::class,'transaction'])->middleware('transactionlog:recharge');
Route::any('android/recharge/status', [Android\RechargeController::class,'status']);
Route::any('android/transaction/status', [Android\TransactionController::class,'transactionStatus']);
Route::any('android/recharge/getplan', [Android\RechargeController::class,'getplan']);
Route::any('android/recharge/getoperator', [Android\RechargeController::class,'getoperator']);
Route::any('android/recharge/dthinfo', [Android\RechargeController::class,'getdthinfo']);

/*Bill Android Api*/

Route::any('android/billpay/providers', [Android\BillpayController::class,'providersList']);
Route::any('android/billpay/getprovider', [Android\BillpayController::class,'getprovider']);
Route::any('android/billpay/transaction', [Android\BillpayController::class,'transaction'])->middleware('transactionlog:billpay');
Route::any('android/billpay/status', [Android\BillpayController::class,'status']);

/*Bill Android Api*/

Route::any('android/pancard/transaction', [Android\PancardController::class,'transaction'])->middleware('transactionlog:pancard');
Route::any('android/pancard/status', [Android\PancardController::class,'status']);

/*Bill Android Api*/

Route::any('android/dmt/transaction', [Android\MoneyController::class,'transaction'])->middleware('transactionlog:dmt');

Route::any('android/aepsregistration', [Android\UserController::class,'aepskyc']);
Route::any('android/GetState', [Android\UserController::class,'GetState']);
Route::any('android/GetDistrictByState', [Android\UserController::class,'GetDistrictByState']);
Route::any('android/bcstatus', [Android\UserController::class,'bcstatus']);


/*Member Create Android Api*/
Route::any('android/member/create', [Android\UserController::class,'addMember']);
Route::any('android/member/list', [Android\TransactionController::class,'transaction']);

Route::any('android/support/store', [Android\ComplaintController::class,'support']);
Route::any('android/complaint/store', [Android\ComplaintController::class,'store']);

/*Adhar verify Android Api*/
Route::any('android/aadhar/verify', [Android\UserController::class,'adharnumberverify']);


/*LIC Bill Android Api*/
Route::any('android/licbillpay/transaction', [Android\LicBillpayController::class,'lictransaction'])->middleware('transactionlog:licbillpay');
Route::any('android/licbillpay/status', [Android\LicBillpayController::class,'status']);

/*LIC Bill Android Api*/

/* paysprint DMT */
  Route::post('android/pdmt/transaction', [Android\PdmtController::class,'payment']);
  Route::post('android/pdmt/getbank', [Android\PdmtController::class,'getbank']);

//paysprint aeps api for android
Route::any('android/paysprint/onboard', [Android\RaepsController::class,'getkyc']);
Route::any('android/raeps/transaction', [Android\RaepsController::class,'trasaction']);
Route::any('android/raeps/getdata', [Android\RaepsController::class,'getdata']);
Route::any('android/paysprint/aeps', [Android\RaepsController::class,'trasaction']);


//Paysprint APIS
Route::any('android/paysprint/uti', [Android\PancardController::class,'payment']);
Route::any('android/getcommission', [Android\UserController::class,'getcommission']);

Route::any('android/paysprint/microatm/initiate', [Android\MatmController::class,'microatmInitiate'])->middleware('transactionlog:matm');
Route::any('android/paysprint/microatm/update', [Android\MatmController::class,'microatmUpdate'])->middleware('transactionlog:matm');

//Loan Enquery 
Route::any('android/loan/enquery', [Android\UserController::class,'loanenquiery']);

Route::post('android/profile/update', [Android\UserController::class,'updateprofile']);

Route::any('android/iaeps/transaction', [Android\FingpayController::class,'transaction'])->middleware('transactionlog:faeps');
//Route::any('android/faeps/transaction', 'Android\FingpayController@transaction')->middleware('transactionlog:faeps');
// Route::group(['prefix' => 'iaeps'], function(){
//      Route::post('transaction','Api\FingpayController@transaction');
//     // Route::post('cash/deposit/transaction', 'Api\FingpayController@cashdeposittransaction');
//     Route::post('matm/transaction','Api\FingpayController@matmtransaction');
//     Route::post('matm/transaction/update','Api\FingpayController@microatmUpdate');
// });

Route::any('android/secure/microatm/initiate', [Android\UserController::class,'fmicroatmInitiate'])->middleware('transactionlog:fmicroatm');
Route::any('android/secure/microatm/update', [Android\UserController::class,'fmicroatmUpdate'])->middleware('transactionlog:fmicroatmupd');

Route::group(['prefix' => 'iaeps'], function(){
  //  Route::post('transaction','Android\FingpayController@transaction');
    Route::post('matm/transaction',[Api\FingpayController::class,'matmtransaction']);
    Route::post('matm/transaction/update',[Api\FingpayController::class,'microatmUpdate']);
});

Route::any('android/account/listing', [Android\UserController::class,'accountListing']);
Route::any('android/account/add', [Android\UserController::class,'addAccount']);
Route::any('android/tpin/reset', [Android\UserController::class,'resetTpin']);
Route::any('android/tpin/check', [Android\UserController::class,'userpin']);

Route::any('android/faeps/getdata', [Android\FingpayController::class,'getdata']);

Route::any('android/servicelist', [Android\UserController::class,'servicelist']);

Route::any('android/GetState', [Android\UserController::class,'GetState']);
Route::any('android/GetDistrictByState', [Android\UserController::class,'GetDistrictByState']);

Route::any('android/payout/accountStatus', [FundController::class,'bankList']);

// Route::any('android/secure/fmicroatm/initiate', 'Android\UserController@fmicroatmInitiate')->middleware('transactionlog:fmicroatm');
// Route::any('android/secure/fmicroatm/update', 'Android\UserController@fmicroatmUpdate')->middleware('transactionlog:fmicroatmupd');