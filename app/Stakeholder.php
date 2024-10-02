<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stakeholder extends Model
{
    protected $table='stakeholder';
    protected $fillable=['id','co_usuario','','heart','fe_interaccion','fe_modificacion'];
}
