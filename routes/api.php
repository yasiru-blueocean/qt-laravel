<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectUnitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/projects/{projectId}/units',[ProjectUnitController::class, 'index']);

Route::get('/companies',[CompanyController::class, 'index']);

Route::get('/customers',[CustomerController::class, 'index']);

Route::get('/projects', [ProjectController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);

Route::get('/sales', [SaleController::class, 'index']);
