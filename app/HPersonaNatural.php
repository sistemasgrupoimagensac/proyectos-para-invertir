<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HPersonaNatural extends Model
{
    protected $table = 'h_persona_natural';

    protected $primaryKey = 'co_persona';

    public $timestamps = false;

    protected $fillable = [
        'co_persona', 'co_estado_civil', 'co_persona_conyuge', 'no_apellido_paterno', 'no_apellido_materno', 'no_nombres', 'in_sexo', 'ocupacion'
    ];

    public function estado_civil()
    {
        return $this->belongsTo('App\AEstadoCivil', 'co_estado_civil');
    }
}
