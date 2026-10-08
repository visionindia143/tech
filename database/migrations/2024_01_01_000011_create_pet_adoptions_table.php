<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pet_adoptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('breed')->nullable();
            $table->string('age')->nullable();          // e.g. "2 years", "6 months"
            $table->enum('gender', ['male', 'female', 'unknown'])->default('unknown');
            $table->string('size')->nullable();          // small, medium, large
            $table->string('color')->nullable();
            $table->string('pet_type')->default('dog'); // dog, cat, rabbit, etc.
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->json('images')->nullable();          // array of image filenames
            $table->decimal('adoption_fee', 10, 2)->nullable();
            $table->boolean('is_vaccinated')->default(false);
            $table->boolean('is_neutered')->default(false);
            $table->enum('status', ['available', 'reserved', 'adopted'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pet_adoptions');
    }
};
