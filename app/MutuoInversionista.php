<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MutuoInversionista extends Model
{
    protected $table = 'inversionistas';
    public $timestamps = false;
    protected $fillable = [
        'co_solicitud_prestamo', 'co_persona', 'in_estado', 'co_usuario_modifica', 'fe_creacion', 'fe_modifica',
    ];

    protected $casts = [
        'in_estado' => 'boolean',
    ];

    protected $dates = [
        'fe_creacion', 'fe_modifica',
    ];

    public function persona()
    {
        return $this->belongsTo('App\PPersona', 'co_persona', 'co_persona');
    }

    public function cuentas()
    {
        return $this->hasMany('App\CuentaInversionista', 'inversionista_id');
    }
}
