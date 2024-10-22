<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CuentaInversionista extends Model
{
    protected $table = 'cuentas_inversionistas';
    public $timestamps = false;
    protected $fillable = [
        'co_solicitud_prestamo', 'inversionista_id', 'banco', 'moneda_id', 'numero_cuenta', 'numero_cci', 'in_estado', 'co_usuario_creacion', 'fe_creacion', 'fe_modificacion'
    ];

    protected $casts = [
        'in_estado' => 'boolean',
    ];

    protected $dates = [
        'fe_creacion', 'fe_modificacion',
    ];

    public function inversionista()
    {
        return $this->belongsTo('App\MutuoInversionista', 'inversionista_id');
    }

    public function moneda()
    {
        return $this->belongsTo('App\ATipoMoneda', 'moneda_id', 'co_tipo_moneda');
    }
}
