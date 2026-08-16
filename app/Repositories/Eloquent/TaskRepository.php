<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        return Task::with(['category', 'tags', 'subtasks'])
            ->filter($filters)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getMetrics(): array
    {
        return [
            'total' => Task::count(),
            'completed' => Task::where('status', 'completed')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'todo' => Task::where('status', 'todo')->count(),
            'urgent' => Task::where('priority', 'urgent')->where('status', '!=', 'completed')->count(),
        ];
    }

    public function findById(int $id): Task
    {
        return Task::with(['category', 'tags', 'subtasks'])->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->fresh(['category', 'tags', 'subtasks']);
    }

    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    public function toggleStatus(Task $task): Task
    {
        if ($task->status === 'completed') {
            $task->status = 'todo';
            $task->completed_at = null;
        } else {
            $task->status = 'completed';
            $task->completed_at = Carbon::now();
        }

        $task->save();
        return $task;
    }
}
