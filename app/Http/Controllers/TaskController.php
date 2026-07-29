<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreTaskRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->tasks()->create($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Task created successfully!');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        return view('tasks.edit', [
            'task' => $task,
            'project' => $task->project,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        $this->autoCompleteProject($task->project);

        return redirect()->route('projects.show', $task->project_id)
            ->with('success', 'Task updated successfully!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'status' => ['required', 'in:Pending,In Progress,Completed'],
        ]);

        $task->update(['status' => $validated['status']]);

        $this->autoCompleteProject($task->project);

        return redirect()->back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $project = $task->project;
        $task->delete();

        $this->autoCompleteProject($project);

        return redirect()->route('projects.show', $project->id)
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Automatically mark a project as Completed if all its tasks are completed.
     * Reverts to Active if any task is not completed (or there are no tasks).
     */
    private function autoCompleteProject(\App\Models\Project $project): void
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
