<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_cannot_be_created_without_a_title(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('projects.tasks.store', $project), [
            'title' => '',
            'status' => 'Pending',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_user_can_create_task_under_owned_project(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('projects.tasks.store', $project), [
            'title' => 'Build Authentication',
            'description' => 'Setup Breeze Blade auth',
            'due_date' => '2026-08-01',
            'status' => 'In Progress',
        ]);

        $this->assertDatabaseHas('tasks', [
            'project_id' => $project->id,
            'title' => 'Build Authentication',
            'status' => 'In Progress',
        ]);

        $response->assertRedirect(route('projects.show', $project));
    }

    public function test_user_can_filter_tasks_by_status(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        $pendingTask = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Pending Task',
            'status' => 'Pending',
        ]);

        $completedTask = Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Completed Task',
            'status' => 'Completed',
        ]);

        $response = $this->actingAs($user)->get(route('projects.show', [$project, 'status' => 'Completed']));

        $response->assertStatus(200);
        $response->assertSee('Completed Task');
        $response->assertDontSee('Pending Task');
    }

    public function test_user_can_search_tasks_by_keyword(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Deploy to Staging Server',
            'status' => 'Pending',
        ]);

        Task::factory()->create([
            'project_id' => $project->id,
            'title' => 'Fix CSS Layout Margins',
            'status' => 'Pending',
        ]);

        $response = $this->actingAs($user)->get(route('projects.show', [$project, 'search' => 'Staging']));

        $response->assertStatus(200);
        $response->assertSee('Deploy to Staging Server');
        $response->assertDontSee('Fix CSS Layout Margins');
    }

    public function test_tasks_are_paginated(): void
    {
        $user = User::factory()->create();
        $project = Project::factory()->create(['user_id' => $user->id]);

        Task::factory()->count(8)->create(['project_id' => $project->id]);

        $response = $this->actingAs($user)->get(route('projects.show', $project));

        $response->assertStatus(200);
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 5 && $tasks->total() === 8;
        });
    }
}
