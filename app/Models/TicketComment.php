<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketComment extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'body',
    ];

    /**
     * The ticket this comment belongs to.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The author of the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get attachments for the comment
     */
    public function attachments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
