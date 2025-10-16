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
        Schema::create('workflow_states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->string('label', 100);
            $table->text('description')->nullable();
            $table->string('color', 20)->default('gray');
            $table->string('icon', 50)->nullable();
            $table->boolean('is_predefined')->default(false);
            $table->boolean('is_closed')->default(false);
            $table->integer('order')->default(0);
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index('slug');
            $table->index('project_id');
            $table->index('is_predefined');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workflow_states');
    }
};
