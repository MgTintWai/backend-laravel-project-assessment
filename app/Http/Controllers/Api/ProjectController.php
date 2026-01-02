<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    // Old way of injecting dependencies
    // protected ProjectService $service;
    // public function __construct(ProjectService $service)
    // {
    //     $this->service = $service;
    // }

    // New way of injecting dependencies
    public function __construct(
        protected ProjectService $service
    ) {
        $this->authorizeResource(Project::class, 'project');
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 15);

        $projects = $this->service->list($perPage);

        return new ProjectCollection($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->service->create($request->validated());

        return new ProjectResource($project);
    }

    public function show(Project $project)
    {
        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, Project $project)
    {
        $project = $this->service->update($project->id, $request->validated());

        return new ProjectResource($project);
    }

    public function destroy(Project $project)
    {
        $this->service->delete($project->id);

        return response()->json([
            'version' => '1.0',
            'status'  => 204,
            'message' => 'Project deleted successfully',
        ], 204);
    }

    public function restore(Project $project)
    {
        $project = $this->service->restore($project->id);

        return new ProjectResource($project);
    }

    public function forceDelete(Project $project)
    {
        $this->service->forceDelete($project->id);

        return response()->json([
            'version' => '1.0',
            'status'  => 204,
            'message' => 'Project permanently deleted',
        ], 204);
    }


    // public function index(Request $request)
    // {
    //     $perPage = (int) $request->query('per_page', 15);

    //     $projects = $this->service->list($perPage);

    //     return new ProjectCollection($projects);
    // }

    // public function store(ProjectRequest $request)
    // {
    //     $project = $this->service->create($request->validated());

    //     return new ProjectResource($project);
    // }

    // public function show(int $id)
    // {
    //     $project = $this->service->find($id);

    //     return new ProjectResource($project);
    // }

    // public function update(ProjectRequest $request, int $id)
    // {
    //     $project = $this->service->update($id, $request->validated());

    //     return new ProjectResource($project);
    // }

    // public function destroy(int $id)
    // {
    //     $this->service->delete($id);

    //     return response()->json([
    //         'version' => '1.0',
    //         'status'  => 204,
    //         'message' => 'Project deleted successfully',
    //     ], 204);
    // }

    // public function restore(int $id)
    // {
    //     $project = $this->service->restore($id);

    //     return new ProjectResource($project);
    // }

    // public function forceDelete(int $id)
    // {
    //     $this->service->forceDelete($id);

    //     return response()->json([
    //         'version' => '1.0',
    //         'status'  => 204,
    //         'message' => 'Project permanently deleted',
    //     ], 204);
    // }
}