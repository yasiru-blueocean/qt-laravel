<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $companies = Company::where('company_status', 0)
            ->get();

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