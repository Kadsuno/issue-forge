<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

final class Attachment extends Model
{
    /**
     * Maximum file size in kilobytes (10MB)
     */
    public const MAX_FILE_SIZE = 10240;

    /**
     * Allowed MIME types
     */
    public const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/plain',
        'application/zip',
        'application/x-rar-compressed',
        'application/x-rar',
    ];

    /**
     * Allowed file extensions
     */
    public const ALLOWED_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'txt',
        'zip',
        'rar',
    ];

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
        'uploaded_by',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the parent attachable model (ticket, project, or comment).
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user who uploaded this attachment.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the full URL to the file.
     */
    public function getUrlAttribute(): string
    {
        return Storage::disk('attachments')->url($this->file_path);
    }

    /**
     * Get human-readable file size.
     */
    public function getHumanFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;

        return round($bytes / (1024 ** $power), 2).' '.$units[$power];
    }

    /**
     * Get file icon class based on mime type or extension.
     */
    public function getFileIconAttribute(): string
    {
        $extension = pathinfo($this->file_name, PATHINFO_EXTENSION);

        return match ($extension) {
            'jpg', 'jpeg', 'png', 'gif' => 'image',
            'pdf' => 'file-pdf',
            'doc', 'docx' => 'file-word',
            'xls', 'xlsx' => 'file-excel',
            'zip', 'rar' => 'file-archive',
            'txt' => 'file-text',
            default => 'file',
        };
    }

    /**
     * Check if the attachment is an image.
     */
    public function isImage(): bool
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    /**
     * Get thumbnail URL for images.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->isImage()) {
            return null;
        }

        return $this->url;
    }

    /**
     * Delete the file from storage when the model is deleted.
     */
    protected static function booted(): void
    {
        self::deleting(function (self $attachment): void {
            Storage::disk('attachments')->delete($attachment->file_path);
        });
    }
}
