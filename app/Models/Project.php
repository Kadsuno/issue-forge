<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'is_active',
        'user_id',
        'start_date',
        'due_date',
        'default_assignee_id',
        'visibility',
        'ticket_prefix',
        'color',
        'priority',
        'workflow_type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get the default base slug when the source text is empty.
     */
    protected static function getDefaultSlugBase(): string
    {
        return 'project';
    }

    /**
     * Get the user that owns the project
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Default assignee relation
     */
    public function defaultAssignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'default_assignee_id');
    }

    /**
     * Get the tickets for the project
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get workflow states for the project
     */
    public function workflowStates(): HasMany
    {
        return $this->hasMany(WorkflowState::class);
    }

    /**
     * Get available workflow states for this project
     */
    public function getAvailableStates(): \Illuminate\Database\Eloquent\Collection
    {
        if ($this->workflow_type === 'custom') {
            return WorkflowState::where('project_id', $this->id)
                ->orderBy('order')
                ->get();
        }

        return WorkflowState::whereNull('project_id')
            ->orderBy('order')
            ->get();
    }

    /**
     * Get attachments for the project
     */
    public function attachments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
