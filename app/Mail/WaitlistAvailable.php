<?php

namespace App\Mail;

use App\Models\Waitlist;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaitlistAvailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Waitlist $waitlist)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A Spot Opened! - GameBook',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.waitlist-available',
        );
    }
}