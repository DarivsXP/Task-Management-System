<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h2 class="font-display font-bold text-2xl text-stone-900">
                Projects
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-stone-900 text-white rounded-lg font-medium text-sm hover:bg-stone-700 transition-colors">
                + New Project
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search Bar -->
            <form action="{{ route('projects.index') }}" method="GET">
                <div class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search projects by name or description..."
                        class="flex-1 px-4 py-2 border border-stone-300 rounded-lg text-sm focus:ring-stone-400 focus:border-stone-400 bg-white placeholder-stone-400">
                    <button type="submit"
                        class="px-4 py-2 bg-stone-900 text-white rounded-lg text-sm font-medium hover:bg-stone-700 transition-colors whitespace-nowrap">
                        Search
                    </button>
                    @if(!empty($search))
                        <a href="{{ route('projects.index') }}"
                            class="px-4 py-2 border border-stone-300 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-100 transition-colors whitespace-nowrap">
                            Clear
                        </a>
                    @endif
                </div>
            </form>

            @if($projects->isEmpty())
                <div class="bg-white border border-stone-200 rounded-xl p-12 text-center">
                    <p class="text-stone-500 font-medium text-base mb-1">
                        {{ !empty($search) ? 'No projects matched "' . $search . '"' : 'No projects yet' }}
                    </p>
                    <p class="text-stone-400 text-sm mb-5">
                        {{ !empty($search) ? 'Try different search terms.' : 'Create your first project to start tracking tasks.' }}
                    </p>
                    @if(!empty($search))
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-stone-900 text-white rounded-lg font-medium text-sm hover:bg-stone-700">
                            Clear Search
                        </a>
                    @else
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-stone-900 text-white rounded-lg font-medium text-sm hover:bg-stone-700">
                            Create Project
                        </a>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($projects as $project)
                        <div class="bg-white border border-stone-200 rounded-xl flex flex-col hover:border-stone-300 hover:shadow-sm transition-all">
                            <div class="p-5 flex-1">
                                <div class="flex items-start justify-between mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold
                                        {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-50 text-blue-600' }}">
                                        {{ $project->status }}
                                    </span>
                                    <span class="text-xs text-stone-400">{{ $project->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="font-display font-bold text-lg text-stone-900 mb-1.5 leading-snug">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-stone-600 transition-colors">
                                        {{ $project->name }}
                                    </a>
                                </h3>
                                <p class="text-stone-500 text-sm line-clamp-2 leading-relaxed">
                                    {{ $project->description ?: 'No description provided.' }}
                                </p>
                            </div>
                            <div class="px-5 py-3 border-t border-stone-100 flex items-center justify-between">
                                <span class="text-xs text-stone-400 font-medium">
                                    {{ $project->tasks_count }} {{ Str::plural('task', $project->tasks_count) }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('projects.show', $project) }}" class="text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors">View</a>
                                    <span class="text-stone-200">·</span>
                                    <a href="{{ route('projects.edit', $project) }}" class="text-xs font-medium text-stone-600 hover:text-stone-900 transition-colors">Edit</a>
                                    <span class="text-stone-200">·</span>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project and all its tasks?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
