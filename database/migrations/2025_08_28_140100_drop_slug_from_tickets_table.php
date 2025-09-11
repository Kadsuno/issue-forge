<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tickets', 'slug')) {
            // Drop unique index first if present (SQLite requires index removal before column drop)
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    $table->dropUnique(['slug']);
                });
            } catch (\Throwable $e) {
                // ignore if index doesn't exist
            }

            Schema::table('tickets', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('tickets', 'slug')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('slug')->nullable();
            });
            try {
                Schema::table('tickets', function (Blueprint $table) {
                    $table->unique('slug');
                });
            } catch (\Throwable $e) {
                // ignore if cannot create unique index in rollback context
            }
        }
    }
};
