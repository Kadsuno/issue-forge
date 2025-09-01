<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tickets', 'slug')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->string('slug')->unique()->after('title');
            });

            // Backfill existing rows with unique slugs based on title
            $tickets = DB::table('tickets')->select('id', 'title')->get();
            $existing = [];
            foreach ($tickets as $t) {
                $base = Str::slug($t->title) ?: 'ticket';
                $slug = $base;
                $i = 1;
                while (in_array($slug, $existing) || DB::table('tickets')->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                DB::table('tickets')->where('id', $t->id)->update(['slug' => $slug]);
                $existing[] = $slug;
            }
        }
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
