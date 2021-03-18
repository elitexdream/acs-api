<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestService extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.request-service')
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Request Service')
                    ->with([
                        'username' => $this->data->user['username'],
                        'company_name' => $this->data->company_name,
                        'machine_type' => $this->data->overview['machineName'],
                        'firmware_version' => $this->data->overview['version'],
                        'serial_number' => $this->data->overview['serial']
                    ]);
    }
}
