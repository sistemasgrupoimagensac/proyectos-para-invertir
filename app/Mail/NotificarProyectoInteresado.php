<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificarProyectoInteresado extends Mailable
{
    use Queueable, SerializesModels;
    protected $proyecto;
    protected $inversionista;
    protected $celular;
    protected $analista;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($proyecto, $inversionista, $analista, $celular)
    {
        $this->proyecto = $proyecto;
        $this->inversionista = $inversionista;
        $this->analista = $analista;
        $this->celular = $celular;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Un inversionista de su cartera le interesa el proyecto '. $this->proyecto)
            ->view('emails.notificar-proyecto-interesado')
            ->with([
                'proyecto'              => $this->proyecto,
                'inversionista'         => $this->inversionista,
                'analista'              => $this->analista,
                'celular'               => $this->celular,
            ]
        );
    }
}
