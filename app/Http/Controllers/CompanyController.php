<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::with('CompanyCreateBy')->get();
        return response()->json([
            'message' => 'Companies fetched successfully',
            'count' => count($companies),
            'data' => CompanyResource::collection($companies),
        ]);
    }
}
