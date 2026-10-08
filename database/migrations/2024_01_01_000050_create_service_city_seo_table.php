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
        Schema::create('service_city_seo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            
            // SEO Fields
            $table->string('seo_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('custom_content')->nullable();
            $table->json('faq_content')->nullable(); // Store FAQ as JSON
            
            // Status and tracking
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0); // For sorting
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->unique(['service_id', 'city_id']);
            $table->index(['is_active', 'service_id']);
            $table->index(['is_active', 'city_id']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_city_seo');
    }
};
