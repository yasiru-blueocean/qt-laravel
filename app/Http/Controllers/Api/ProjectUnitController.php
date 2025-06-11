<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectUnit;
use Illuminate\Http\Request;

class ProjectUnitController extends Controller
{
    public function index(Request $request, $projectId)
    {
        $units = ProjectUnit::with([
            'company:company_id,company_id,company_name',
            'project:project_id,project_id,project_name'
        ])
            ->where('project_id', $projectId)
            ->where('Unit_delete',0)
            ->get();

        return response()->json($units);
    }
}
