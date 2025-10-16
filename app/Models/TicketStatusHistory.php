<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class TicketStatusHistory extends Model
{
    use HasFactory;

    protected $table = 'ticket_status_history';

    protected $fillable = [
        'ticket_id',
        'from_status',
        'to_status',
        'user_id',
        'comment',
    ];

    /**
     * Get the ticket that owns this history entry
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who made this status change
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

