<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * GET /api/projects/{project}/tasks
     * List all tasks for a project, with overdue tasks sorted first.
     */
    public function index(Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        $today = now()->toDateString();

        $tasks = $project->tasks()
            ->orderByRaw("
                CASE WHEN status != 'Completed' AND due_date IS NOT NULL AND due_date < ? THEN 0 ELSE 1 END ASC
            ", [$today])
            ->orderByRaw("
                CASE WHEN status != 'Completed' AND due_date IS NOT NULL AND due_date < ? THEN due_date ELSE NULL END ASC
            ", [$today])
            ->latest()
            ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * POST /api/projects/{project}/tasks
     * Create a new task under the given project.
     */
    public function store(Request $request, Project $project): TaskResource
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date'    => ['nullable', 'date'],
            'status'      => ['sometimes', 'in:Pending,In Progress,Completed'],
        ]);

        $task = $project->tasks()->create($validated);

        $this->autoCompleteProject($project);

        return new TaskResource($task);
    }

    /**
     * GET /api/tasks/{task}
     * Show a single task.
     */
    public function show(Task $task): TaskResource
    {
        $this->authorize('view', $task->project);

        return new TaskResource($task);
    }

    /**
     * PATCH /api/tasks/{task}
     * Update a task (title, description, due_date, status).
     */
    public function update(Request $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'due_date'    => ['sometimes', 'nullable', 'date'],
            'status'      => ['sometimes', 'in:Pending,In Progress,Completed'],
        ]);

        $task->update($validated);

        $this->autoCompleteProject($task->project);

        return new TaskResource($task->fresh());
    }

    /**
     * DELETE /api/tasks/{task}
     * Delete a task.
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        $this->autoCompleteProject($project);

        return response()->json(['message' => 'Task deleted successfully.']);
    }

    /**
     * Automatically mark a project as Completed if all its tasks are completed.
     * Reverts to Active if any task is not completed (or there are no tasks).
     */
    private function autoCompleteProject(Project $project): void
    {
        $totalTasks     = $project->tasks()->count();
        $completedTasks = $project->tasks()->where('status', 'Completed')->count();

        if ($totalTasks > 0 && $completedTasks === $totalTasks) {
            if ($project->status !== 'Completed') {
                $project->update(['status' => 'Completed']);
            }
        } else {
            if ($project->status === 'Completed') {
                $project->update(['status' => 'Active']);
            }
        }
    }
}
