<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                        {{ $project->name }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Created on {{ $project->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                    {{ $project->status }}
                </span>
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 hover:bg-gray-50">
                    Edit Project
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project? All associated tasks will be permanently removed.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-200 rounded-md font-semibold text-xs text-red-600 hover:bg-red-100">
                        Delete Project
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Project Overview Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Description</h3>
                <p class="text-gray-700 text-sm whitespace-pre-line leading-relaxed">
                    {{ $project->description ?: 'No detailed description provided for this project.' }}
                </p>
            </div>

            <!-- Task Management Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between pb-6 border-b border-gray-200 mb-6 space-y-4 md:space-y-0">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Tasks</h3>
                        <p class="text-xs text-gray-500">Manage tasks and track due dates for this project.</p>
                    </div>

                    <!-- Status Filter Tabs -->
                    <div class="flex items-center space-x-1 bg-gray-100 p-1 rounded-lg">
                        <a href="{{ route('projects.show', $project) }}"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ empty($currentFilter) ? 'bg-white text-indigo-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                            All
                        </a>
                        <a href="{{ route('projects.show', [$project, 'status' => 'Pending']) }}"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $currentFilter === 'Pending' ? 'bg-white text-amber-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                            Pending
                        </a>
                        <a href="{{ route('projects.show', [$project, 'status' => 'In Progress']) }}"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $currentFilter === 'In Progress' ? 'bg-white text-indigo-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                            In Progress
                        </a>
                        <a href="{{ route('projects.show', [$project, 'status' => 'Completed']) }}"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors {{ $currentFilter === 'Completed' ? 'bg-white text-emerald-600 shadow-sm font-semibold' : 'text-gray-600 hover:text-gray-900' }}">
                            Completed
                        </a>
                    </div>
                </div>

                <!-- Create Task Form Component -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 mb-6">
                    <h4 class="text-sm font-bold text-gray-800 mb-3">+ Add New Task</h4>
                    <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <div class="md:col-span-4">
                                <label for="title" class="block text-xs font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Task title..." required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('title') border-red-500 @enderror">
                                @error('title')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-3">
                                <label for="description" class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Optional details..."
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('description') border-red-500 @enderror">
                                @error('description')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="due_date" class="block text-xs font-medium text-gray-700 mb-1">Due Date</label>
                                <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('due_date') border-red-500 @enderror">
                                @error('due_date')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                                <select name="status" id="status" required
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm @error('status') border-red-500 @enderror">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-1 flex items-end">
                                <button type="submit" class="w-full py-2 bg-indigo-600 text-white rounded-md text-xs font-semibold hover:bg-indigo-700 transition-colors">
                                    Add
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Tasks List -->
                @if($tasks->isEmpty())
                    <div class="text-center py-8 text-gray-500 text-sm">
                        No tasks found matching filter standard.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($tasks as $task)
                            <div class="p-4 rounded-lg border border-gray-200 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-gray-300 transition-colors">
                                <div class="space-y-1 flex-1">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="text-sm font-bold text-gray-900 {{ $task->status === 'Completed' ? 'line-through text-gray-400' : '' }}">
                                            {{ $task->title }}
                                        </h4>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                            {{ $task->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : ($task->status === 'In Progress' ? 'bg-indigo-100 text-indigo-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $task->status }}
                                        </span>
                                    </div>
                                    @if($task->description)
                                        <p class="text-xs text-gray-600">{{ $task->description }}</p>
                                    @endif
                                    <div class="flex items-center space-x-4 text-xs text-gray-400 pt-1">
                                        @if($task->due_date)
                                            <span class="flex items-center space-x-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Due: {{ $task->due_date->format('M d, Y') }}</span>
                                            </span>
                                        @else
                                            <span>No due date</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center space-x-3 border-t md:border-t-0 pt-3 md:pt-0 border-gray-100">
                                    <!-- Quick Status Change Form -->
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="text-xs py-1 px-2 rounded border-gray-300 focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>

                                    <!-- Edit Task -->
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-xs text-indigo-600 hover:text-indigo-900 font-medium">
                                        Edit
                                    </a>

                                    <!-- Delete Task -->
                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
