<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HPersonaRepresentante extends Model
{
    protected $table = 'h_persona_representante';

    protected $primaryKey = 'co_persona';

    public $timestamps = false;

    protected $fillable = [
        'co_persona', 'co_persona_representante', 'co_tipo_relacion', 'nu_partida',
        'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado', 'parentesco', 'is_apoderado', 'firmara'
    ];

    protected $dates = [
        'fe_usuario_modifica'
    ];

}
