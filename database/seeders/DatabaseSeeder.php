<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $demoUser = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => 'password',
        ]);

        $project1 = Project::create([
            'user_id' => $demoUser->id,
            'name' => 'Website Redesign',
            'description' => 'Overhaul the corporate website landing page with modern responsive design.',
            'status' => 'Active',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Design Figma Wireframes',
            'description' => 'Draft low-fidelity wireframes for home, features, and contact pages.',
            'due_date' => now()->addDays(3)->setTime(17, 0),
            'status' => 'Completed',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Setup Blade Component Templates',
            'description' => 'Build reusable Blade header, sidebar, and alert components.',
            'due_date' => now()->addDays(5)->setTime(14, 30),
            'status' => 'In Progress',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Configure Production Server Deployment',
            'description' => 'Setup Nginx, SSL certificate, and database automated backup script.',
            'due_date' => now()->addDays(10)->setTime(9, 0),
            'status' => 'Pending',
        ]);

        $project2 = Project::create([
            'user_id' => $demoUser->id,
            'name' => 'Mobile App API Integration',
            'description' => 'Build RESTful API endpoints for the mobile client authentication and task sync.',
            'status' => 'Active',
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Implement OAuth2 Sanctum Tokens',
            'description' => 'Secure mobile API endpoints using Laravel Sanctum bearer tokens.',
            'due_date' => now()->addDays(2)->setTime(18, 0),
            'status' => 'Completed',
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Write Endpoint Documentation',
            'description' => 'Document API endpoints using Postman collection format.',
            'due_date' => now()->addDays(7)->setTime(12, 0),
            'status' => 'Pending',
        ]);

        $project3 = Project::create([
            'user_id' => $demoUser->id,
            'name' => 'Legacy System Migration',
            'description' => 'Completed migration of customer historical records to modern database schema.',
            'status' => 'Completed',
        ]);

        Task::create([
            'project_id' => $project3->id,
            'title' => 'Validate Data Integrity',
            'description' => 'Run SQL consistency checks against legacy MySQL database.',
            'due_date' => now()->subDays(5)->setTime(10, 0),
            'status' => 'Completed',
        ]);

        // Second user for access boundary testing
        $otherUser = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        Project::factory()->has(Task::factory()->count(3))->create([
            'user_id' => $otherUser->id,
            'name' => 'John Private Project',
        ]);
    }
}
