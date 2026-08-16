<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Task;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Categories (Projects)
        $categories = [
            ['name' => 'Marketing', 'slug' => 'marketing', 'color' => '#ea4335', 'icon' => 'campaign', 'description' => 'Marketing campaigns and outreach tasks.'],
            ['name' => 'Development', 'slug' => 'development', 'color' => '#1a73e8', 'icon' => 'code', 'description' => 'Engineering and development tasks.'],
            ['name' => 'Design', 'slug' => 'design', 'color' => '#f29900', 'icon' => 'palette', 'description' => 'UI/UX design and creative work.'],
            ['name' => 'Operations', 'slug' => 'operations', 'color' => '#34a853', 'icon' => 'settings', 'description' => 'Day-to-day operational tasks.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Tags
        $tags = [
            ['name' => 'Bug', 'slug' => 'bug', 'color' => '#ea4335'],
            ['name' => 'Feature', 'slug' => 'feature', 'color' => '#1a73e8'],
            ['name' => 'Urgent', 'slug' => 'urgent', 'color' => '#f29900'],
            ['name' => 'Research', 'slug' => 'research', 'color' => '#673ab7'],
            ['name' => 'Documentation', 'slug' => 'documentation', 'color' => '#5f6368'],
            ['name' => 'Refactor', 'slug' => 'refactor', 'color' => '#00bcd4'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }

        // Tasks with relationships
        $tasks = [
            [
                'title' => 'Implement user authentication',
                'description' => 'Set up Laravel Breeze for login/register flow with email verification and password reset functionality.',
                'priority' => 'high',
                'status' => 'in_progress',
                'category_id' => 2,
                'due_date' => Carbon::now()->addDays(3),
                'tags' => [2, 3],
                'subtasks' => ['Install Breeze scaffolding', 'Configure email verification', 'Test password reset flow', 'Add rate limiting to auth routes'],
            ],
            [
                'title' => 'Design landing page mockup',
                'description' => 'Create a high-fidelity mockup for the new product landing page with responsive variants for mobile and desktop.',
                'priority' => 'urgent',
                'status' => 'todo',
                'category_id' => 3,
                'due_date' => Carbon::now()->addDays(1),
                'tags' => [2],
                'subtasks' => ['Wireframe layout', 'Hero section design', 'Mobile responsive variant'],
            ],
            [
                'title' => 'Fix pagination bug on reports page',
                'description' => 'The reports page crashes when navigating past page 5. Likely a query optimization issue with the N+1 problem.',
                'priority' => 'high',
                'status' => 'todo',
                'category_id' => 2,
                'due_date' => Carbon::now()->addDays(2),
                'tags' => [1, 3],
                'subtasks' => ['Reproduce the bug', 'Add eager loading', 'Write regression test'],
            ],
            [
                'title' => 'Write API documentation',
                'description' => 'Document all REST API endpoints with request/response examples using OpenAPI spec format.',
                'priority' => 'medium',
                'status' => 'todo',
                'category_id' => 2,
                'due_date' => Carbon::now()->addDays(7),
                'tags' => [5],
                'subtasks' => ['List all endpoints', 'Add request examples', 'Add response schemas'],
            ],
            [
                'title' => 'Set up CI/CD pipeline',
                'description' => 'Configure GitHub Actions for automated testing, linting, and deployment to staging environment.',
                'priority' => 'medium',
                'status' => 'completed',
                'category_id' => 4,
                'due_date' => Carbon::now()->subDays(2),
                'completed_at' => Carbon::now()->subDay(),
                'tags' => [2],
                'subtasks' => [],
            ],
            [
                'title' => 'Launch social media campaign Q3',
                'description' => 'Plan and execute the Q3 social media campaign across Instagram, Twitter, and LinkedIn targeting developer audience.',
                'priority' => 'medium',
                'status' => 'in_progress',
                'category_id' => 1,
                'due_date' => Carbon::now()->addDays(14),
                'tags' => [4],
                'subtasks' => ['Draft content calendar', 'Create visual assets', 'Schedule posts', 'Set up analytics tracking'],
            ],
            [
                'title' => 'Refactor notification service',
                'description' => 'Extract the notification logic from controllers into a dedicated NotificationService class using the Observer pattern.',
                'priority' => 'low',
                'status' => 'todo',
                'category_id' => 2,
                'due_date' => Carbon::now()->addDays(10),
                'tags' => [6],
                'subtasks' => ['Identify all notification triggers', 'Create service class', 'Write unit tests'],
            ],
            [
                'title' => 'Quarterly team performance review',
                'description' => 'Prepare and conduct Q3 performance reviews for all team members. Gather 360-degree feedback and prepare growth plans.',
                'priority' => 'high',
                'status' => 'todo',
                'category_id' => 4,
                'due_date' => Carbon::now()->subDays(1),
                'tags' => [3],
                'subtasks' => ['Send feedback forms', 'Schedule 1-on-1 meetings'],
            ],
        ];

        foreach ($tasks as $taskData) {
            $tagIds = $taskData['tags'] ?? [];
            $subtasks = $taskData['subtasks'] ?? [];
            unset($taskData['tags'], $taskData['subtasks']);

            $task = Task::create($taskData);

            if (!empty($tagIds)) {
                $task->tags()->sync($tagIds);
            }

            foreach ($subtasks as $subtaskTitle) {
                $task->subtasks()->create([
                    'title' => $subtaskTitle,
                    'is_completed' => false,
                ]);
            }
        }
    }
}
