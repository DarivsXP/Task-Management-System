<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Projects') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                + Create Project
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded shadow-sm">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($projects->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No projects yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first project to organize your tasks.</p>
                    <div class="mt-6">
                        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Create Your First Project
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($projects as $project)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 flex flex-col justify-between hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $project->status === 'Completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $project->status }}
                                    </span>
                                    <span class="text-xs text-gray-400">Created {{ $project->created_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600 transition-colors">
                                        {{ $project->name }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $project->description ?: 'No description provided.' }}
                                </p>
                            </div>
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-xs font-semibold text-gray-500">
                                    {{ $project->tasks_count }} {{ Str::plural('Task', $project->tasks_count) }}
                                </span>
                                <div class="flex space-x-2">
                                    <a href="{{ route('projects.show', $project) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-900">View</a>
                                    <a href="{{ route('projects.edit', $project) }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Edit</a>
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project and all its tasks?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
