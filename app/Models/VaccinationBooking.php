<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VaccinationBooking extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'pet_type', 'pet_name',
        'vaccination_type_id', 'preferred_date', 'city_id',
        'message', 'status',
        // OTP
        'otp', 'otp_sent_at', 'otp_verified_at',
        // Payment
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'amount', 'whatsapp_sent',
    ];

    protected $casts = [
        'preferred_date'   => 'date',
        'otp_sent_at'      => 'datetime',
        'otp_verified_at'  => 'datetime',
        'whatsapp_sent'    => 'boolean',
    ];

    public function vaccinationType()
    {
        return $this->belongsTo(VaccinationType::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
