<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('company')->get()->map(function ($project) {
            return [
                'project_id' => $project->project_id,
                'project_name' => $project->project_name,
                'project_location' => $project->project_location,
                'project_status' => $project->project_status,
                'project_createby'=> $project->project_createby,
                'project_createdate'=>$project->project_createdate,
                'company_name' => $project->company->company_name ?? 'N/A',
            ];
        });

        return response()->json([
            'message' => 'fetched successfully',
            'data' => $projects
        ]);
    }
}
