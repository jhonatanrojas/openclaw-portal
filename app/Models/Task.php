<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'type',
        'category',
        'estimated_hours',
        'actual_hours',
        'created_by',
        'assigned_to',
        'due_date',
        'started_at',
        'completed_at',
        'tags',
        'dependencies',
        'acceptance_criteria',
        'complexity_score',
        'urgency_score',
        'importance_score',
    ];

    protected $casts = [
        'tags' => 'array',
        'dependencies' => 'array',
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'complexity_score' => 'decimal:2',
        'urgency_score' => 'decimal:2',
        'importance_score' => 'decimal:2',
        'overall_priority' => 'decimal:2',
    ];

    /**
     * Get the user who created the task.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user assigned to the task.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all assignments for this task.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    /**
     * Scope for pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for in-progress tasks.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for completed tasks.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for high priority tasks.
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high')->orWhere('priority', 'critical');
    }

    /**
     * Scope for overdue tasks.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())->whereNotIn('status', ['completed', 'cancelled']);
    }

    /**
     * Calculate overall priority score.
     */
    public function calculateOverallPriority(): float
    {
        return ($this->complexity_score + $this->urgency_score + $this->importance_score) / 3;
    }

    /**
     * Get tasks that depend on this task.
     */
    public function dependentTasks()
    {
        return self::whereJsonContains('dependencies', $this->id);
    }

    /**
     * Check if task is blocked by dependencies.
     */
    public function isBlocked(): bool
    {
        if (empty($this->dependencies)) {
            return false;
        }

        $incompleteDependencies = self::whereIn('id', $this->dependencies)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->exists();

        return $incompleteDependencies;
    }

    /**
     * Get progress percentage.
     */
    public function getProgressAttribute(): int
    {
        if ($this->status === 'completed') {
            return 100;
        }

        if ($this->status === 'pending') {
            return 0;
        }

        // For in_progress tasks, calculate based on hours
        if ($this->estimated_hours && $this->actual_hours) {
            return min(99, (int) (($this->actual_hours / $this->estimated_hours) * 100));
        }

        return 50; // Default for in_progress without hour tracking
    }
}
