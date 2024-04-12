<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_plataforma extends Model
{
    protected $table = 'inv_productos_plataforma';
    protected $primaryKey = 'productoPlataformaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoPlataforma','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}