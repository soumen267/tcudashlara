<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $getData,$dashboardId;
    /**
     * Create a new message instance.
     */
    public function __construct($getData,$dashboardId)
    {
        $this->getData = $getData;
        $this->dashboardId = $dashboardId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email_template.email',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        /**
         * Replace the "from" field with your valid sender email address.
         * The "email-template" is the name of the file present inside
         * "resources/views" folder. If you don't have this file, then
         * create it.
         */
        // $order = $this->orddata['order_id'];
        // return $this->subject('Order Confirmation - '. $this->orddata['order_id'])
        //             ->markdown('mail.orderconfirm');
        //             //->attachData($this->pdf, 'invoice-'.$order.'.html');
        return $this->from("support@example.com")->view('email_template.email');
    }
}
