<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectUnitController;
use App\Http\Controllers\CompanyController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::get('/projects/{projectId}/units',[ProjectUnitController::class, 'index']);

Route::get('/companies',[CompanyController::class, 'index']);