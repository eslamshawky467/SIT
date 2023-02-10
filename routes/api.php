<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmployeeController;
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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [UserAuthController::class, 'login'])->middleware("throttle:5:1");
    Route::post('/register', [UserAuthController::class, 'register']);
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::post('/refresh', [UserAuthController::class, 'refresh']);
});


Route::group([
    'middleware' => 'auth:web',
    'prefix' => 'user'
], function () {
    Route::get('/users',[UsersController::class,'index']);
    Route::post('/create/users',[UsersController::class,'store']);
    Route::post('/update/users',[UsersController::class,'update']);
    Route::post('/delete/users',[UsersController::class,'destroy']);
});


Route::group([
    'middleware' => 'auth:web',
    'prefix' => 'companies'
], function () {
    Route::get('/companies',[CompanyController::class,'index']);
    Route::post('/create/companies',[CompanyController::class,'store']);
    Route::post('/update/companies',[CompanyController::class,'update']);
    Route::post('/delete/companies',[CompanyController::class,'destroy']);
});



Route::group([
    'middleware' => 'auth:web',
    'prefix' => 'employee'
], function () {
    Route::get('/employees',[EmployeeController::class,'index']);
    Route::post('/create/employees',[EmployeeController::class,'store']);
    Route::post('/update/employees',[EmployeeController::class,'update']);
    Route::post('/delete/employees',[EmployeeController::class,'destroy']);
});


