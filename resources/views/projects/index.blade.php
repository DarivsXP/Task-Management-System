<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h2 class="font-extrabold text-2xl md:text-3xl text-slate-900 leading-tight">
                {{ __('Projects Dashboard') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-indigo-700 shadow-sm transition ease-in-out duration-150">
                + Create New Project
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-xl shadow-sm">
                    <p class="font-semibold text-base">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Search Bar Component -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
                <form action="{{ route('projects.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search projects by name or description..."
                            class="block w-full pl-10 pr-4 py-2.5 border-slate-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex items-center space-x-2 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-slate-800 text-white rounded-lg font-bold text-sm hover:bg-slate-900 transition-colors">
                            Search
                        </button>
                        @if(!empty($search))
                            <a href="{{ route('projects.index') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-bold text-sm text-center hover:bg-slate-200 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if($projects->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-12 text-center border border-slate-200">
                    <svg class="mx-auto h-16 w-16 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-4 text-xl font-bold text-slate-900">
                        {{ !empty($search) ? 'No matching projects found' : 'No projects created yet' }}
                    </h3>
                    <p class="mt-2 text-base text-slate-500">
                        {{ !empty($search) ? 'Try adjusting your search terms.' : 'Get started by creating your first project to organize and track tasks.' }}
                    </p>
                    <div class="mt-6">
                        @if(!empty($search))
                            <a href="{{ route('projects.index') }}" class="inline-flex items-center px-6 py-3 bg-slate-800 text-white rounded-lg font-bold text-base hover:bg-slate-900 shadow-sm">
                                Clear Search Filter
                            </a>
                        @else
                            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg font-bold text-base hover:bg-indigo-700 shadow-sm">
                                Create Your First Project
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-slate-200 flex flex-col justify-between hover:shadow-md hover:border-slate-300 transition-all">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-blue-100 text-blue-800 border border-blue-200' }}">
                                        {{ $project->status }}
                                    </span>
                                    <span class="text-xs font-medium text-slate-400">Created {{ $project->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600 transition-colors">
                                        {{ $project->name }}
                                    </a>
                                </h3>
                                <p class="text-slate-600 text-base mb-4 line-clamp-3 leading-relaxed">
                                    {{ $project->description ?: 'No detailed description provided.' }}
                                </p>
                            </div>
                            <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-between items-center">
                                <span class="text-sm font-bold text-slate-500">
                                    {{ $project->tasks_count }} {{ Str::plural('Task', $project->tasks_count) }}
                                </span>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('projects.show', $project) }}" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-md text-xs font-bold hover:bg-indigo-100 transition-colors">View</a>
                                    <a href="{{ route('projects.edit', $project) }}" class="px-3 py-1.5 bg-slate-100 text-slate-700 rounded-md text-xs font-bold hover:bg-slate-200 transition-colors">Edit</a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project and all its tasks?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-md text-xs font-bold hover:bg-red-100 transition-colors">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination Links -->
                <div class="pt-4">
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
