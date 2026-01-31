<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    protected $table            = 'servicios';
    protected $primaryKey       = 'id_servicio';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $protectFields = true;
    protected $allowedFields = [
        'id_usuario',
        'nombre',
        'descripcion',
        'especificaciones',
        'precio',
        'activo',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'nombre'     => 'required|min_length[3]|max_length[100]',
        'precio'     => 'permit_empty|decimal',
        'activo'     => 'permit_empty|in_list[0,1]',
        'id_usuario' => 'permit_empty|is_natural_no_zero',
    ];

    public function activos()
    {
        return $this->where('activo', 1);
    }

    public function desactivar(int $id): bool
    {
        return (bool) $this->update($id, ['activo' => 0]);
    }

    public function activar(int $id): bool
    {
        return (bool) $this->update($id, ['activo' => 1]);
    }
}
