<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'p_persona';
    protected $primaryKey = 'co_persona';
    protected $fillable = ['co_persona','co_tipo_documento','in_tipo_persona',
        'nu_documento_identidad','no_completo_persona','fe_inicio_activades',
        'no_direccion_fiscal','co_distrito_fiscal','no_distirto_ultima','nu_telefono_contacto',
        'nu_telefono_contacto_2','nu_telefono_contacto_3','nu_celular','no_correo_electrónico'
    ];

    public function user()
    {
        return $this->hasOne('App\User','inversionista_id','co_persona');
    }
}
