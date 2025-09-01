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
        Schema::table('projects', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('is_active');
            $table->date('due_date')->nullable()->after('start_date');
            $table->foreignId('default_assignee_id')->nullable()->after('user_id')
                ->constrained('users')->nullOnDelete();
            $table->string('visibility')->default('team')->after('due_date'); // private|team|public
            $table->string('ticket_prefix', 10)->nullable()->after('visibility');
            $table->string('color', 7)->nullable()->after('ticket_prefix'); // HEX like #1e40af
            $table->string('priority')->default('medium')->after('color'); // low|medium|high
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['default_assignee_id']);
            $table->dropColumn([
                'start_date',
                'due_date',
                'default_assignee_id',
                'visibility',
                'ticket_prefix',
                'color',
                'priority',
            ]);
        });
    }
};
