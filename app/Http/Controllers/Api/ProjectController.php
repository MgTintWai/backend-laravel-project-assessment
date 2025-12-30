<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Services\ProjectService;

class ProjectController extends Controller
{
    // Old way of injecting dependencies
    // protected ProjectService $service;
    // public function __construct(ProjectService $service)
    // {
    //     $this->service = $service;
    // }

    public function __construct(
        protected ProjectService $service
    ) {}

    public function index()
    {
        $projects = $this->service->list();

        return new ProjectCollection($projects);
    }

    public function store(ProjectRequest $request)
    {
        $project = $this->service->create($request->validated());

        return new ProjectResource($project);
    }

    public function show(int $id)
    {
        $project = $this->service->find($id);

        return new ProjectResource($project);
    }

    public function update(ProjectRequest $request, int $id)
    {
        $project = $this->service->update($id, $request->validated());

        return new ProjectResource($project);
    }

    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->json([
            'version' => '1.0',
            'status'  => 204,
            'message' => 'Project deleted successfully',
        ], 204);
    }

    public function restore(int $id)
    {
        $project = $this->service->restore($id);

        return new ProjectResource($project);
    }

    public function forceDelete(int $id)
    {
        $this->service->forceDelete($id);

        return response()->json([
            'version' => '1.0',
            'status'  => 204,
            'message' => 'Project permanently deleted',
        ], 204);
    }
}