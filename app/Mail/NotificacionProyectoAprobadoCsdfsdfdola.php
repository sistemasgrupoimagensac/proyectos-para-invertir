<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionProyectoAprobadoInversionista extends Mailable
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
        return $this->view('emails.proyecto-aprobado-cola')
            ->subject('El Proyecto con código ' . $this->co_unico_solicitud . ' a sido aprobado por usted.')
            ->with([
                'co_unico_solicitud'    => $this->co_unico_solicitud,
                'inversionista'         => $this->inversionista,
                'analista'              => $this->analista,
                'co_solicitud_prestamo' => $this->co_solicitud_prestamo,
            ]);
    }
}
