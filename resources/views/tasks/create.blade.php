@extends('tasks.layout')
@section('title', 'New Task')

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
            Back
        </a>
    </div>

    <div class="card">
        <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">Create New Task</h1>

        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control"
                       value="{{ old('title') }}" placeholder="What needs to be done?" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control"
                          placeholder="Add details, notes, or context...">{{ old('description') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="category_id">Project</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">No project</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="todo" {{ old('status', 'todo') === 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control"
                           value="{{ old('due_date') }}">
                </div>
            </div>

            @if($tags->isNotEmpty())
            <div class="form-group">
                <label class="form-label">Tags</label>
                <div class="checkbox-group">
                    @foreach($tags as $tag)
                        <label class="checkbox-chip">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $tag->color }};"></span>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="form-group" id="subtasks-section">
                <label class="form-label">Subtasks</label>
                <div id="subtasks-container">
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <input type="text" name="subtasks[]" class="form-control" placeholder="Add a subtask..." style="flex: 1;">
                    </div>
                </div>
                <button type="button" onclick="addSubtask()" class="btn btn-secondary btn-sm" style="margin-top: 0.25rem;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">add</span>
                    Add Subtask
                </button>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--border);">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add_task</span>
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function addSubtask() {
    const container = document.getElementById('subtasks-container');
    const row = document.createElement('div');
    row.style.cssText = 'display: flex; gap: 0.5rem; margin-bottom: 0.5rem;';
    row.innerHTML = `
        <input type="text" name="subtasks[]" class="form-control" placeholder="Add a subtask..." style="flex: 1;">
        <button type="button" onclick="this.parentElement.remove()" class="btn-icon danger" title="Remove">
            <span class="material-symbols-outlined" style="font-size: 18px;">close</span>
        </button>
    `;
    container.appendChild(row);
    row.querySelector('input').focus();
}
</script>
@endsection
