<?php

namespace App\Mail;

use Faker\Provider\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class SolicitudCiudadanoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $buzon;


    public function __construct($buzon)
    {
        $this->buzon = $buzon;
    }

    public function build()
    {   
        $correo = $this->subject('Notificación de Incidentes: '. $this->buzon->correlativo)
            ->view('emails.solicitud_cliente', // Tu vista de correo
            [
                'buzon' => $this->buzon
            ]);

        return $correo;
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