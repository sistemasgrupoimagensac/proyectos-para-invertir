<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PInversionista extends Model
{
    protected $table = 'p_inversionista';

    protected $primaryKey = 'co_inversionista';

    public $timestamps = false;

    protected $fillable = [
        'co_inversionista', 'co_solicitud_inversionista', 'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado',
        'co_estado', 'co_ocurrencia_actual', 'co_condicion_actual'
    ];
}
