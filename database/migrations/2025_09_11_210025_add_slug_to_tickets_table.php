<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
            $table->unique('slug');
        });

        // Backfill existing rows with generated slugs based on title
        $tickets = DB::table('tickets')->select('id', 'title')->get();
        foreach ($tickets as $t) {
            $base = Str::slug((string) $t->title);
            $slug = $base ?: 'ticket-' . $t->id;
            $suffix = 1;
            while (DB::table('tickets')->where('slug', $slug)->where('id', '!=', $t->id)->exists()) {
                $suffix++;
                $slug = $base . '-' . $suffix;
            }
            DB::table('tickets')->where('id', $t->id)->update(['slug' => $slug]);
        }

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
