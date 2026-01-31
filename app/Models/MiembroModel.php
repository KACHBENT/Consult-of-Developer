<?php

namespace App\Models;

use CodeIgniter\Model;

class MiembroModel extends Model
{
    protected $table            = 'miembros';
    protected $primaryKey       = 'id_miembro';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $protectFields = true;
    protected $allowedFields = [
        'id_servicio',
        'nombre',
        'puesto',
        'especialidad',
        'experiencia',
        'email',
        'foto',
        'activo',
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id_servicio'  => 'permit_empty|is_natural_no_zero',
        'nombre'       => 'required|min_length[3]|max_length[100]',
        'puesto'       => 'permit_empty|max_length[100]',
        'especialidad' => 'permit_empty|max_length[150]',
        'email'        => 'permit_empty|valid_email|max_length[100]',
        'foto'         => 'permit_empty|max_length[255]',
        'activo'       => 'permit_empty|in_list[0,1]',
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
