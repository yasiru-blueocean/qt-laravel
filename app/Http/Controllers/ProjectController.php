<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('company')->get();
        return response()->json([
            'message' => 'Projects fetched successfully',
            'count'=> count($projects),
            'data' => ProjectResource::collection($projects),
        ]);

    }
}
