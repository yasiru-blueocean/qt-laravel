<?php
namespace App\Http\Controllers;

use App\Models\Sale;

class SaleController extends Controller
{
    public function index()
    {
        // Load all sales and related data first!
        $sales = Sale::with(['project', 'unit', 'customer', 'paymentPlans', 'payments'])
            ->whereNotIn('active_status', [1, 2])->paginate(2000);

        $saleData = [];
        foreach ($sales as $sale) {
            $saleData[] = [
                'sale_id' => $sale->sale_id,
                'sale_date' => $sale->sale_date,
                'project_name' => $sale->project->project_name ?? '',
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'customer_email' => $sale->customer->C_Email ?? '',
                'selling_price' => number_format($sale->selling_price ?? 0, 2),
                'totalPaid' => number_format($sale->total_paid, 2),
                'balance' => number_format($sale->balance, 2),
                'dueAmount' => number_format($sale->due_amount, 2),
                'active_status' => $sale->active_status,
                'has_payment_plan_data' => $sale->has_payment_plan_data,
            ];
        }

        return response()->json($saleData);
    }
}
