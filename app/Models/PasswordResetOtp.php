<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetOtp extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at', 'used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Generate and store a new OTP for the given email
     */
    public static function generateForEmail(string $email): string
    {
        // Delete any existing unused OTPs for this email
        static::where('email', $email)->where('used', false)->delete();

        // Generate 6-digit OTP
        $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        static::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10), // 10 minutes expiry
            'used' => false,
        ]);

        return $otp;
    }

    /**
     * Verify OTP for the given email
     */
    public static function verifyOtp(string $email, string $otp): bool
    {
        $otp = trim($otp); // clean input

        $record = static::where('email', $email)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$record) return false;

        if (
            trim($record->otp) === str_pad($otp, 6, '0', STR_PAD_LEFT) &&
            $record->expires_at > now()
        ) {
            $record->update(['used' => true]);
            return true;
        }

        return false;
    }

    /**
     * Clean up expired OTPs (can be called by a scheduled job)
     */
    public static function cleanupExpired(): int
    {
        return static::where('expires_at', '<', Carbon::now())->delete();
    }
}
