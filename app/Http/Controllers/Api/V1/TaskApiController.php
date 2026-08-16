<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Category;
use App\Models\Tag;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskApiController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * GET /api/v1/tasks
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status', 'priority', 'category_id']);
        $tasks = $this->taskService->getTasks($filters);

        return response()->json([
            'data' => TaskResource::collection($tasks),
            'meta' => [
                'current_page' => $tasks->currentPage(),
                'last_page' => $tasks->lastPage(),
                'per_page' => $tasks->perPage(),
                'total' => $tasks->total(),
            ],
            'metrics' => $this->taskService->getDashboardMetrics(),
        ]);
    }

    /**
     * GET /api/v1/tasks/{task}
     */
    public function show(Task $task): TaskResource
    {
        $task->load(['category', 'tags', 'subtasks']);
        return new TaskResource($task);
    }

    /**
     * POST /api/v1/tasks
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'message' => 'Task created successfully.',
            'data' => new TaskResource($task),
        ], 201);
    }

    /**
     * PUT /api/v1/tasks/{task}
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task = $this->taskService->updateTask($task, $request->validated());

        return response()->json([
            'message' => 'Task updated successfully.',
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * DELETE /api/v1/tasks/{task}
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->taskService->deleteTask($task);

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }

    /**
     * PATCH /api/v1/tasks/{task}/toggle
     */
    public function toggle(Task $task): JsonResponse
    {
        $task = $this->taskService->toggleTaskCompletion($task);

        return response()->json([
            'message' => 'Task status toggled.',
            'data' => new TaskResource($task),
        ]);
    }

    /**
     * GET /api/v1/categories
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'data' => CategoryResource::collection(Category::withCount('tasks')->orderBy('name')->get()),
        ]);
    }

    /**
     * GET /api/v1/tags
     */
    public function tags(): JsonResponse
    {
        return response()->json([
            'data' => TagResource::collection(Tag::withCount('tasks')->orderBy('name')->get()),
        ]);
    }
}
