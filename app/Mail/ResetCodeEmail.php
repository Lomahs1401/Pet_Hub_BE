<?php

namespace App\Mail;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
        $this->subject('Reset Code Email');
        $this->markdown('emails.reset_code');
    }

    public function build()
    {
        return $this->from('pethub@gmail.com', 'Pet Hub')
                    ->subject('Password Reset Code')
                    ->view('emails.reset-password');
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
    
}