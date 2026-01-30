<?php

namespace App\Models;

use CodeIgniter\Model;


class CategoriaUsuariosModels extends Model{

    protected $table = 'categorias_usuarios';
    protected $primarykey = 'id_categoria';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
    protected $allowedFields =[
        'nombre_categoria',
        'descripcion',
    ];

}