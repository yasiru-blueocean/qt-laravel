<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlySalesSummaryController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('selectedYear');
        $project = $request->input('selectedProject');
        $company = $request->input('selectedCompany');
        $sales = $request->input('selectedSalesMember');
        $handler = $request->input('selectedHandlingPerson');

        $salesQuery = Sale::with('project')
            ->whereNotIn('active_status', [1,2]);

        if ($year && $year != 'SelectYear') {
            $salesQuery->whereYear('sale_date', $year);
        }
        if ($project && $project != 'SelectProject') {
            $salesQuery->where('project_id', $project);
        }
        if ($company && $company != 'SelectCompany') {
            $salesQuery->whereHas('project', function ($q) use ($company) {
                $q->where('company_id', $company);
            });
        }
        if ($sales && $sales != 'SelectSalesMember') {
            $salesQuery->where('sale_by', $sales);
        }
        if ($handler && $handler != 'SelectHandlingPerson') {
            $salesQuery->where('handling_person', $handler);
        }

        $salesData = $salesQuery->get();

        $grouped = $salesData->groupBy(function ($sale) {
            return Carbon::parse($sale->sale_date)->format('Y-m');
        });

        $results = [];

        foreach ($grouped as $dateKey => $salesGroup) {
            [$year, $monthNum] = explode('-', $dateKey);
            $monthName = Carbon::createFromFormat('m', $monthNum)->format('F');

            $results[] = [
                'year' => $year,
                'month' => $monthName,
                'sale_count' => $salesGroup->count(),
                'sale_unit_price_total' => $salesGroup->sum('sale_unit_price'),
                'discount_total' => $salesGroup->sum('sale_unit_discount_price'),
                'selling_price_total' => $salesGroup->sum('selling_price'),
                'selected_company' => $request->input('selectedCompany', 'SelectCompany'),
                'selected_project' => $request->input('selectedProject', 'SelectProject'),
                'selected_sales_member' => $request->input('selectedSalesMember', 'SelectSalesMember'),
            ];
        }
return response()->json([
    'total_records' => $salesData->count(),
    'data' => $results
]);

    }
}