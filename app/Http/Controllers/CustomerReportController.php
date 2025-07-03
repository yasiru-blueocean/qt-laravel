<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Project;
use App\Models\User;

class CustomerReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('C_status', 0);

        // Filters
        if ($request->filled('selectedProject') && $request->selectedProject !== 'SelectProject') {
            $query->whereHas('sales', function ($q) use ($request) {
                $q->where('project_id', $request->selectedProject)
                  ->whereNotIn('active_status', [1, 2]);
            });
        }
        if ($request->filled('selectedCompany') && $request->selectedCompany !== 'SelectCompany') {
            $query->whereHas('sales', function ($q) use ($request) {
                $q->whereHas('project', function ($q2) use ($request) {
                    $q2->where('company_id', $request->selectedCompany);
                })
                ->whereNotIn('active_status', [1, 2]);
            });
        }
        if ($request->filled('selectedSalesMember') && $request->selectedSalesMember !== 'SelectSalesMember') {
            $query->whereHas('sales', function ($q) use ($request) {
                $q->where('sale_by', $request->selectedSalesMember)
                  ->whereNotIn('active_status', [1, 2]);
            });
        }
        if ($request->filled('selectedHandlingPerson') && $request->selectedHandlingPerson !== 'SelectHandlingPerson') {
            $query->whereHas('sales', function ($q) use ($request) {
                $q->where('handling_person', $request->selectedHandlingPerson)
                  ->whereNotIn('active_status', [1, 2]);
            });
        }

        $customers = $query->with([
            'sales' => function ($q) {
                $q->whereNotIn('active_status', [1, 2])
                  ->with(['user', 'project.company', 'unit']);
            }
        ])->get();

        // Build result array
        $results = [];
        $total_purchases = 0;
        foreach ($customers as $customer) {
            if ($customer->sales->count() > 0) {
                $purchases = [];
                $salesPersons = [];
                $companies = [];
                $projects = [];
                $units = [];
                foreach ($customer->sales as $sale) {
                    $purchases[] = $sale->sale_id;
                    $salesPersons[] = $sale->user ? $sale->user->U_FName : '';
                    $companies[] = $sale->project && $sale->project->company ? $sale->project->company->company_name : '';
                    $projects[] = $sale->project ? $sale->project->project_name : '';
                    $units[] = $sale->unit ? $sale->unit->unit_Name : '';
                }
                $total_purchases += count($purchases);
                $results[] = [
                    'C_Title'         => $customer->C_Title,
                    'C_namewinitials' => $customer->C_namewinitials,
                    'C_NIC'           => $customer->C_NIC,
                    'purchase_ids'    => $purchases,
                    'f_name_array'    => $salesPersons,
                    'company_names'   => $companies,
                    'project_names'   => $projects,
                    'unit_names'      => $units,
                    'customer_id'     => $customer->Customer_id,
                    'purchase_count'  => count($purchases),
                ];
            }
        }
        $total_customers = count($results);

        $filterDetails = [];

        if ($request->filled('selectedCompany') && $request->selectedCompany !== 'SelectCompany') {
            $company = Company::find($request->selectedCompany);
            $filterDetails['company_name'] = $company ? $company->company_name : null;
        }

        if ($request->filled('selectedProject') && $request->selectedProject !== 'SelectProject') {
            $project = Project::find($request->selectedProject);
            $filterDetails['project_name'] = $project ? $project->project_name : null;
        }

        if ($request->filled('selectedSalesMember') && $request->selectedSalesMember !== 'SelectSalesMember') {
            $user = User::find($request->selectedSalesMember);
            $filterDetails['sales_member_name'] = $user ? $user->U_FName . ' ' . $user->U_LName : null;
        }

        if ($request->filled('selectedHandlingPerson') && $request->selectedHandlingPerson !== 'SelectHandlingPerson') {
            $user = User::find($request->selectedHandlingPerson);
            $filterDetails['handling_person_name'] = $user ? $user->U_FName . ' ' . $user->U_LName : null;
        }

        return response()->json([
            'total_customers' => $total_customers,
            'total_purchases' => $total_purchases,
            'customers'       => $results,
            'filters'         => $filterDetails
        ]);
    }
}
