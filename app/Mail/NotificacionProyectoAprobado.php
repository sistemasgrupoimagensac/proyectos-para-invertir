<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionProyectoAprobado extends Mailable
{
    use Queueable, SerializesModels;
    
    public $co_unico_solicitud;
    public $inversionista;
    public $analista;
    public $co_solicitud_prestamo;

    public function __construct( $co_unico_solicitud, $inversionista, $analista, $co_solicitud_prestamo )
    {
        $this->co_unico_solicitud = $co_unico_solicitud;
        $this->inversionista = $inversionista;
        $this->analista = $analista;
        $this->co_solicitud_prestamo = $co_solicitud_prestamo;
    }

    public function build()
    {
        return $this->subject('Proyecto aprobado - Proyectos para Invertir')
            ->view('emails.proyecto-aprobado')
            ->with([
                'co_unico_solicitud'    => $this->co_unico_solicitud,
                'inversionista'         => $this->inversionista,
                'analista'              => $this->analista,
                'co_solicitud_prestamo' => $this->co_solicitud_prestamo,
            ]
        );
    }
}
