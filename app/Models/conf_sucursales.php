<?php

namespace App\Models;

use CodeIgniter\Model;

class conf_sucursales extends Model
{
    protected $table = 'conf_sucursales';
    protected $primaryKey = 'sucursalId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['sucursal','direccionSucursal'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar
}

