<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use App\Models\Tag;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'status', 'priority', 'category_id']);
        $tasks = $this->taskService->getTasks($filters);
        $metrics = $this->taskService->getDashboardMetrics();
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'metrics', 'categories', 'tags', 'filters'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('tasks.create', compact('categories', 'tags'));
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $task->load(['category', 'tags', 'subtasks']);
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories', 'tags'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($task, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);

        return redirect()->route('tasks.index')->with('success', 'Task moved to trash.');
    }

    public function toggleStatus(Task $task)
    {
        $this->taskService->toggleTaskCompletion($task);

        return redirect()->back()->with('success', 'Task status updated.');
    }
}
