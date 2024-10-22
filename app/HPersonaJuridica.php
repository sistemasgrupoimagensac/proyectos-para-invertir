<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HPersonaJuridica extends Model
{
    protected $table = 'h_persona_juridica';

    protected $primaryKey = 'co_persona';

    public $timestamps = false;

    protected $fillable = [
        'co_persona', 'no_razon_social', 'no_razon_comercial', 'nu_partida_registros_publicos'
    ];

}
