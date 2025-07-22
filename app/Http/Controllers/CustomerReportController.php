<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerReportResource;

class CustomerReportController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('C_status', 0)
            ->with(['sales' => function($query) use ($request) {
                $query->whereNotIn('active_status', [1, 2])
                    ->with(['project.company', 'saleBy']);

                if ($request->filled('selectedProject') && $request->selectedProject !== 'SelectProject') {
                    $query->where('project_id', $request->selectedProject);
                }

                if ($request->filled('selectedCompany') && $request->selectedCompany !== 'SelectCompany') {
                    $query->whereHas('project', function ($q) use ($request) {
                        $q->where('company_id', $request->selectedCompany);
                    });
                }

                if ($request->filled('selectedSalesMember') && $request->selectedSalesMember !== 'SelectSalesMember') {
                    $query->where('sale_by', $request->selectedSalesMember);
                }

                if ($request->filled('selectedHandlingPerson') && $request->selectedHandlingPerson !== 'SelectHandlingPerson') {
                    $query->where('handling_person', $request->selectedHandlingPerson);
                }
            }])->get();

        // Filter customers with valid sales
        $filtered = $customers->filter(fn($customer) => $customer->sales->isNotEmpty())->values();

        return CustomerReportResource::collection($filtered);
    }
}
