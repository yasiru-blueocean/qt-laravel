<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectUnits;
use App\Http\Resources\ProjectUnitResource;

class ProjectUnitController extends Controller
{
    public function index(Request $request)
    {
       $projectId = $request->query('project_id');

       if(!$projectId)
       {
        return response()->json([
            'message' => 'missing required project id',
        ], 400);
       }

       $projectUnits = ProjectUnits::where('project_id' , $projectId)
                       ->where('Unit_delete' , '!=', '1')
                       ->get();
        return response()->json([
            'message' => 'Project Units fetched successfully',
            'count' => count($projectUnits),
            'data' => ProjectUnitResource::collection($projectUnits),
        ]);
    }
}
