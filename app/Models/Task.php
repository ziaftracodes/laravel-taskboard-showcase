<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'completed_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    protected $appends = [
        'is_overdue',
        'priority_badge_color',
    ];

    // ==========================================
    // Relationships
    // ==========================================

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    // ==========================================
    // Scopes (Filterable query patterns)
    // ==========================================

    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopePriority(Builder $query, ?string $priority): Builder
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    public function scopeCategory(Builder $query, ?int $categoryId): Builder
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->search($filters['search'] ?? null)
            ->status($filters['status'] ?? null)
            ->priority($filters['priority'] ?? null)
            ->category($filters['category_id'] ?? null);
    }

    // ==========================================
    // Accessors & Mutators
    // ==========================================

    public function getIsOverdueAttribute(): bool
    {
        if ($this->status === 'completed' || !$this->due_date) {
            return false;
        }
        return $this->due_date->isPast();
    }

    public function getPriorityBadgeColorAttribute(): string
    {
        return match ($this->priority) {
            'urgent' => '#ea4335', // Red
            'high' => '#f29900',   // Amber
            'medium' => '#1a73e8', // Blue
            'low' => '#34a853',    // Green
            default => '#5f6368',
        };
    }
}
