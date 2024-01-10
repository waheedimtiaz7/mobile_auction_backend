<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\StripeController;
use App\Http\Controllers\Api\ComplaintController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login',[AuthController::class,'login']);
Route::post('register',[AuthController::class,'register']);
Route::group(['middleware' => 'auth:api'],function(){
   Route::get('devices', [DeviceController::class, 'index']);
   Route::get('get-my-devices', [DeviceController::class, 'getMyDevices']);
   Route::post('device/create', [DeviceController::class, 'create']);
   Route::post('device/update', [DeviceController::class, 'update']);
   Route::get('device/show/{device_id}', [DeviceController::class, 'show']);
   Route::get('device/delete/{device_id}', [DeviceController::class, 'delete']);
   Route::post('device/create-new-bid', [DeviceController::class, 'createNewBid']);
   Route::post('device/accept-bid', [DeviceController::class, 'acceptBid']);
   Route::post('save-payment-method', [StripeController::class, 'savePaymentMethod']);
   Route::get('future-use-intent', [StripeController::class, 'futureUseIntent']);
   Route::get('get-payment-methods', [StripeController::class, 'getPaymentMethods']);
   Route::post('set-default-method', [StripeController::class, 'setDefaultMethod']);
   Route::post('delete-method', [StripeController::class, 'deleteMethod']);
   Route::post('update-profile', [AuthController::class, 'update']);
   //////////////User Complaint////////////////
   Route::get('get-user-complaints', [ComplaintController::class, 'getUserComplaints']);
   Route::post('create-complaint', [ComplaintController::class, 'createComplaint']);
   Route::post('reply-on-complaint', [ComplaintController::class, 'replyOnComplaint']);
   Route::post('get-complaint-detail', [ComplaintController::class, 'getComplaintDetail']);
   Route::post('delete-complaint', [ComplaintController::class, 'deleteComplaint']);

   //////////////////Employee Routes////////////////

    Route::get('get-ongoing-auction-devices', [DeviceController::class, 'getOngoingAuctionDevices']);
    Route::get('get-pending-devices', [DeviceController::class, 'getNewDevices']);
    Route::get('get-employee-bid-devices', [DeviceController::class, 'getAllBidDevices']);
    Route::get('get-active-pending-devices', [DeviceController::class, 'getActivePendingDevices']);
    Route::get('get-sold-devices', [DeviceController::class, 'getSoldDevices']);
    Route::post('update-device-status-by-employee', [DeviceController::class, 'updateDevicesStatus']);
    Route::get('get-employee-complaints', [ComplaintController::class, 'getEmployeeSideComplaints']);
});
