<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RPrestamoInversionista extends Model
{
    protected $table = 'r_prestamo_inversionista';

    protected $primaryKey = 'co_prestamo_inversionista';

    public $timestamps = false;

    protected $fillable = [
        'co_prestamo_inversionista', 'co_prestamo', 'co_inversionista', 'in_estado',
        'co_usuario_modifica', 'fe_usuario_modifica', 'nu_porcentaje'
    ];
}
