<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PNotificacion extends Model
{
    protected $table = 'p_notificacion';

    protected $primaryKey = 'co_notificacion';

    public $timestamps = false;

    protected $fillable = [
        'co_notificacion',
        'co_tipo_notificacion',
        'de_tipo_notificacion',
        'fe_notificacion',
        'co_usuario_notificacion',
        'de_url',
        'co_usuario_modifica',
        'fe_usuario_modifica',
        'in_estado',
    ];

    protected $dates = [
        'fe_notificacion',
    ];
}
