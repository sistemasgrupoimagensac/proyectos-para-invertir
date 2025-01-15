<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PSolicitudInversionista extends Model
{
    protected $table = 'p_solicitud_inversionista';

    protected $primaryKey = 'co_solicitud_inversionista';

    public $timestamps = false;

    protected $fillable = [
        'co_persona', 'co_usuario', 'co_tipo_inversionista', 'nu_monto_invertir', 'in_estado',
        'co_producto_inversionista', 'fe_solicitud_inversionista', 'co_tipo_origen', 'co_tiempo_pago', 'co_anuncio_publicado',
    ];

    public function analista()
    {
        return $this->belongsTo('App\PUsuario', 'co_usuario', 'co_usuario');
    }
}
