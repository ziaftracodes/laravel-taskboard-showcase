@extends('tasks.layout')
@section('title', 'Dashboard')

@section('content')
{{-- Metrics --}}
<div class="metrics-row">
    <div class="metric-card">
        <div class="metric-icon" style="background: var(--primary);">
            <span class="material-symbols-outlined">assignment</span>
        </div>
        <div>
            <div class="metric-value">{{ $metrics['total'] }}</div>
            <div class="metric-label">Total Tasks</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: var(--warning);">
            <span class="material-symbols-outlined">pending</span>
        </div>
        <div>
            <div class="metric-value">{{ $metrics['in_progress'] }}</div>
            <div class="metric-label">In Progress</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: var(--success);">
            <span class="material-symbols-outlined">check_circle</span>
        </div>
        <div>
            <div class="metric-value">{{ $metrics['completed'] }}</div>
            <div class="metric-label">Completed</div>
        </div>
    </div>
    <div class="metric-card">
        <div class="metric-icon" style="background: var(--danger);">
            <span class="material-symbols-outlined">priority_high</span>
        </div>
        <div>
            <div class="metric-value">{{ $metrics['urgent'] }}</div>
            <div class="metric-label">Urgent</div>
        </div>
    </div>
</div>

{{-- Toolbar --}}
<form method="GET" action="{{ route('tasks.index') }}" class="toolbar">
    <div class="search-wrapper">
        <span class="material-symbols-outlined">search</span>
        <input type="text" name="search" class="search-input" placeholder="Search tasks..."
               value="{{ $filters['search'] ?? '' }}">
    </div>

    <select name="status" class="filter-select" onchange="this.form.submit()">
        <option value="">All Status</option>
        <option value="todo" {{ ($filters['status'] ?? '') === 'todo' ? 'selected' : '' }}>To Do</option>
        <option value="in_progress" {{ ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
    </select>

    <select name="priority" class="filter-select" onchange="this.form.submit()">
        <option value="">All Priority</option>
        <option value="urgent" {{ ($filters['priority'] ?? '') === 'urgent' ? 'selected' : '' }}>Urgent</option>
        <option value="high" {{ ($filters['priority'] ?? '') === 'high' ? 'selected' : '' }}>High</option>
        <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
        <option value="low" {{ ($filters['priority'] ?? '') === 'low' ? 'selected' : '' }}>Low</option>
    </select>

    <select name="category_id" class="filter-select" onchange="this.form.submit()">
        <option value="">All Projects</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
    </select>

    @if(!empty($filters['search']) || !empty($filters['status']) || !empty($filters['priority']) || !empty($filters['category_id']))
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">
            <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
            Clear
        </a>
    @endif
</form>

{{-- Task Grid --}}
<div class="task-grid">
    @forelse($tasks as $task)
        <div class="task-card">
            <div class="task-card-header">
                <div class="task-title {{ $task->status === 'completed' ? 'completed' : '' }}">{{ $task->title }}</div>
                <span class="badge badge-priority" style="background: {{ $task->priority_badge_color }};">{{ ucfirst($task->priority) }}</span>
            </div>

            <div class="task-meta-row">
                <span class="badge badge-status-{{ $task->status }}">
                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                </span>
                @if($task->category)
                    <span class="category-label">
                        <span class="category-dot" style="background: {{ $task->category->color }};"></span>
                        {{ $task->category->name }}
                    </span>
                @endif
            </div>

            @if($task->tags->isNotEmpty())
                <div class="tags-row">
                    @foreach($task->tags as $tag)
                        <span class="tag-chip" style="color: {{ $tag->color }}; border-color: {{ $tag->color }}33; background: {{ $tag->color }}0d;">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            <div class="task-desc">
                {{ $task->description ?? 'No description provided.' }}
            </div>

            @if($task->subtasks->isNotEmpty())
                @php
                    $done = $task->subtasks->where('is_completed', true)->count();
                    $total = $task->subtasks->count();
                    $pct = $total > 0 ? round(($done / $total) * 100) : 0;
                @endphp
                <div class="subtask-progress">
                    <span>{{ $done }}/{{ $total }}</span>
                    <div class="progress-bar">
                        <div class="progress-bar-fill" style="width: {{ $pct }}%;"></div>
                    </div>
                </div>
            @endif

            <div class="task-footer">
                <div class="task-date {{ $task->is_overdue ? 'overdue' : '' }}">
                    @if($task->due_date)
                        <span class="material-symbols-outlined" style="font-size: 14px;">{{ $task->is_overdue ? 'warning' : 'event' }}</span>
                        {{ $task->is_overdue ? 'Overdue: ' : '' }}{{ $task->due_date->format('M d') }}
                    @else
                        <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                        {{ $task->created_at->diffForHumans() }}
                    @endif
                </div>
                <div class="task-actions">
                    <form action="{{ route('tasks.toggle', $task->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn-icon" title="{{ $task->status === 'completed' ? 'Mark Incomplete' : 'Mark Complete' }}">
                            <span class="material-symbols-outlined" style="font-size: 20px; color: {{ $task->status === 'completed' ? 'var(--success)' : 'var(--text-light)' }};">
                                {{ $task->status === 'completed' ? 'check_circle' : 'radio_button_unchecked' }}
                            </span>
                        </button>
                    </form>
                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn-icon" title="Edit">
                        <span class="material-symbols-outlined" style="font-size: 20px;">edit</span>
                    </a>
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this task?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon danger" title="Delete">
                            <span class="material-symbols-outlined" style="font-size: 20px;">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <span class="material-symbols-outlined">assignment</span>
            <h2>No tasks found</h2>
            <p style="margin-top: 0.5rem; margin-bottom: 1.5rem;">
                @if(!empty($filters['search']) || !empty($filters['status']) || !empty($filters['priority']) || !empty($filters['category_id']))
                    No tasks match your current filters.
                @else
                    Get started by creating your first task.
                @endif
            </p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                <span class="material-symbols-outlined" style="font-size: 20px;">add</span>
                Create Task
            </a>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($tasks->hasPages())
    <div style="margin-top: 2rem; display: flex; justify-content: center;">
        {{ $tasks->links() }}
    </div>
@endif
@endsection
