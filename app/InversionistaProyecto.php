<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InversionistaProyecto extends Model
{
    protected $table = 'inversionista_proyectos';

    protected $fillable=[
        'prestamo_id',
        'persona_id',
        'prioridad',
        'estado',
        'user_modifica',
    ];

    public function prestamo()
    {
        return $this->belongsTo(PPrestamo::class, 'prestamo_id', 'co_prestamo');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'co_persona');
    }

}
