@extends('layouts.tenant.app')

@section('title', 'Task: ' . $task->title)

@section('content')
<div class="max-w-4xl mx-auto py-6 space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('operations.tasks.index') }}" class="text-sm text-slate-500 hover:text-slate-700 flex items-center gap-1 mb-2">
                <span class="material-symbols-outlined text-base">arrow_back</span> Back to Tasks
            </a>
            <h1 class="text-2xl font-bold text-slate-900">{{ $task->title }}</h1>
        </div>
        <a href="{{ route('operations.tasks.edit', $task) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors">
            <span class="material-symbols-outlined text-base">edit</span> Edit Task
        </a>
    </div>

    {{-- Task Details Card --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 space-y-5">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    {{ $task->status === 'done' ? 'bg-green-100 text-green-800' :
                       ($task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' :
                       ($task->status === 'review' ? 'bg-yellow-100 text-yellow-800' : 'bg-slate-100 text-slate-700')) }}">
                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Priority</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                    {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' :
                       ($task->priority === 'high' ? 'bg-orange-100 text-orange-800' :
                       ($task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-slate-100 text-slate-700')) }}">
                    {{ ucfirst($task->priority ?? 'normal') }}
                </span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Assigned To</p>
                <p class="text-sm font-medium text-slate-800">{{ $task->assignee?->full_name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Project</p>
                <p class="text-sm font-medium text-slate-800">{{ $task->project?->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Due Date</p>
                <p class="text-sm font-medium text-slate-800">
                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M, Y') : '-' }}
                </p>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Estimated Hours</p>
                <p class="text-sm font-medium text-slate-800">{{ $task->estimated_hours ?? '-' }}</p>
            </div>
        </div>

        @if($task->description)
        <div class="border-t border-slate-100 pt-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Description</p>
            <p class="text-sm text-slate-700 leading-relaxed">{{ $task->description }}</p>
        </div>
        @endif
    </div>

    {{-- Timesheets --}}
    @if($task->timesheets && $task->timesheets->count())
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-bold text-slate-900">Time Logs</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                <tr>
                    <th class="px-6 py-3 text-left font-bold">Employee</th>
                    <th class="px-6 py-3 text-left font-bold">Date</th>
                    <th class="px-6 py-3 text-left font-bold">Hours</th>
                    <th class="px-6 py-3 text-left font-bold">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($task->timesheets as $ts)
                <tr>
                    <td class="px-6 py-3">{{ $ts->employee?->full_name ?? '-' }}</td>
                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($ts->date)->format('d M, Y') }}</td>
                    <td class="px-6 py-3 font-medium">{{ $ts->hours }}h</td>
                    <td class="px-6 py-3 text-slate-500">{{ $ts->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
@endsection
