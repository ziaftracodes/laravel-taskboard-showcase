<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function getTasks(array $filters = [], int $perPage = 12)
    {
        return $this->taskRepository->getAll($filters, $perPage);
    }

    public function getDashboardMetrics(): array
    {
        return $this->taskRepository->getMetrics();
    }

    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            $taskData = [
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'] ?? 'medium',
                'status' => $data['status'] ?? 'todo',
                'due_date' => $data['due_date'] ?? null,
                'completed_at' => ($data['status'] ?? '') === 'completed' ? Carbon::now() : null,
            ];

            $task = $this->taskRepository->create($taskData);

            if (!empty($data['tags'])) {
                $task->tags()->sync($data['tags']);
            }

            if (!empty($data['subtasks']) && is_array($data['subtasks'])) {
                foreach ($data['subtasks'] as $subtaskTitle) {
                    if (!empty(trim($subtaskTitle))) {
                        $task->subtasks()->create([
                            'title' => trim($subtaskTitle),
                            'is_completed' => false,
                        ]);
                    }
                }
            }

            return $task->load(['category', 'tags', 'subtasks']);
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        return DB::transaction(function () use ($task, $data) {
            $taskData = [
                'category_id' => $data['category_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'priority' => $data['priority'] ?? 'medium',
                'status' => $data['status'] ?? $task->status,
                'due_date' => $data['due_date'] ?? null,
            ];

            if ($taskData['status'] === 'completed' && !$task->completed_at) {
                $taskData['completed_at'] = Carbon::now();
            } elseif ($taskData['status'] !== 'completed') {
                $taskData['completed_at'] = null;
            }

            $updatedTask = $this->taskRepository->update($task, $taskData);

            if (isset($data['tags'])) {
                $updatedTask->tags()->sync($data['tags']);
            }

            return $updatedTask->load(['category', 'tags', 'subtasks']);
        });
    }

    public function deleteTask(Task $task): bool
    {
        return $this->taskRepository->delete($task);
    }

    public function toggleTaskCompletion(Task $task): Task
    {
        return $this->taskRepository->toggleStatus($task);
    }
}
