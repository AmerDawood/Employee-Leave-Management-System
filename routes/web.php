<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveTypeController;
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

Route::middleware('auth')->group(function(){



    Route::middleware('admin')->group(function(){
        Route::get('admin/dashboard',[AdminController::class,'index'])->name('dashboard.index');
        Route::resource('leave-types',LeaveTypeController::class);
        Route::get('show-request-details/{id}',[AdminController::class,'show'])->name('request.details');
        Route::put('/update-leave-request/{leaveRequest}', [AdminController::class,'updateLeaveRequest'])->name('update.leave-request');

        Route::put('/leave-types/{leave_type}', [LeaveTypeController::class,'update'])->name('leave-types.update');


        Route::resource('employees',EmployeeController::class);
        Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');

        // Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('employees.update');

        Route::get('employee/{id}/request/',[EmployeeController::class,'getLeaveRequestByUserId'])->name('employee.request');

        Route::get('leavetype/{id}/request/',[LeaveTypeController::class,'getLeaveRequestByLeaveType'])->name('leavetype.request');



    });


    Route::middleware('user')->group(function(){
        Route::resource('my-requests',LeaveRequestController::class);

    });

     Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


});



// Auth

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);




