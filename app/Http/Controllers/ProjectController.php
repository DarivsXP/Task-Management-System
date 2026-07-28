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
        $search = $request->query('search');

        $tasksQuery = $project->tasks()->latest();

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
            'project' => $project,
            'tasks' => $tasks,
            'currentFilter' => $statusFilter,
            'search' => $search,
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
}
