<?php

use App\Models;
use CodeIgniter\Model;

class ServiciosModels extends Model{
    protected $table = 'servicios';
    protected $primarykey = 'id_servicio';
    protected $returnType = 'array';
    protected $useAutoIncrement = true;
}