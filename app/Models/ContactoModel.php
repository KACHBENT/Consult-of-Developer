<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactoModel extends Model
{
    protected $table      = 'contactos';
    protected $primaryKey = 'id_contacto';

    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'nombre','email','asunto','mensaje',
        'miembro_id','miembro_nombre','miembro_email',
        'token_verificacion','verificado',
        'fecha_creacion','ip','user_agent'
    ];

    protected $useTimestamps = false;
}
