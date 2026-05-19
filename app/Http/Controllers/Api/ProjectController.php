<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with(['creator', 'tasks.assignee'])
            ->withCount('tasks')
            ->orderByDesc('created_at')
            ->get();

        return ApiResponse::success(['projects' => $projects]);
    }

    public function show(Project $project)
    {
        $project->load(['creator', 'tasks.assignee']);

        return ApiResponse::success(['project' => $project]);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => $request->user()->id,
        ]);

        $project->load('creator');

        return ApiResponse::success(['project' => $project], 'Project created', 201);
    }
}
