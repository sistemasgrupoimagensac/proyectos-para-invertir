<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPrestamo extends Model
{
    protected $table = 'p_prestamo';

    // protected $primaryKey = 'co_prestamo';

    public $timestamps = false;

    protected $fillable = [
        'co_prestamo', 'co_solicitud_prestamo', 'co_inversionista', 'co_distrito', 'co_estado', 'nu_dia_pago',
        'fe_primera_cuota', 'nu_monto_prestamo', 'nu_periodo_pago', 'nu_tasa_interes', 'nu_cuota', 'de_observacion',
        'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado',
        'co_usuario', //CODIGO DE USUAIRO GESTOR DE VENTAS
        'co_usuario_inversion',
        'co_usuario_cobranza',
        'co_usuario_legal',
        'co_pago_responsable',
        'nu_latitud',
        'nu_longitud',
        'no_direccion_garantia',
        'co_tipo_cliente',
        'fe_desembolso',
        'nu_dias_aviso',
        'co_unico_prestamo',

        'nu_cuota_minimo',
        'mo_cuota_minimo',
        'nu_cuota_maximo',
        'mo_cuota_maximo',

        'nu_llamadas',
        'nu_whatsapp',

        'fe_contrato',
        'fe_escritura',
        'co_ocurrencia_actual',
        'co_condicion_actual'
    ];

    function scopeCriterios($query, $criterio, $texto)
    {
        if ($criterio != '' and $texto != '') {
            $criterio = base64_decode($criterio);
            if ($criterio == 'codigo')
                $criterio = 'p_prestamo.co_unico_prestamo';

            return $this->separarTexto($query, $criterio, $texto);
        }

    }

    function separarTexto($query, $criterio, $texto)
    {
        $porciones = explode(" ", $texto);
        $i = 1;
        foreach ($porciones as $porcion) :
            $query->where($criterio, 'LIKE', "%{$porcion}%");
            $i++;
        endforeach;

        return $query;

    }

    function scopeFecha($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio != '' and $fechaFin != '')
            return $query->whereRaw("(select DATE(fe_usuario_modifica) from h_estado_prestamo where h_estado_prestamo.co_prestamo  = p_prestamo.co_prestamo and h_estado_prestamo.co_estado = 5
                order by co_estado_prestamo asc limit 1) >= '{$fechaInicio}' 
                AND 
                (select DATE(fe_usuario_modifica) from h_estado_prestamo where h_estado_prestamo.co_prestamo  = p_prestamo.co_prestamo and h_estado_prestamo.co_estado = 5
                order by co_estado_prestamo asc limit 1) <= '{$fechaFin}'");
    }

    public function solicitudPrestamo()
    {
        return $this->belongsTo('App\PSolicitudPrestamo', 'co_solicitud_prestamo', 'co_solicitud_prestamo');
    }

    public function user_ventas()
    {
        return $this->belongsTo('App\User', 'co_usuario', 'co_usuario');
    }

    public function tasa()
    {
        return $this->belongsTo('App\ATipoCliente', 'co_tipo_cliente', 'co_tipo_cliente');
    }

    public function cuotas()
    {
        return $this->hasMany('App\HDetallePrestamo', 'co_prestamo', 'co_prestamo')->where('in_estado', 1);
    }

    public function total_a_pagar()
    {
        return $this->cuotas()->sum('nu_monto_pagar');
    }

    public function total_intereses()
    {
        return $this->cuotas()->sum('nu_monto_interes');
    }
}
