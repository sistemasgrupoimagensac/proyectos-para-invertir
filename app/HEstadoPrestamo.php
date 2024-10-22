<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HEstadoPrestamo extends Model
{
    protected $table = 'h_estado_prestamo';

    protected $primaryKey = 'co_estado_prestamo';

    public $timestamps = false;

    protected $fillable = [
        'co_estado_prestamo', 'co_estado', 'co_prestamo', 'de_observacion', 'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado',
        'co_usuario_creacion', 'fe_usuario_creacion', 'fecha_firma_escritura_publica', 'can_cheque_custodia', 'lugar_cheque_custodia', 'fecha_entrega_cheque_custodia'
    ];

    protected $dates = [
        'fe_usuario_modifica', 'fe_usuario_creacion', 'fecha_firma_escritura_publica', 'fecha_entrega_cheque_custodia'
    ];
}
