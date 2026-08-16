<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic category and tag for testing
        Category::create([
            'name' => 'Design',
            'slug' => 'design',
            'color' => '#f29900'
        ]);

        Tag::create([
            'name' => 'Urgent',
            'slug' => 'urgent',
            'color' => '#ea4335'
        ]);
    }

    /** @test */
    public function it_can_display_the_tasks_list_and_metrics()
    {
        Task::create([
            'title' => 'Test Task 1',
            'status' => 'todo',
            'priority' => 'medium'
        ]);

        Task::create([
            'title' => 'Test Task 2',
            'status' => 'completed',
            'priority' => 'high'
        ]);

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Task 1');
        $response->assertSee('Test Task 2');
        $response->assertSee('Total Tasks');
    }

    /** @test */
    public function it_validates_required_fields_when_creating_a_task()
    {
        $response = $this->post(route('tasks.store'), [
            'title' => '',
            'priority' => 'invalid_priority',
            'status' => 'invalid_status'
        ]);

        $response->assertSessionHasErrors(['title', 'priority', 'status']);
    }

    /** @test */
    public function it_can_create_a_task_with_category_tags_and_subtasks()
    {
        $category = Category::first();
        $tag = Tag::first();

        $response = $this->post(route('tasks.store'), [
            'title' => 'Build integration tests',
            'description' => 'Write clean feature tests.',
            'category_id' => $category->id,
            'priority' => 'high',
            'status' => 'todo',
            'tags' => [$tag->id],
            'subtasks' => ['Subtask 1', 'Subtask 2']
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Build integration tests',
            'category_id' => $category->id,
            'priority' => 'high',
            'status' => 'todo'
        ]);

        $task = Task::first();
        $this->assertCount(1, $task->tags);
        $this->assertCount(2, $task->subtasks);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::create([
            'title' => 'Old Title',
            'priority' => 'medium',
            'status' => 'todo'
        ]);

        $response = $this->put(route('tasks.update', $task->id), [
            'title' => 'New Title',
            'priority' => 'high',
            'status' => 'in_progress'
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'New Title',
            'priority' => 'high',
            'status' => 'in_progress'
        ]);
    }

    /** @test */
    public function it_can_toggle_a_task_completion_status()
    {
        $task = Task::create([
            'title' => 'Toggle Task',
            'status' => 'todo'
        ]);

        $response = $this->patch(route('tasks.toggle', $task->id));

        $response->assertRedirect();
        $this->assertEquals('completed', $task->fresh()->status);
        $this->assertNotNull($task->fresh()->completed_at);
    }

    /** @test */
    public function it_can_delete_a_task_via_soft_deletes()
    {
        $task = Task::create([
            'title' => 'Delete Task'
        ]);

        $response = $this->delete(route('tasks.destroy', $task->id));

        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', [
            'id' => $task->id
        ]);
    }

    // ==========================================
    // API Feature Tests
    // ==========================================

    /** @test */
    public function api_can_fetch_paginated_tasks_and_metrics()
    {
        Task::create([
            'title' => 'API Task 1',
            'status' => 'todo'
        ]);

        $response = $this->getJson(route('api.tasks.index'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            'metrics' => ['total', 'completed', 'in_progress', 'todo', 'urgent']
        ]);
        $response->assertJsonFragment(['title' => 'API Task 1']);
    }

    /** @test */
    public function api_can_create_a_task()
    {
        $response = $this->postJson(route('api.tasks.store'), [
            'title' => 'API New Task',
            'priority' => 'medium',
            'status' => 'todo'
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('data.title', 'API New Task');
        $this->assertDatabaseHas('tasks', ['title' => 'API New Task']);
    }
}
