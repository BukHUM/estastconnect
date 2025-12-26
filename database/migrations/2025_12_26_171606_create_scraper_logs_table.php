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
        Schema::create('scraper_logs', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->enum('status', ['success', 'failed', 'partial'])->default('success');
            $table->text('error_message')->nullable();
            $table->integer('properties_found')->default(0);
            $table->integer('properties_saved')->default(0);
            $table->integer('execution_time')->nullable(); // in seconds
            $table->timestamp('last_scraped_at')->nullable();
            $table->timestamp('next_scrape_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('url');
            $table->index('status');
            $table->index('last_scraped_at');
            $table->index('next_scrape_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scraper_logs');
    }
};
