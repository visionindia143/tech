<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vaccination_bookings', function (Blueprint $table) {
            $table->string('otp')->nullable()->after('message');
            $table->timestamp('otp_sent_at')->nullable()->after('otp');
            $table->timestamp('otp_verified_at')->nullable()->after('otp_sent_at');
            $table->enum('payment_status', ['not_required', 'pending', 'paid', 'failed'])
                  ->default('not_required')->after('otp_verified_at');
            $table->string('razorpay_order_id')->nullable()->after('payment_status');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->decimal('amount', 10, 2)->nullable()->after('razorpay_payment_id');
            $table->boolean('whatsapp_sent')->default(false)->after('amount');
        });
    }

    public function down(): void
    {
        Schema::table('vaccination_bookings', function (Blueprint $table) {
            $table->dropColumn([
                'otp', 'otp_sent_at', 'otp_verified_at',
                'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
                'amount', 'whatsapp_sent',
            ]);
        });
    }
};
