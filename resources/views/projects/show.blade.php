<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.index') }}" class="p-2.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-extrabold text-2xl md:text-3xl text-slate-900 leading-tight">
                        {{ $project->name }}
                    </h2>
                    <p class="text-sm font-medium text-slate-500 mt-1">
                        Created on {{ $project->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                    {{ $project->status }}
                </span>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-lg font-bold text-sm text-slate-700 hover:bg-slate-50 shadow-sm">
                    Edit Project
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? All associated tasks will be permanently removed.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-50 border border-red-200 rounded-lg font-bold text-sm text-red-600 hover:bg-red-100 shadow-sm">
                        Delete Project
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-lg shadow-sm">
                    <p class="font-semibold text-base">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Project Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 p-6 md:p-8">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Project Description</h3>
                <p class="text-slate-800 text-base md:text-lg whitespace-pre-line leading-relaxed">
                    {{ $project->description ?: 'No detailed description provided for this project.' }}
                </p>
            </div>

            <!-- Task Management Section -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 p-6 md:p-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between pb-6 border-b border-slate-200 mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Project Tasks</h3>
                        <p class="text-sm font-medium text-slate-500">Manage tasks, search keywords, and filter by status.</p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Task Search Bar -->
                        <form action="{{ route('projects.show', $project) }}" method="GET" class="flex items-center space-x-2">
                            @if(!empty($currentFilter))
                                <input type="hidden" name="status" value="{{ $currentFilter }}">
                            @endif
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search tasks..."
                                    class="w-full sm:w-48 pl-8 pr-3 py-1.5 text-xs rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500">
                                <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none text-slate-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded-lg text-xs font-bold hover:bg-slate-900 transition-colors">
                                Search
                            </button>
                            @if(!empty($search))
                                <a href="{{ route('projects.show', [$project, 'status' => $currentFilter]) }}" class="px-2.5 py-1.5 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200">
                                    Clear
                                </a>
                            @endif
                        </form>

                        <!-- Status Filter Tabs -->
                        <div class="flex items-center space-x-1 bg-slate-100 p-1 rounded-xl border border-slate-200">
                            <a href="{{ route('projects.show', [$project, 'search' => $search]) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ empty($currentFilter) ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                All
                            </a>
                            <a href="{{ route('projects.show', [$project, 'status' => 'Pending', 'search' => $search]) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $currentFilter === 'Pending' ? 'bg-white text-amber-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                Pending
                            </a>
                            <a href="{{ route('projects.show', [$project, 'status' => 'In Progress', 'search' => $search]) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $currentFilter === 'In Progress' ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                In Progress
                            </a>
                            <a href="{{ route('projects.show', [$project, 'status' => 'Completed', 'search' => $search]) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all {{ $currentFilter === 'Completed' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                                Completed
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Create Task Form Component -->
                <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 mb-8">
                    <h4 class="text-base font-bold text-slate-900 mb-4">+ Add New Task</h4>
                    <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-4">
                                <label for="title" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Task Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Task title..." required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label for="description" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Description</label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Optional details..."
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('description') border-red-500 @enderror">
                                @error('description')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="due_date" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('due_date') border-red-500 @enderror">
                                @error('due_date')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="status" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                    class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('status') border-red-500 @enderror">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1 flex items-end">
                                <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-sm transition-colors">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tasks List -->
                @if($tasks->isEmpty())
                    <div class="text-center py-10 text-slate-500 text-base font-medium">
                        {{ !empty($search) ? 'No tasks match your search criteria.' : 'No tasks found for this project.' }}
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($tasks as $task)
                            <div class="p-5 rounded-xl border border-slate-200 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-slate-300 hover:shadow-sm transition-all">
                                <div class="space-y-1.5 flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="text-base font-bold text-slate-900 {{ $task->status === 'Completed' ? 'line-through text-slate-400' : '' }}">
                                            {{ $task->title }}
                                        </h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider
                                            {{ $task->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : ($task->status === 'In Progress' ? 'bg-indigo-100 text-indigo-800 border border-indigo-200' : 'bg-amber-100 text-amber-800 border border-amber-200') }}">
                                            {{ $task->status }}
                                        </span>
                                    </div>
                                    @if($task->description)
                                        <p class="text-sm text-slate-600 leading-relaxed">{{ $task->description }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs font-semibold text-slate-400 pt-1">
                                        @if($task->due_date)
                                            <span class="flex items-center space-x-1.5 text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Due: {{ $task->due_date->format('M d, Y') }}</span>
                                            </span>
                                        @else
                                            <span>No due date</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100">
                                    <!-- Quick Status Change Form -->
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-xs font-bold py-1.5 px-3 rounded-lg border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                                            <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>

                                    <!-- Edit Task -->
                                    <a href="{{ route('tasks.edit', $task) }}" class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">
                                        Edit
                                    </a>

                                    <!-- Delete Task -->
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination Links -->
                    <div class="pt-6">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
