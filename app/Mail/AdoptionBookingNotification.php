<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AdoptionBooking;

class AdoptionBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AdoptionBooking $booking)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Adoption Booking - ' . ($this->booking->pet->name ?? 'Pet'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.adoption-booking-notification',
        );
    }
}
