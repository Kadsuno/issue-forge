<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('type')->default('task')->after('priority'); // bug|task|feature|improvement|chore
            $table->string('severity')->nullable()->after('type'); // trivial|minor|major|critical|blocker
            $table->unsignedInteger('estimate_minutes')->nullable()->after('due_date');
            $table->text('labels')->nullable()->after('estimate_minutes'); // comma-separated labels
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['type', 'severity', 'estimate_minutes', 'labels']);
        });
    }
};
