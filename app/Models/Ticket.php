<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'project_id',
        'parent_ticket_id',
        'user_id',
        'assigned_to',
        'due_date',
        'type',
        'severity',
        'estimate_minutes',
        'labels',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'estimate_minutes' => 'integer',
    ];

    /**
     * Boot model hooks.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $ticket): void {
            // Generate unique slug from title on creation
            if (empty($ticket->slug)) {
                $ticket->slug = static::generateUniqueSlug((string) $ticket->title);
            }
        });
    }

    /**
     * Generate a globally-unique slug for tickets from the given title.
     */
    private static function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title) ?: 'ticket';
        $slug = $base;
        $suffix = 1;
        while (static::where('slug', $slug)->exists()) {
            $suffix++;
            $slug = $base . '-' . $suffix;
        }

        return $slug;
    }

    /**
     * Bind tickets by slug in routes.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Status constants
     */
    const STATUS_OPEN = 'open';

    const STATUS_IN_PROGRESS = 'in_progress';

    const STATUS_RESOLVED = 'resolved';

    const STATUS_CLOSED = 'closed';

    /**
     * Priority constants
     */
    const PRIORITY_LOW = 'low';

    const PRIORITY_MEDIUM = 'medium';

    const PRIORITY_HIGH = 'high';

    const PRIORITY_URGENT = 'urgent';

    /**
     * Type constants
     */
    const TYPE_BUG = 'bug';

    const TYPE_TASK = 'task';

    const TYPE_FEATURE = 'feature';

    const TYPE_IMPROVEMENT = 'improvement';

    const TYPE_CHORE = 'chore';

    /**
     * Severities
     */
    const SEVERITY_TRIVIAL = 'trivial';

    const SEVERITY_MINOR = 'minor';

    const SEVERITY_MAJOR = 'major';

    const SEVERITY_CRITICAL = 'critical';

    const SEVERITY_BLOCKER = 'blocker';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_CLOSED => 'Closed',
        ];
    }

    /**
     * Get all available priorities
     */
    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_URGENT => 'Urgent',
        ];
    }

    /**
     * Get all available types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_BUG => 'Bug',
            self::TYPE_TASK => 'Task',
            self::TYPE_FEATURE => 'Feature',
            self::TYPE_IMPROVEMENT => 'Improvement',
            self::TYPE_CHORE => 'Chore',
        ];
    }

    /**
     * Get all severities
     */
    public static function getSeverities(): array
    {
        return [
            self::SEVERITY_TRIVIAL => 'Trivial',
            self::SEVERITY_MINOR => 'Minor',
            self::SEVERITY_MAJOR => 'Major',
            self::SEVERITY_CRITICAL => 'Critical',
            self::SEVERITY_BLOCKER => 'Blocker',
        ];
    }

    /**
     * Get the project that owns the ticket
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user that created the ticket
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user assigned to the ticket
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Parent ticket relation
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_ticket_id');
    }

    /**
     * Children tickets relation
     */
    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_ticket_id');
    }

    /**
     * Users watching this ticket
     */
    public function watchers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_watchers')->withTimestamps();
    }

    /**
     * Time entries booked against this ticket
     */
    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    /**
     * Get comments for the ticket
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class)->latest();
    }

    /**
     * Check if ticket is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && ! in_array($this->status, [self::STATUS_RESOLVED, self::STATUS_CLOSED]);
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_OPEN => 'bg-slate-500',
            self::STATUS_IN_PROGRESS => 'bg-primary-500',
            self::STATUS_RESOLVED => 'bg-success-500',
            self::STATUS_CLOSED => 'bg-dark-500',
            default => 'bg-slate-500',
        };
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_LOW => 'success',
            self::PRIORITY_MEDIUM => 'warning',
            self::PRIORITY_HIGH => 'accent',
            self::PRIORITY_URGENT => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Computed ticket number (e.g., PREFIX-123 or #123)
     */
    public function getNumberAttribute(): string
    {
        $prefix = optional($this->project)->ticket_prefix;

        return $prefix ? ($prefix . '-' . $this->id) : ('#' . $this->id);
    }
}
