<?php

namespace App\Mail;

use App\Models\ImagePost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $images;
    public $cantidad;

    /**
     * Create a new message instance.
     */
    public function __construct( $images,$cantidad)
    {
        $this->images = $images;
        $this->cantidad = $cantidad;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "$this->cantidad imagenes",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.test',
            with: [
                'images'=> $this->images,
            ]
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
        //$attachments = [];

        // Iterar sobre las imágenes y agregarlas a los adjuntos
       /* foreach ($this->images as $image) {
            $attachments[] = Attachment::fromStorage('public/imagesPost/'.$image->imagen)
                                       ->as($image->id.'.'.$image->file_ext);
        }
        
        
       /* return [
            Attachment::fromStorage('public/imagesPost/'.$this->image->imagen)->as($this->image->id .'.'. $this->image->file_ext),
        ];*/
        //return $attachments;
    }
}
