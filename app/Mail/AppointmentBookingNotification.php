<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\AppointmentBooking;

class AppointmentBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AppointmentBooking $booking) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Appointment Booking - ' . $this->booking->pet_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-booking-notification',
        );
    }
}
