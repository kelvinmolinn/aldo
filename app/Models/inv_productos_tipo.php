<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_tipo extends Model
{
    protected $table = 'inv_productos_tipo';
    protected $primaryKey = 'productoTipoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoTipo'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}