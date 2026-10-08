<?php

namespace App\Mail;

use App\Models\TestBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TestBooking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Diagnostic Test Booking - ' . $this->booking->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test-booking-notification',
        );
    }
}