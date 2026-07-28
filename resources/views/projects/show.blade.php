<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.index') }}" class="text-stone-400 hover:text-stone-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-display font-bold text-3xl text-stone-900 leading-tight">{{ $project->name }}</h2>
                    <p class="text-base text-stone-400 mt-0.5">Created {{ $project->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold
                    {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-50 text-blue-600' }}">
                    {{ $project->status }}
                </span>
                <a href="{{ route('projects.edit', $project) }}" class="px-3 py-1.5 border border-stone-300 text-stone-700 rounded-lg text-sm font-medium hover:bg-stone-50 transition-colors">
                    Edit
                </a>
                <form action="{{ route('projects.destroy', $project) }}" method="POST"
                    onsubmit="return confirm('Delete this project and all its tasks?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 border border-red-200 text-red-600 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Project Description -->
            @if($project->description)
                <div class="bg-white border border-stone-200 rounded-xl p-5">
                    <p class="text-sm text-stone-400 font-semibold uppercase tracking-wider mb-1.5">Description</p>
                    <p class="text-stone-700 leading-relaxed">{{ $project->description }}</p>
                </div>
            @endif

            <!-- Tasks Panel -->
            <div class="bg-white border border-stone-200 rounded-xl">

                <!-- Panel Header: Search + Filters -->
                <div class="p-5 border-b border-stone-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <h3 class="font-display font-bold text-xl text-stone-900">Tasks</h3>
                            <p class="text-sm text-stone-400">{{ $tasks->total() }} {{ Str::plural('task', $tasks->total()) }} in this project</p>
                        </div>

                        <!-- Search + Status Filter Row -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
                            <!-- Task Search -->
                            <form action="{{ route('projects.show', $project) }}" method="GET" class="flex items-center gap-2">
                                @if(!empty($currentFilter))
                                    <input type="hidden" name="status" value="{{ $currentFilter }}">
                                @endif
                                <input type="text" name="search" value="{{ $search ?? '' }}"
                                    placeholder="Search tasks..."
                                    class="w-44 px-3 py-1.5 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400 bg-white placeholder-stone-400">
                                <button type="submit"
                                    class="px-3 py-1.5 bg-stone-900 text-white rounded-lg text-sm font-medium hover:bg-stone-700 transition-colors whitespace-nowrap">
                                    Search
                                </button>
                                @if(!empty($search))
                                    <a href="{{ route('projects.show', [$project, 'status' => $currentFilter]) }}"
                                        class="text-sm text-stone-500 hover:text-stone-800 font-medium">
                                        Clear
                                    </a>
                                @endif
                            </form>

                            <!-- Status Filter -->
                            <div class="flex items-center gap-1 bg-stone-100 p-1 rounded-lg">
                                @foreach(['' => 'All', 'Pending' => 'Pending', 'In Progress' => 'In Progress', 'Completed' => 'Done'] as $value => $label)
                                    <a href="{{ route('projects.show', array_filter(['project' => $project->id, 'status' => $value ?: null, 'search' => $search])) }}"
                                        class="px-3 py-1 rounded-md text-xs font-semibold transition-all {{ ($currentFilter ?? '') === $value ? 'bg-white text-stone-900 shadow-sm' : 'text-stone-500 hover:text-stone-800' }}">
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Task Form -->
                <div class="p-5 border-b border-stone-100 bg-stone-50">
                    <p class="text-xs font-semibold text-stone-500 uppercase tracking-wider mb-3">Add Task</p>
                    <form action="{{ route('projects.tasks.store', $project) }}" method="POST">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1">
                                <input type="text" name="title" id="title" value="{{ old('title') }}"
                                    placeholder="Task title *" required
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400 @error('title') border-red-400 @enderror">
                                @error('title')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="md:w-48">
                                <input type="text" name="description" value="{{ old('description') }}"
                                    placeholder="Description (optional)"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400">
                            </div>
                            <div class="md:w-40">
                                <input type="date" name="due_date" value="{{ old('due_date') }}"
                                    class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400">
                            </div>
                            <div class="md:w-36">
                                <select name="status" class="w-full px-3 py-2 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400 bg-white">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <button type="submit"
                                class="px-5 py-2 bg-stone-900 text-white rounded-lg text-sm font-medium hover:bg-stone-700 transition-colors whitespace-nowrap">
                                Add Task
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Task List -->
                @if($tasks->isEmpty())
                    <div class="p-10 text-center text-stone-400 text-sm">
                        {{ !empty($search) ? 'No tasks matched your search.' : 'No tasks yet. Add one above.' }}
                    </div>
                @else
                    <ul class="divide-y divide-stone-100">
                        @foreach($tasks as $task)
                            <li class="px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-3 hover:bg-stone-50 transition-colors">
                                <!-- Task Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-semibold text-base text-stone-900 {{ $task->status === 'Completed' ? 'line-through text-stone-400' : '' }}">
                                            {{ $task->title }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                            {{ $task->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : ($task->status === 'In Progress' ? 'bg-indigo-50 text-indigo-600' : 'bg-amber-50 text-amber-600') }}">
                                            {{ $task->status }}
                                        </span>
                                    </div>
                                    @if($task->description)
                                        <p class="text-sm text-stone-500 mt-0.5 truncate">{{ $task->description }}</p>
                                    @endif
                                    @if($task->due_date)
                                        <p class="text-xs text-stone-400 mt-0.5">Due {{ $task->due_date->format('M d, Y') }}</p>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <!-- Inline Status Dropdown - fixed width so long options don't expand the row -->
                                    <form action="{{ route('tasks.updateStatus', $task) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()"
                                            class="w-32 px-2 py-1.5 text-xs font-medium border border-stone-200 rounded-lg bg-white text-stone-700 focus:ring-stone-400 focus:border-stone-400 cursor-pointer truncate">
                                            <option value="Pending" {{ $task->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="In Progress" {{ $task->status === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Completed" {{ $task->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                    </form>

                                    <a href="{{ route('tasks.edit', $task) }}"
                                        class="text-xs font-medium text-stone-500 hover:text-stone-900 transition-colors">
                                        Edit
                                    </a>

                                    <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                        onsubmit="return confirm('Delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs font-medium text-red-400 hover:text-red-600 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    <div class="px-5 py-4 border-t border-stone-100">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
