<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\SalesSummaryResource;

class SalesSummaryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('active_status', 2);

        $query = Sale::with(['project.company', 'unit', 'customer', 'handlingPerson', 'payments'])
            ->whereNotIn('active_status', [1, 2]); // Default: show all except deleted or irrelevant

        //  Filter by active/inactive status
        if ($status == 1) {
            $query->whereIn('active_status', [0, 3, 4]); // Active
        } elseif ($status == 0) {
            $query->whereIn('active_status', [5, 6, 7]); // Inactive
        }

        //  Date range filter
        if ($request->filled('startDate') && $request->filled('endDate')) {
            $query->whereBetween('sale_date', [$request->startDate, $request->endDate]);
        } elseif ($request->filled('startDate')) {
            $query->whereDate('sale_date', $request->startDate);
        } elseif ($request->filled('endDate')) {
            $query->whereDate('sale_date', $request->endDate);
        }

        //  Project filter
        if ($request->filled('selectedProject') && $request->selectedProject !== 'SelectProject') {
            $query->where('project_id', $request->selectedProject);
        }

        //  Company filter
        if ($request->filled('selectedCompany') && $request->selectedCompany !== 'SelectCompany') {
            $query->whereHas('project.company', function ($q) use ($request) {
                $q->where('company_id', $request->selectedCompany);
            });
        }

        //  Sales Member filter
        if ($request->filled('selectedSalesMember') && $request->selectedSalesMember !== 'SelectSalesMember') {
            $query->where('sale_by', $request->selectedSalesMember);
        }

        //  Handling Person filter
        if ($request->filled('selectedHandlingPerson') && $request->selectedHandlingPerson !== 'SelectHandlingPerson') {
            $query->where('handling_person', $request->selectedHandlingPerson);
        }

        //  Get all filtered sales
        $sales = $query->get();

        //  Return formatted resource collection
        return SalesSummaryResource::collection($sales);
    }
}
