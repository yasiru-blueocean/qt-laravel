<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;

class InactiveCollectionReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $selectedCompany = $request->input('formSelectedCompany');
        $selectedProject = $request->input('formSelectedProject');
        $selectedSalesPerson = $request->input('formSalesPersonSelect');
        $selectedHandlingPerson = $request->input('formHandlingPersonSelect');

        $salesQuery = Sale::with(['project', 'unit', 'customer', 'handlingPerson'])
            ->whereIn('active_status', [5,6,7]);

        // 🔍 Filter by project/company
        if ($selectedCompany && $selectedCompany !== 'Select Company') {
            $salesQuery->whereHas('unit', fn($q) => $q->where('company_id', $selectedCompany));
        }

        if ($selectedProject && $selectedProject !== 'Select Project') {
            $salesQuery->where('project_id', $selectedProject);
        }

        if ($selectedSalesPerson && $selectedSalesPerson !== 'Select Sales Person') {
            $salesQuery->where('sale_by', $selectedSalesPerson);
        }

        if ($selectedHandlingPerson) {
            $salesQuery->where('handling_person', $selectedHandlingPerson);
        }

        $sales = $salesQuery->get();

        $result = [];

        foreach ($sales as $sale) {
            $paymentsQuery = Payment::where('sale_id', $sale->sale_id)->where('active_status', 0);

            if ($startDate && $endDate) {
                $paymentsQuery->whereBetween('pay_date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $paymentsQuery->whereDate('pay_date', $startDate);
            } elseif ($endDate) {
                $paymentsQuery->whereDate('pay_date', $endDate);
            }

            $totalPaid = $paymentsQuery->sum('paid_amount');

            $result[] = [
                'sale_id' => $sale->sale_id,
                'sale_date' => $sale->sale_date,
                'project_name' => $sale->project->project_name ?? '',
                'unit_Name' => $sale->unit->unit_Name ?? '',
                'C_namewinitials' => $sale->customer->C_namewinitials ?? '',
                'handlingPersonFName' => $sale->handlingPerson->U_FName ?? '',
                'handlingPersonLName' => $sale->handlingPerson->U_LName ?? '',
                'salesActiveStatus' => $sale->active_status,
                'selling_price' => $sale->selling_price,
                'totalPaid' => $totalPaid
            ];
        }

        return response()->json($result);
    }
}
