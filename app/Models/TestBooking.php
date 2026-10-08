<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestBooking extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'pet_type', 'pet_name', 'age', 'gender', 'address',
        'test_type_id', 'preferred_date', 'preferred_time', 'city_id',
        'medical_history', 'symptoms', 'message', 'status',
        'otp', 'otp_sent_at', 'otp_verified_at',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'amount', 'whatsapp_sent',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'age' => 'integer',
        'otp_sent_at' => 'datetime',
        'otp_verified_at' => 'datetime',
        'whatsapp_sent' => 'boolean',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
