<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_a_project(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'New Feature Project',
            'description' => 'A test project description',
            'status' => 'Active',
        ]);

        $this->assertDatabaseHas('projects', [
            'user_id' => $user->id,
            'name' => 'New Feature Project',
            'status' => 'Active',
        ]);

        $project = Project::where('name', 'New Feature Project')->first();
        $response->assertRedirect(route('projects.show', $project));
    }

    public function test_user_cannot_access_another_users_project(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $project = Project::factory()->create([
            'user_id' => $owner->id,
            'name' => 'Private Owner Project',
        ]);

        $response = $this->actingAs($otherUser)->get(route('projects.show', $project));

        $response->assertStatus(403);
    }

    public function test_project_can_be_deleted_successfully(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->delete(route('projects.destroy', $project));

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);

        $response->assertRedirect(route('projects.index'));
    }
}
