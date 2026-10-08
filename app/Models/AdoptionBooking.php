<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdoptionBooking extends Model
{
    protected $fillable = [
        'pet_adoption_id', 'name', 'phone', 'email', 'address', 'city', 'notes',
        'otp', 'otp_sent_at', 'otp_verified_at',
        'payment_status', 'razorpay_order_id', 'razorpay_payment_id', 'amount',
        'status', 'whatsapp_sent',
    ];

    protected $casts = [
        'otp_sent_at'      => 'datetime',
        'otp_verified_at'  => 'datetime',
        'whatsapp_sent'    => 'boolean',
        'amount'           => 'decimal:2',
    ];

    public function pet()
    {
        return $this->belongsTo(PetAdoption::class, 'pet_adoption_id');
    }

    public function isOtpVerified(): bool
    {
        return !is_null($this->otp_verified_at);
    }

    public function isOtpExpired(): bool
    {
        return $this->otp_sent_at && $this->otp_sent_at->diffInMinutes(now()) > 10;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed'  => '<span class="badge badge-success">Confirmed</span>',
            'completed'  => '<span class="badge badge-info">Completed</span>',
            'cancelled'  => '<span class="badge badge-danger">Cancelled</span>',
            default      => '<span class="badge badge-warning">Pending</span>',
        };
    }

    public function getPaymentBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'         => '<span class="badge badge-success">Paid</span>',
            'pending'      => '<span class="badge badge-warning">Pending</span>',
            'failed'       => '<span class="badge badge-danger">Failed</span>',
            default        => '<span class="badge badge-secondary">N/A</span>',
        };
    }
}
