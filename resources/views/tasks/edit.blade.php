<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl md:text-3xl text-slate-900 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200 p-8">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Task Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Task Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" required autofocus
                            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('title') border-red-500 @enderror">
                        @error('title')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('description') border-red-500 @enderror">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Due Date & Time -->
                    <div class="mb-6">
                        <label for="due_date" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Due Date & Time</label>
                        <input type="datetime-local" name="due_date" id="due_date" value="{{ old('due_date', optional($task->due_date)->format('Y-m-d\TH:i')) }}"
                            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('due_date') border-red-500 @enderror">
                        @error('due_date')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-8">
                        <label for="status" class="block text-xs font-bold text-slate-700 mb-1 uppercase tracking-wider">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3 @error('status') border-red-500 @enderror">
                            <option value="Pending" {{ old('status', $task->status) === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ old('status', $task->status) === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status', $task->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('projects.show', $task->project) }}" class="px-5 py-2.5 bg-slate-100 border border-slate-300 rounded-lg font-bold text-sm text-slate-700 hover:bg-slate-200 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-700 shadow-sm transition-all">
                            Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
