<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $search = $request->query('search');

        $projectsQuery = $request->user()
            ->projects()
            ->withCount('tasks')
            ->withCount(['tasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'Completed');
            }])
            ->withCount(['tasks as overdue_tasks_count' => function ($q) {
                $q->where('status', '!=', 'Completed')
                  ->whereNotNull('due_date')
                  ->where('due_date', '<', now()->toDateString());
            }])
            ->latest();

        if ($search) {
            $projectsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $projects = $projectsQuery->paginate(6)->withQueryString();

        return view('projects.index', compact('projects', 'search'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $request->user()->projects()->create($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully!');
    }

    public function show(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        $statusFilter = $request->query('status');
        $search       = $request->query('search');

        // Progress stats (always on full task list, not filtered)
        $totalTasks     = $project->tasks()->count();
        $completedTasks = $project->tasks()->where('status', 'Completed')->count();
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $today = now()->toDateString();

        $tasksQuery = $project->tasks()
            // Overdue tasks (non-completed, past due date) sort first
            ->orderByRaw("
                CASE
                    WHEN status != 'Completed' AND due_date IS NOT NULL AND due_date < ? THEN 0
                    ELSE 1
                END ASC
            ", [$today])
            // Within overdue group: most overdue (earliest due_date) first
            ->orderByRaw("
                CASE
                    WHEN status != 'Completed' AND due_date IS NOT NULL AND due_date < ? THEN due_date
                    ELSE NULL
                END ASC
            ", [$today])
            // Everything else: newest first
            ->latest();

        if ($statusFilter && in_array($statusFilter, ['Pending', 'In Progress', 'Completed'])) {
            $tasksQuery->where('status', $statusFilter);
        }

        if ($search) {
            $tasksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $tasks = $tasksQuery->paginate(5)->withQueryString();

        return view('projects.show', [
            'project'         => $project,
            'tasks'           => $tasks,
            'currentFilter'   => $statusFilter,
            'search'          => $search,
            'totalTasks'      => $totalTasks,
            'completedTasks'  => $completedTasks,
            'progressPercent' => $progressPercent,
        ]);
    }

    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully!');
    }

    public function duplicate(Project $project, Request $request)
    {
        $this->authorize('view', $project);

        // Clone the project
        $copy = $request->user()->projects()->create([
            'name'        => $project->name . ' (Copy)',
            'description' => $project->description,
            'status'      => $project->status,
        ]);

        // Clone all tasks
        foreach ($project->tasks as $task) {
            $copy->tasks()->create([
                'title'       => $task->title,
                'description' => $task->description,
                'status'      => $task->status,
                'due_date'    => $task->due_date,
            ]);
        }

        return redirect()->route('projects.show', $copy)
            ->with('success', 'Project duplicated successfully!');
    }
}
