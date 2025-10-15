<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

final class AttachmentPolicy
{
    /**
     * Determine whether the user can download the attachment.
     */
    public function download(User $user, Attachment $attachment): bool
    {
        // Users can download attachments if they can view the parent resource
        $attachable = $attachment->attachable;

        if ($attachable instanceof \App\Models\Ticket) {
            return $user->can('view', $attachable);
        }

        if ($attachable instanceof \App\Models\Project) {
            return true; // Projects are viewable by all authenticated users
        }

        if ($attachable instanceof \App\Models\TicketComment) {
            return $user->can('view', $attachable->ticket);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the attachment.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        // Users can delete attachments they uploaded or if they're admins
        return $user->id === $attachment->uploaded_by || $user->is_admin;
    }
}
