<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HAuditoria extends Model
{
    protected $table = 'h_auditoria';

    protected $primaryKey = 'co_auditoria';

    public $timestamps = false;

    protected $fillable = [
        'co_auditoria', 'co_indice', 'no_auditoria', 'no_tabla', 'co_usuario_modifica', 'fe_usuario_modifica'
    ];

    protected $dates = [
        'fe_usuario_modifica'
    ];

    function scopeNombre($query, $nombre)
    {

        if ($nombre)
            return $query->where('name', 'LIKE', "%{$nombre}%");
    }

    function scopeUsuario($query, $usuario) {
        if ($usuario)
            return $query->where('p_usuario.co_usuario', $usuario);
    }

    function scopeFechaAuditoria($query, $fechaInicio, $fechaFin)
    {
        if ($fechaInicio != '' and $fechaFin != '')
            return $query->whereBetween('h_auditoria.fe_usuario_modifica', [$fechaInicio . ' 00:00:00', $fechaFin . ' 23:59:59']);
    }
}
