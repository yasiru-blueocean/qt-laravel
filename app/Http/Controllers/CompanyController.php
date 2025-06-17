<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
      $companies = Company::with(['creator:U_id,U_FName,U_LName'])
        ->where('company_status', 0)
        ->get()
        ->map(function ($company) {
            return [
                'company_id' => $company->company_id,
                'company_name' => $company->company_name,
                'company_description' => $company->company_description,
                'company_address' => $company->company_address,
                'company_createdate' => $company->company_createdate,
                'company_status' => $company->company_status,
                'company_createby' => $company->creator ? $company->creator->U_FName . ' ' . $company->creator->U_LName : ''
            ];
        });
        if ($companies->isEmpty()) {
            return response()->json([
                'message' => 'not available',
                'data' => []
            ],404);
        }

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $companies
        ]);
    }
}