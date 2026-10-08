<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->boolean('show_in_slider')->default(false)->after('views');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->boolean('show_on_home')->default(true)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('show_in_slider');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('show_on_home');
        });
    }
};