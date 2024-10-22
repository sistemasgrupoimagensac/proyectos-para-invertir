<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ASemillaPrestamo extends Model
{
    protected $table = 'a_semilla_prestamo';

    protected $primaryKey = 'co_semilla_prestamo';

    public $timestamps = false;

    protected $fillable = [
        'co_semilla_prestamo', 'nu_solicitud', 'in_estado', 'co_usuario_modifica', 'fe_usuario_modifica'
    ];
}
