<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProjectController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/projects/{projectId}/units',[ProjectUnitController::class, 'index']);


// Login route
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/system-users', [UserController::class, 'index']);
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/companies', [CompanyController::class, 'index']);
    Route::get('/projects' , [ProjectController::class, 'index']);
});