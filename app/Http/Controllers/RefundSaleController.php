<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\RefundResource;
use App\Models\Refund;

class RefundSaleController extends Controller
{
    public function index()
    {
    
        $refunds = Refund::with(['sale.project', 'sale.unit', 'sale.customer'])
            ->whereHas('sale', function ($query) {
                $query->where('active_status', 2);
            })
            ->get();

        return RefundResource::collection($refunds);
    }
}

