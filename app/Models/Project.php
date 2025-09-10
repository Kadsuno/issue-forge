<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Boot the model
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($project) {
            if (! $project->slug) {
                $project->slug = Str::slug($project->name);
            }
        });
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
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
