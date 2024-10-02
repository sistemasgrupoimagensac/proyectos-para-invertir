<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeInteresa extends Model
{
    protected $table='me_interesa';
    public $timestamps=false;
    protected $fillable=['co_prestamo','comentario','estado','fe_interaccion','fe_modificacion','co_inversionista'];

}
