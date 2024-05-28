<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_20_paises extends Model
{
    protected $table = 'cat_20_paises';
    protected $primaryKey = 'paisId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['paisId','pais','abreviaturaPais','codigoTelefonoPais','codigoMH','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar
}