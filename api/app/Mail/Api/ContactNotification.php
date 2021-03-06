<?php

namespace App\Mail\Api;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $name = '';
    public string $email = '';
    public $theme = '';
    public string $text = '';

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->text = $data['text'];
        $this->email = $data['email'];
        $this->theme = $data['theme'];
        $this->name = $data['name'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('Contact Notification', [
            'items' => [
                'Text' => $this->text,
                'Email' => $this->email,
                'Theme' => $this->theme,
                'Name' => $this->name,]

        ]);
    }
}
