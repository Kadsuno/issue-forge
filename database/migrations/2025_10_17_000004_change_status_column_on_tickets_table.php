<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure all existing statuses are valid
        DB::table('tickets')
            ->whereNotIn('status', ['open', 'in_progress', 'resolved', 'closed'])
            ->update(['status' => 'open']);

        // Change the enum to string
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('status', 50)->default('open')->change();
            $table->string('previous_status', 50)->nullable()->after('status');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropColumn('previous_status');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->change();
        });
    }
};
