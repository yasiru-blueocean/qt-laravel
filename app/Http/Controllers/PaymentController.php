<?php

// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Sale::with(['project', 'unit', 'customer', 'paymentPlans', 'payments'])
        ->whereNotIn('active_status', [1, 2, 5, 6, 7])
        ->get();

    // Prepare array for frontend
    $result = [];
    foreach ($payments as $payment) {
        $result[] = [
            'sale_id'           => $payment->sale_id,
            'sale_date'         => $payment->sale_date,
            'project_name'      => $payment->project->project_name ?? '',
            'unit_Name'         => $payment->unit->unit_Name ?? '',
            'C_fullname'        => $payment->customer->C_fullname ?? '',
            'selling_price'     => number_format($payment->selling_price ?? 0, 2),
            'totalPaid'         => number_format($payment->total_paid, 2),
            'balance'           => number_format($payment->balance, 2),
            'next_due_date'     => $payment->next_due_date ?? 'N/A',
            'due_date_class'    => $payment->due_date_class,
        ];
    }
    return response()->json($result);
}
}