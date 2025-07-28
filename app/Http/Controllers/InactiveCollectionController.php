<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Http\Resources\InactiveCollectionResource;

class InactiveCollectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['project', 'unit', 'customer', 'handlingPerson', 'payments'])
        ->whereIn('active_status', [5,6,7]);
    

 if ($request->filled('startDate')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('pay_date', '>=', $request->startDate);
            });
        }

        if ($request->filled('endDate')) {
            $query->whereHas('payments', function ($q) use ($request) {
                $q->where('pay_date', '<=', $request->endDate);
            });
        }

        if ($request->filled('formSelectedCompany')) {
            $query->whereHas('unit', function ($q) use ($request) {
                $q->where('company_id', $request->formSelectedCompany);
            });
        }

        if ($request->filled('formSelectedProject')) {
            $query->where('project_id', $request->formSelectedProject);
        }

        if ($request->filled('formSalesPersonSelect')) {
            $query->where('sale_by', $request->formSalesPersonSelect);
        }

        if ($request->filled('formHandlingPersonSelect')) {
            $query->where('handling_person', $request->formHandlingPersonSelect);
        }

        $sales = $query->get();

        return InactiveCollectionResource::collection($sales);
    }
}