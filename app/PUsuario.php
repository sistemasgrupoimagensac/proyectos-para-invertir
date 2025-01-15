<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PUsuario extends Model
{
    protected $table = 'p_usuario';

    protected $primaryKey = 'co_usuario';

    public $timestamps = false;

    protected $fillable = [
        'co_usuario', 'co_compania', 'co_persona', 'co_perfil', 'name', 'email', 'password',
        'co_usuario_modifica', 'fe_usuario_modifica', 'in_estado', 'no_iniciales', 'co_sede',
        'co_ultimo_menu', 'prefijo', 'nu_celular_trabajo', 'has_chats', 'password_app', 'token_app'
    ];
}
