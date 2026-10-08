<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_bookings', function (Blueprint $table) {
            $table->string('pet_type');
            $table->string('pet_name');
        });
    }

    public function down(): void
    {
        Schema::table('test_bookings', function (Blueprint $table) {
            $table->dropColumn(['pet_type', 'pet_name']);
        });
    }
};