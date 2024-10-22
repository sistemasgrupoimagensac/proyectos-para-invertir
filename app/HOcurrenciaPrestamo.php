<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HOcurrenciaPrestamo extends Model
{
    protected $table = 'h_ocurrencia_prestamo';

    protected $primaryKey = 'co_ocurrencia_prestamo';

    public $timestamps = false;

    protected $fillable = [
        'co_ocurrencia_prestamo', 'co_ocurrencia', 'co_prestamo', 'co_condicion',
        'de_observacion',
        'co_usuario_creacion',
        'fe_usuario_creacion',
        'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado'
    ];

    protected $dates = [
        'fe_usuario_modifica'
    ];

    public function subnotas()
    {
        return $this->hasMany('App\SubNota', 'co_ocurrencia_nota', 'co_ocurrencia_prestamo');
    }
}
