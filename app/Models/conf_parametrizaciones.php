<?php

namespace App\Models;

use CodeIgniter\Model;

class conf_parametrizaciones extends Model
{
    protected $table = 'conf_parametrizaciones';
    protected $primaryKey = 'parametrizacionId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['parametrizacionId','tipoParametrizacion','valorParametrizacion','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar
}
