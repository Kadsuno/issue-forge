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
        Schema::create('workflow_state_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workflow_state_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('role_id');
            $table->boolean('can_set_to')->default(true);
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unique(['workflow_state_id', 'role_id'], 'workflow_state_role_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_state_permissions');
    }
};
