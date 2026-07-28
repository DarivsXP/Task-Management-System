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

        return redirect()->back()->with('success', 'Task status updated!');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Task deleted successfully!');
    }
}
