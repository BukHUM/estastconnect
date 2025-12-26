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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('ai_description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('type', 50); // condo, house, land
            $table->string('location', 255);
            $table->string('district', 100)->nullable();
            $table->string('province', 100);
            $table->enum('status', ['pending', 'published', 'archived'])->default('pending');
            $table->string('source_url', 500)->nullable();
            $table->string('external_id', 100)->nullable();
            $table->unsignedInteger('view_count')->default(0);
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('type');
            $table->index('province');
            $table->index('district');
            $table->index('price');
            $table->index('external_id');
            $table->index('created_at');
            
            // Full-text indexes (MySQL)
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
