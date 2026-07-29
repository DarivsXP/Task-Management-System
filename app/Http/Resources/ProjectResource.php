<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $totalTasks     = $this->tasks_count ?? $this->tasks->count();
        $completedTasks = $this->completed_tasks_count ?? $this->tasks->where('status', 'Completed')->count();
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'status'               => $this->status,
            'progress_percent'     => $progressPercent,
            'tasks_count'          => $totalTasks,
            'completed_tasks_count'=> $completedTasks,
            'overdue_tasks_count'  => $this->overdue_tasks_count ?? null,
            'tasks'                => TaskResource::collection($this->whenLoaded('tasks')),
            'created_at'           => $this->created_at->toISOString(),
            'updated_at'           => $this->updated_at->toISOString(),
        ];
    }
}
