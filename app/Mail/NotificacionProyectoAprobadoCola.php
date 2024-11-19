<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionProyectoAprobadoCola extends Mailable
{
    use Queueable, SerializesModels;
    
    public $co_unico_solicitud;
    public $inversionista;
    public $analista;
    public $co_solicitud_prestamo;
    public $prioridad;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct( $co_unico_solicitud, $inversionista, $analista, $co_solicitud_prestamo, $prioridad )
    {
        $this->co_unico_solicitud = $co_unico_solicitud;
        $this->inversionista = $inversionista;
        $this->analista = $analista;
        $this->co_solicitud_prestamo = $co_solicitud_prestamo;
        $this->prioridad = $prioridad;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.proyecto-aprobado-cola')
            ->subject('El proyecto con código ' . $this->co_unico_solicitud . ' ha sido aprobado y ahora está en la cola con el número de espera ' . $this->prioridad . '.')
            ->with([
                'co_unico_solicitud'    => $this->co_unico_solicitud,
                'inversionista'         => $this->inversionista,
                'analista'              => $this->analista,
                'co_solicitud_prestamo' => $this->co_solicitud_prestamo,
                'prioridad'             => $this->prioridad,
            ]);
    }
}
