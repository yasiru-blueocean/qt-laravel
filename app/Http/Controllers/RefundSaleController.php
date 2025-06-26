<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Refund;

class RefundSaleController extends Controller
{
    public function index()
    {
        $refunds = Refund::with(['sale.project', 'sale.unit', 'sale.customer'])
            ->whereHas('sale', fn ($q) => $q->where('active_status', 2))
            ->get()
            ->map(function ($refund) {
                return [
                    'refund_id' => $refund->refund_id,
                    'project_name' => $refund->sale->project->project_name ?? '',
                    'unit_name' => $refund->sale->unit->unit_Name ?? '',
                    'customer_name' => $refund->sale->customer->C_namewinitials ?? '',
                    'total_paid' => $refund->total_paid_amount ?? 0,
                    'deduction' => $refund->deduction_amount ?? 0,
                    'refund_amount' => ($refund->total_paid_amount ?? 0) - ($refund->deduction_amount ?? 0),
                    'has_payment_plan' => $refund->has_payment_plan,
                ];
            });

        return response()->json([
        'count' => $refunds->count(),
        'data' => $refunds,
    ]);
}
}
