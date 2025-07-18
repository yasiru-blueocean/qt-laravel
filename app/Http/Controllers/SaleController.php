<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\SaleResource;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['project', 'unit', 'customer', 'payments', 'paymentPlans'])
            ->whereNotIn('active_status', [1, 2])
            ->get();

        return SaleResource::collection($sales);
    }

}
