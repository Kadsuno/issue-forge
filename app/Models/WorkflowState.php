<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

final class WorkflowState extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'label',
        'description',
        'color',
        'icon',
        'is_predefined',
        'is_closed',
        'order',
        'project_id',
    ];

    protected $casts = [
        'is_predefined' => 'boolean',
        'is_closed' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $state): void {
            if (empty($state->slug)) {
                $state->slug = Str::slug((string) $state->name);
            }
        });
    }

    /**
     * Get the project that owns this workflow state
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the roles that have permission to use this state
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            \Spatie\Permission\Models\Role::class,
            'workflow_state_permissions',
            'workflow_state_id',
            'role_id'
        )->withPivot('can_set_to')->withTimestamps();
    }

    /**
     * Scope to get only global workflow states
     */
    public function scopeGlobal(Builder $query): Builder
    {
        return $query->whereNull('project_id');
    }

    /**
     * Scope to get workflow states for a specific project
     */
    public function scopeForProject(Builder $query, int $projectId): Builder
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope to get only predefined states
     */
    public function scopePredefined(Builder $query): Builder
    {
        return $query->where('is_predefined', true);
    }

    /**
     * Scope to get only custom states
     */
    public function scopeCustom(Builder $query): Builder
    {
        return $query->where('is_predefined', false);
    }

    /**
     * Check if a user can set tickets to this state
     */
    public function canBeSetBy(User $user): bool
    {
        // Predefined states without specific permissions can be used by anyone
        if ($this->is_predefined && $this->roles()->count() === 0) {
            return true;
        }

        // Check if user has any role that has permission for this state
        $userRoleIds = $user->roles->pluck('id')->toArray();
        $allowedRoleIds = $this->roles()
            ->wherePivot('can_set_to', true)
            ->pluck('roles.id')
            ->toArray();

        return ! empty(array_intersect($userRoleIds, $allowedRoleIds));
    }

    /**
     * Get color class for UI badge
     */
    public function getColorClassAttribute(): string
    {
        return match ($this->color) {
            'blue' => 'bg-primary-500 text-white',
            'green' => 'bg-success-500 text-white',
            'yellow', 'orange' => 'bg-warning-500 text-dark-900',
            'red' => 'bg-danger-500 text-white',
            'purple' => 'bg-accent-500 text-white',
            'gray' => 'bg-slate-500 text-white',
            default => 'bg-slate-500 text-white',
        };
    }
}

