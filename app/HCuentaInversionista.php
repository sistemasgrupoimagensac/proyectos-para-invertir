<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HCuentaInversionista extends Model
{
    protected $table = 'h_cuenta_inversionista';

    protected $primaryKey = 'co_cuenta_inversionista';

    public $timestamps = false;

    protected $fillable = [
        'co_cuenta_inversionista', 'co_inversionista', 'co_tipo_moneda', 'co_banco', 'nu_cuenta', 'nu_cuenta_cci',
        'in_estado', 'co_usuario_modifica', 'fe_usuario_modifica'
    ];

    public function banco()
    {
        return $this->belongsTo('App\ABanco', 'co_banco', 'co_banco');
    }
}
