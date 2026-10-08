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
        Schema::create('animal_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Dogs", "Cats", "Birds"
            $table->string('icon_class'); // e.g., "fas fa-dog", "fas fa-cat"
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true); // Show in carousel
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animal_types');
    }
};
