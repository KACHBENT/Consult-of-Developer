<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaUsuarioModel extends Model
{
    protected $table            = 'categorias_usuarios';
    protected $primaryKey       = 'id_categoria';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $protectFields = true;
    protected $allowedFields = [
        'nombre_categoria',
        'descripcion',
    ];

    protected $useTimestamps = false;

    // Opcional: validaciones
    protected $validationRules = [
        'nombre_categoria' => 'required|min_length[2]|max_length[50]',
    ];
}
