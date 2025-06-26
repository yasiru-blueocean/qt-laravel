<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use Carbon\Carbon;

class SaleSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        $activeStatus = $request->input('active_status', 2); // Default: overall
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $project = $request->input('selectedProject');
        $company = $request->input('selectedCompany');
        $sales = $request->input('selectedSalesMember');
        $handler = $request->input('selectedHandlingPerson');

        $query = Sale::with(['project.company', 'unit', 'customer', 'handlingPerson', 'payments'])
            ->whereNotIn('active_status', [1, 2]);

        // Filter by active/inactive
        if ($activeStatus == 1) {
            $query->whereIn('active_status', [0, 3, 4]);
        } elseif ($activeStatus == 0) {
            $query->whereIn('active_status', [5, 6, 7]);
        }

       if ($startDate && $endDate) {
            $query->whereBetween('sale_date', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('sale_date', '=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('sale_date', '=', $endDate);
        }


        if ($project && $project !== 'SelectProject') {
            $query->where('project_id', $project);
        }

        if ($company && $company !== 'SelectCompany') {
            $query->whereHas('project', function ($q) use ($company) {
                $q->where('company_id', $company);
            });
        }

        if ($sales && $sales !== 'SelectSalesMember') {
            $query->where('sale_by', $sales);
        }

        if ($handler) {
            $query->where('handling_person', $handler);
        }

        $sales = $query->get();

        $data = [];

        foreach ($sales as $sale) {
            $paid = $sale->payments->where('active_status', 0)->sum('paid_amount');
            $balance = $sale->selling_price - $paid;

            $data[] = [
                'sale_date' => $sale->sale_date,
                'sale_id' => $sale->sale_id,
                'project_id' => $sale->project_id,
                'project_name' => $sale->project->project_name ?? '',
                'unit_id' => $sale->unit_id,
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'company_name' => $sale->project->company->company_name ?? '',
                'Customer_id' => $sale->Customer_id,
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'handlingPersonFName' => $sale->handlingPerson->U_FName ?? '',
                'handlingPersonLName' => $sale->handlingPerson->U_LName ?? '',
                'active_status' => $sale->active_status,
                'selling_price' => $sale->selling_price,
                'paid_amount' => $paid,
                'balance' => $balance,
            ];
        }

        return response()->json([
            'total' => count($data),
            'data' => $data,
        ]);
    }
}
