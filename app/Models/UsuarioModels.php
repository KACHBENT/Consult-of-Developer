<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModels extends Model
{
    protected $table ='usuarios';
    protected $primarykey = 'id_usuario';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields =[
        'id_categoria',
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
        'fecha_registro',
    ];
}
