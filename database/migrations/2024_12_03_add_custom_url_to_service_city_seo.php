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
        Schema::table('service_city_seo', function (Blueprint $table) {
            $table->string('custom_url')->nullable()->after('priority')->comment('Custom URL for this service-city combination');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_city_seo', function (Blueprint $table) {
            $table->dropColumn('custom_url');
        });
    }
};
