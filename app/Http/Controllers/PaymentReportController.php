<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Carbon\Carbon;

class PaymentReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month if not filtered
        $startDate = $request->query('startDate');
$endDate = $request->query('endDate');

if (!$request->has('getFilterPaymentsSummery') || (!$startDate && !$endDate)) {
    $startDate = Carbon::now()->startOfMonth()->toDateString();
    $endDate = Carbon::now()->endOfMonth()->toDateString();
}


        $project = $request->query('selectedProject');
        $company = $request->query('selectedCompany');
        $sales = $request->query('selectedSalesMember');
        $handler = $request->query('selectedHandlingPerson');

        // Query
        $query = Payment::with(['sale.customer', 'sale.project.company', 'sale.unit'])
            ->where('active_status', 0)
            ->whereHas('sale', fn($q) =>
                $q->whereNotIn('active_status', [1, 2])
            );

        // Apply date filter
        if ($startDate && $endDate) {
            $query->whereBetween('pay_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('pay_date', $startDate);
        } elseif ($endDate) {
            $query->whereDate('pay_date', $endDate);
        }

        // Apply optional filters
        if ($project && $project !== 'SelectProject') {
            $query->whereHas('sale', fn($q) => $q->where('project_id', $project));
        }

        if ($company && $company !== 'SelectCompany') {
            $query->whereHas('sale.project', fn($q) => $q->where('company_id', $company));
        }

        if ($sales && $sales !== 'SelectSalesMember') {
            $query->whereHas('sale', fn($q) => $q->where('sale_by', $sales));
        }

        if (!empty($handler)) {
            $query->whereHas('sale', fn($q) => $q->where('handling_person', $handler));
        }

        $payments = $query->get()->groupBy('receipt_id');

        $results = [];

        foreach ($payments as $receiptId => $group) {
            $first = $group->first();
            $sale = $first->sale;

            $results[] = [
                'receipt_id' => $receiptId,
                'pay_date' => $group->pluck('pay_date')->unique()->sort()->join(', '),
                'Customer_id' => $sale->Customer_id,
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'pay_discription' => $group->pluck('pay_discription')->unique()->sort()->join('|'),
                'company_name' => $sale->project->company->company_name ?? '',
                'project_name' => $sale->project->project_name ?? '',
                'unit_id' => $sale->unit->unit_id ?? '',
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'paid_amount' => $group->sum('paid_amount'),
            ];
        }

        return response()->json([
            'total_records' => count($results),
            'data' => $results,
        ]);
    }
}
