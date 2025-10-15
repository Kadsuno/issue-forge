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
     * Get route key name for route model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
