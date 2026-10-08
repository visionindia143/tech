<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adoption_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_adoption_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->text('notes')->nullable();

            // OTP verification
            $table->string('otp')->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->timestamp('otp_verified_at')->nullable();

            // Payment
            $table->enum('payment_status', ['not_required', 'pending', 'paid', 'failed'])->default('not_required');
            $table->string('razorpay_order_id')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();

            // Booking status
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');

            // WhatsApp notifications
            $table->boolean('whatsapp_sent')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('adoption_bookings');
    }
};
