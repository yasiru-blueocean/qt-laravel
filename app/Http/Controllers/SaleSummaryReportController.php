<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Project;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;

class SaleSummaryReportController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('active_status', 2);
        $query = Sale::with(['project', 'project.company', 'unit', 'customer', 'handlingPerson', 'payments'])
            ->whereNotIn('active_status', [1, 2]);

        // Status Filtering
        if ($status == 1) {
            $query->whereIn('active_status', [0, 3, 4]);
        } elseif ($status == 0) {
            $query->whereIn('active_status', [5, 6, 7]);
        }

        // Filters
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereDate('sale_date', '>=', $request->startDate)
                ->whereDate('sale_date', '<=', $request->endDate);
        } else {
            if ($request->filled('startDate')) {
                $query->whereDate('sale_date', $request->startDate);
            }
            if ($request->filled('endDate')) {
                $query->whereDate('sale_date', $request->endDate);
            }
        }


        if ($request->filled('selectedProject') && $request->selectedProject !== 'SelectProject') {
            $query->where('project_id', $request->selectedProject);
        }

        if ($request->filled('selectedCompany') && $request->selectedCompany !== 'SelectCompany') {
            $query->whereHas('project.company', function ($q) use ($request) {
                $q->where('company_id', $request->selectedCompany);
            });
        }

        if ($request->filled('selectedSalesMember') && $request->selectedSalesMember !== 'SelectSalesMember') {
            $query->where('sale_by', $request->selectedSalesMember);
        }

        if ($request->filled('selectedHandlingPerson')) {
            $query->where('handling_person', $request->selectedHandlingPerson);
        }

        $results = $query->get()->map(function ($sale) {
            $paidAmount = $sale->payments->where('active_status', 0)->sum('paid_amount');
            return [
                'sale_id' => $sale->sale_id,
                'sale_date' => $sale->sale_date,
                'project_name' => $sale->project->project_name ?? '',
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'company_name' => $sale->project->company->company_name ?? '',
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'handlingPersonFName' => $sale->handlingPerson->U_FName ?? '',
                'handlingPersonLName' => $sale->handlingPerson->U_LName ?? '',
                'selling_price' => $sale->selling_price,
                'paid_amount' => $paidAmount,
                'balance' => $sale->selling_price - $paidAmount,
                'active_status' => $sale->active_status
            ];
        });

        return response()->json($results);
    }
}
