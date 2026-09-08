<?php

namespace App\Mail;

use Faker\Provider\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class SolicitudRegistradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $libroreclamacion;
    public $pdfPath;
    public $pathImagen;

    public function __construct($libroreclamacion,$pdfPath,$pathImagen)
    {
        $this->libroreclamacion = $libroreclamacion;
        $this->pdfPath = $pdfPath;
        $this->pathImagen = $pathImagen;
    }

    public function build()
    {   
        $correo = $this->subject('Reclamo Generado: '. $this->libroreclamacion->correlativo)
            ->view('emails.solicitud_registrada') // Tu vista de correo
            ->attach($this->pdfPath, [
                'as' => 'RECLAMO-'.$this->libroreclamacion->correlativo.'.pdf',
                'mime' => 'application/pdf',
            ]);

        if ($this->pathImagen != null) {

            $mime = mime_content_type($this->pathImagen);
            $extension = pathinfo($this->pathImagen, PATHINFO_EXTENSION);

            // Define un nombre de archivo más descriptivo según el tipo
            $nombreAdjunto = strtoupper($extension) == 'PDF'
                ? 'ADJUNTO-' . $this->libroreclamacion->correlativo . '.pdf'
                : 'EVIDENCIA-' . $this->libroreclamacion->correlativo . '.' . $extension;

            $correo->attach($this->pathImagen, [
                'as' => $nombreAdjunto,
                'mime' => $mime,
            ]);
        }
        
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





     /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'HNDAC - Libro de Reclamación',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.solicitud_registrada',
    //     );
    // }