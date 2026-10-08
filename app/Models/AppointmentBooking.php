<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentBooking extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'pet_type', 'pet_name',
        'service_id','appointment_type_id', 'preferred_date', 'city_id',
        'message', 'status',
        'otp', 'otp_sent_at', 'otp_verified_at',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id',
        'amount', 'whatsapp_sent',
    ];

    protected $casts = [
        'preferred_date'  => 'date',
        'otp_sent_at'     => 'datetime',
        'otp_verified_at' => 'datetime',
        'whatsapp_sent'   => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function appointmentType()
{
    return $this->belongsTo(AppointmentType::class);
}
}
