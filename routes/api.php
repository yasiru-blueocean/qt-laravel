<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectUnitController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RefundSaleController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\MonthlySalesSummaryController;
use App\Http\Controllers\SaleSummaryReportController;
use App\Http\Controllers\PaymentReportController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});


Route::get('/projects/units/{projectId}',[ProjectUnitController::class, 'index']); 

Route::get('/companies',[CompanyController::class, 'index']);

Route::get('/customers',[CustomerController::class, 'index']);

Route::get('/projects', [ProjectController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);

Route::get('/sales', [SaleController::class, 'index']);

Route::get('/refund_sales', [RefundSaleController::class, 'index']);

Route::get('/customer-report', [CustomerReportController::class, 'index']);

Route::get('/monthly-sales-summary', [MonthlySalesSummaryController::class, 'index']);

Route::get('/sales-summary-report', [SaleSummaryReportController::class, 'index']);

Route::get('/payment-report', [PaymentReportController::class, 'index']);
