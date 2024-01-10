<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
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
Route::get('/migrate', function (){
    \Illuminate\Support\Facades\Artisan::call("migrate");
});
Route::group(['prefix'=> 'admin'],function(){
    Route::get('/',[AuthController::class , 'login'])->name('login');
    Route::post('/authenticate',[AuthController::class , 'authenticate'])->name('admin.authenticate');


    Route::group(['middleware' => 'admin'],function(){
        Route::get('/dashboard',[HomeController::class , 'index'])->name('dashboard');
        Route::get('/logout',[HomeController::class , 'logout'])->name('admin.logout');

        //user routes
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{id}', [UserController::class, 'detail'])->name('users.detail');

        // employee routes
        Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/employee/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::post('/employee/update', [EmployeeController::class, 'update'])->name('employees.update');
        Route::get('/employees/{id}', [EmployeeController::class, 'detail'])->name('employees.detail');
        Route::get('/employee/delete/{id}', [EmployeeController::class, 'delete'])->name('employees.delete');

        Route::get('/devices', [\App\Http\Controllers\DeviceController::class, 'index'])->name('devices.index');
        Route::get('/device/detail/{id}', [\App\Http\Controllers\DeviceController::class, 'detail'])->name('device.detail');
        Route::post('/device/status-update', [\App\Http\Controllers\DeviceController::class, 'updateStatus'])->name('device.status_update');
    });
});
