<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'id_usuario';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $protectFields = true;
    protected $allowedFields = [
        'id_categoria',
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
        // 'fecha_registro' // normalmente NO lo envías, lo pone MySQL solo
    ];

    protected $useTimestamps = false;

    // Callbacks para hashear password si viene en el payload
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected $validationRules = [
        'nombre'      => 'required|min_length[3]|max_length[100]',
        'email'       => 'required|valid_email|max_length[100]',
        'password'    => 'permit_empty|min_length[6]|max_length[255]',
        'rol'         => 'required|in_list[ADMIN,CLIENTE]',
        'estado'      => 'permit_empty|in_list[0,1]',
        'id_categoria'=> 'permit_empty|is_natural_no_zero',
    ];

    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        // Si viene vacío en update, no lo cambies
        if ($data['data']['password'] === '' || $data['data']['password'] === null) {
            unset($data['data']['password']);
            return $data;
        }

        // Evitar doble-hash si ya viene hasheado (heurística simple)
        $pass = (string) $data['data']['password'];
        if (str_starts_with($pass, '$2y$') || str_starts_with($pass, '$argon2')) {
            return $data;
        }

        $data['data']['password'] = password_hash($pass, PASSWORD_BCRYPT);
        return $data;
    }

    // Helpers típicos
    public function activos()
    {
        return $this->where('estado', 1);
    }

    public function desactivar(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 0]);
    }

    public function activar(int $id): bool
    {
        return (bool) $this->update($id, ['estado' => 1]);
    }

    // Opcional: usuario con su categoría (JOIN)
    public function getConCategoria(int $idUsuario): ?array
    {
        return $this->select('usuarios.*, categorias_usuarios.nombre_categoria, categorias_usuarios.descripcion AS categoria_descripcion')
            ->join('categorias_usuarios', 'categorias_usuarios.id_categoria = usuarios.id_categoria', 'left')
            ->where('usuarios.id_usuario', $idUsuario)
            ->first();
    }
}
