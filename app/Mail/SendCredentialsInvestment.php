<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCredentialsInvestment extends Mailable
{
    use Queueable, SerializesModels;
    private $nombre;
    private $usuario;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nombre, $usuario, $password)
    {
        $this->nombre = $nombre;
        $this->usuario = $usuario;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Datos de acceso a la plataforma de proyectos')->view('emails.accesos-plataforma-proyectos')->with([
            'nombre'   => $this->nombre,
            'usuario'  => $this->usuario,
            'password' => $this->password
        ]);
    }
}
