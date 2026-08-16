<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'is_overdue' => $this->is_overdue,
            'priority_badge_color' => $this->priority_badge_color,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'tags' => TagResource::collection($this->whenLoaded('tags')),
            'subtasks' => $this->whenLoaded('subtasks', function () {
                return $this->subtasks->map(fn ($s) => [
                    'id' => $s->id,
                    'title' => $s->title,
                    'is_completed' => $s->is_completed,
                ]);
            }),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
