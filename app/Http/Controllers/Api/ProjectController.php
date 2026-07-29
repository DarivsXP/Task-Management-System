<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * GET /api/projects
     * List all projects belonging to the authenticated user.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $now = now()->toDateTimeString();

        $projects = $request->user()
            ->projects()
            ->withCount('tasks')
            ->withCount(['tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'Completed')])
            ->withCount(['tasks as overdue_tasks_count' => fn ($q) => $q
                ->where('status', '!=', 'Completed')
                ->whereNotNull('due_date')
                ->where('due_date', '<', $now)
            ])
            ->latest()
            ->get();

        return ProjectResource::collection($projects);
    }

    /**
     * POST /api/projects
     * Create a new project.
     */
    public function store(Request $request): ProjectResource
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['sometimes', 'in:Active,Completed'],
        ]);

        $project = $request->user()->projects()->create($validated);

        return new ProjectResource($project->loadCount('tasks'));
    }

    /**
     * GET /api/projects/{project}
     * Show a single project with its tasks.
     */
    public function show(Project $project): ProjectResource
    {
        $this->authorize('view', $project);

        $project->load('tasks');
        $project->loadCount([
            'tasks',
            'tasks as completed_tasks_count' => fn ($q) => $q->where('status', 'Completed'),
            'tasks as overdue_tasks_count'   => fn ($q) => $q
                ->where('status', '!=', 'Completed')
                ->whereNotNull('due_date')
                ->where('due_date', '<', now()->toDateTimeString()),
        ]);

        return new ProjectResource($project);
    }

    /**
     * PATCH /api/projects/{project}
     * Update a project's name, description, or status.
     */
    public function update(Request $request, Project $project): ProjectResource
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status'      => ['sometimes', 'in:Active,Completed'],
        ]);

        $project->update($validated);

        return new ProjectResource($project->loadCount('tasks'));
    }

    /**
     * DELETE /api/projects/{project}
     * Delete a project and all its tasks.
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully.']);
    }
}
