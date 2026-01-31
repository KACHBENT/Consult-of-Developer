<?php

namespace App\Models;

use CodeIgniter\Model;

class MovimientoModel extends Model
{
    protected $table            = 'movimientos';
    protected $primaryKey       = 'id_movimiento';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $protectFields = true;
    protected $allowedFields = [
        'id_usuario',
        'accion',
        'descripcion',
        // 'fecha' // normalmente NO lo envías, lo pone MySQL solo
    ];

    protected $useTimestamps = false;

    protected $validationRules = [
        'id_usuario'   => 'permit_empty|is_natural_no_zero',
        'accion'       => 'permit_empty|max_length[150]',
        'descripcion'  => 'permit_empty',
    ];

    // Helper rápido para registrar movimientos
    public function log(?int $idUsuario, string $accion, ?string $descripcion = null): int
    {
        return (int) $this->insert([
            'id_usuario'  => $idUsuario,
            'accion'      => $accion,
            'descripcion' => $descripcion,
        ]);
    }
}
