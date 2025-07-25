<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\SaleViseDueCollectionResource ;

class SaleViseDueCollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['project', 'unit', 'customer', 'handlingPerson', 'payments'])
        ->whereNotIn('active_status', [1,2]);
    

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
        return SaleViseDueCollectionResource::collection($sales);
    }
}