<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
            $table->unique('slug');
        });

        // Backfill existing users
        $users = DB::table('users')->select('id', 'name')->get();
        foreach ($users as $u) {
            $base = Str::slug((string) $u->name);
            $slug = $base ?: 'user-'.$u->id;
            $suffix = 1;
            while (DB::table('users')->where('slug', $slug)->where('id', '!=', $u->id)->exists()) {
                $suffix++;
                $slug = $base.'-'.$suffix;
            }
            DB::table('users')->where('id', $u->id)->update(['slug' => $slug]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('slug')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
