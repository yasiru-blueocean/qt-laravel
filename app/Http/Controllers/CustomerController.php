<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
     public function index()
     {
        $customers = Customer::all();
        return response()->json([
            'message' => 'Customers fetched successfully',
            'count' => count($customers),
            'data' => CustomerResource::collection($customers),
        ]);
     }
}
