<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PPersona extends Model
{
    protected $table = 'p_persona';

    protected $primaryKey = 'co_persona';

    public $timestamps = false;

    protected $fillable = [
        'co_persona', 'co_tipo_documento_identidad', 'in_tipo_persona', 'nu_documento_identidad', 'no_completo_persona',
        'fe_inicio_actividades', 'no_direccion_fiscal', 'no_direccion_ultima', 'co_distrito_ultima', 'nu_telefono_fijo',
        'co_distrito_fiscal', 'fe_nacimiento',
        'nu_celular', 'no_correo_electronico', 'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado',
        'in_tipo_perfil_persona', 'co_usuario_gestor', 'nu_telefono_contacto_2', 'nu_telefono_contacto_3',
        'nu_telefono_contacto', 'in_sistema'
    ];

    protected $dates = [
        'fe_inicio_actividades', 'fe_usuario_modifica', 'fe_nacimiento'
    ];

    public function persona_natural()
    {
        return $this->hasOne('App\HPersonaNatural', 'co_persona', 'co_persona');
    }

    public function persona_juridica()
    {
        return $this->hasOne('App\HPersonaJuridica', 'co_persona', 'co_persona');
    }

    // public function conyuge()
    // {
    //     return $this->belongsToMany('App\PPersona')
    //                 ->using('App\HPersonaRepresentante')
    //                 ->withPivot(['nu_partida', 'parentesco', 'is_apoderado'])
    //                 ->wherePivot('co_tipo_relacion', 1)
    //                 ->wherePivot('in_estado', 1);
    // }

    function scopeSolicitante($query, $solicitante)
    {

        if ($solicitante) {
            $names = explode(' ', trim($solicitante));
            foreach ($names as $name) {
                $query->where('p_persona.no_completo_persona', 'LIKE', "%{$name}%");
            }
            return $query;
        }
    }

    function scopeUsuario($query, $usuario, $perfil)
    {
        if ($perfil != 1)
            return $query->where('p_persona.co_usuario_gestor', $usuario)/*->orWhereNull('co_usuario_gestor')*/ ;
    }

    function scopeSolicitudInversion($query, $codigo)
    {
        if ($codigo)
            return $query->where('p_solicitud_inversionista.co_solicitud_inversionista', $codigo);
    }

    function scopeSolicitudPrestamo($query, $codigo)
    {
        if ($codigo)
            return $query->where('p_solicitud_prestamo.co_solicitud_prestamo', $codigo);
    }

    public function scopeGestorVentas($query, $gestor)
    {
        if ($gestor) {
            return $query->where('p_prestamo.co_usuario', $gestor);
        }
    }

    public function scopeGestorInversion($query, $gestor)
    {
        if ($gestor) {
            return $query->where('p_usuario_inversion.co_usuario', $gestor);
        }
    }

    public function distrito()
    {
        return $this->belongsTo('App\PDistrito', 'co_distrito_fiscal');
    }

    public function representantes()
    {
        return $this->belongsToMany('App\PPersona', 'h_persona_representante', 'co_persona', 'co_persona_representante')
                    ->withPivot(['co_tipo_relacion', 'nu_partida', 'parentesco', 'is_apoderado', 'firmara', 'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado']);
    }

    public function tipo_documento()
    {
        return $this->belongsTo('App\ATipoDocumentoIdentidad', 'co_tipo_documento_identidad');
    }
    
    // ERROR EN LA RELACION
    /* public function interesesInversionistas()
    {
        return $this->hasMany(MeInteresa::class, 'co_inversionista', 'co_persona');
    } */
}
