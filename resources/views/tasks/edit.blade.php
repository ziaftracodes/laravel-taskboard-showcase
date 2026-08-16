@extends('tasks.layout')
@section('title', 'Edit: ' . $task->title)

@section('content')
<div style="max-width: 720px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem;">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary btn-sm">
            <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
            Back
        </a>
    </div>

    <div class="card">
        <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">Edit Task</h1>

        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control"
                       value="{{ old('title', $task->title) }}" required autofocus>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-control">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="category_id">Project</label>
                    <select id="category_id" name="category_id" class="form-control">
                        <option value="">No project</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $task->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="priority">Priority</label>
                    <select id="priority" name="priority" class="form-control">
                        <option value="low" {{ old('priority', $task->priority) === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ old('priority', $task->priority) === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ old('priority', $task->priority) === 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ old('priority', $task->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="todo" {{ old('status', $task->status) === 'todo' ? 'selected' : '' }}>To Do</option>
                        <option value="in_progress" {{ old('status', $task->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $task->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control"
                           value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}">
                </div>
            </div>

            @if($tags->isNotEmpty())
            @php $selectedTags = old('tags', $task->tags->pluck('id')->toArray()); @endphp
            <div class="form-group">
                <label class="form-label">Tags</label>
                <div class="checkbox-group">
                    @foreach($tags as $tag)
                        <label class="checkbox-chip">
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                   {{ in_array($tag->id, $selectedTags) ? 'checked' : '' }}>
                            <span style="width: 8px; height: 8px; border-radius: 50%; background: {{ $tag->color }};"></span>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div style="display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid var(--border);">
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
