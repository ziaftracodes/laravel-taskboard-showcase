<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAll(array $filters = [], int $perPage = 12): LengthAwarePaginator;
    
    public function getMetrics(): array;

    public function findById(int $id): Task;

    public function create(array $data): Task;

    public function update(Task $task, array $data): Task;

    public function delete(Task $task): bool;

    public function toggleStatus(Task $task): Task;
}
