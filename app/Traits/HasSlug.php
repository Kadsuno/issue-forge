<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Provides automatic slug generation for models.
 *
 * Usage:
 * 1. Add the trait to your model: use HasSlug;
 * 2. Optionally, override getSlugSourceColumn() to specify the source field
 * 3. Slugs are automatically generated on model creation if not already set
 *
 * Example:
 * ```php
 * class Project extends Model
 * {
 *     use HasSlug;
 *
 *     protected function getSlugSourceColumn(): string
 *     {
 *         return 'name'; // Default is 'name'
 *     }
 * }
 * ```
 */
trait HasSlug
{
    /**
     * Boot the HasSlug trait for the model.
     */
    protected static function bootHasSlug(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->slug)) {
                $sourceColumn = $model->getSlugSourceColumn();
                $model->slug = static::generateUniqueSlug((string) $model->{$sourceColumn});
            }
        });
    }

    /**
     * Get the column name to use as the slug source.
     * Override this method in your model to use a different column.
     */
    protected function getSlugSourceColumn(): string
    {
        return $this->slugSource ?? 'name';
    }

    /**
     * Generate a globally-unique slug for the model from the given source text.
     * Uses an efficient algorithm to avoid N+1 queries for popular slugs.
     *
     * @param  string  $source  The text to convert into a slug
     * @return string The unique slug
     */
    protected static function generateUniqueSlug(string $source): string
    {
        $base = Str::slug($source) ?: static::getDefaultSlugBase();
        $slug = $base;

        // Check if slug already exists
        if (! static::where('slug', $slug)->exists()) {
            return $slug;
        }

        // More efficient: find the highest suffix in a single query
        $pattern = $base.'-';
        $maxSuffix = static::where('slug', 'like', $pattern.'%')
            ->orderByRaw('CAST(SUBSTRING(slug, '.(strlen($pattern) + 1).') AS UNSIGNED) DESC')
            ->value('slug');

        if ($maxSuffix) {
            // Extract the numeric suffix and increment
            $suffix = (int) substr($maxSuffix, strlen($pattern));
            $slug = $base.'-'.($suffix + 1);
        } else {
            // No existing suffixed slugs, start with -2
            $slug = $base.'-2';
        }

        return $slug;
    }

    /**
     * Get the default base slug when the source text is empty.
     * Override this method in your model to use a different default.
     */
    protected static function getDefaultSlugBase(): string
    {
        return 'item';
    }
}
