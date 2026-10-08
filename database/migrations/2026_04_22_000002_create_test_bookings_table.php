<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('address')->nullable();
            $table->foreignId('test_type_id')->constrained('test_types')->onDelete('cascade');
            $table->date('preferred_date');
            $table->string('preferred_time', 20)->nullable();
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->text('medical_history')->nullable();
            $table->text('symptoms')->nullable();
            $table->text('message')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->string('otp')->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();
            $table->enum('payment_status', ['not_required', 'pending', 'paid', 'failed'])->default('not_required');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->boolean('whatsapp_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_bookings');
    }
};