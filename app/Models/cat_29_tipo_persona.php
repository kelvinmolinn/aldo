<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_29_tipo_persona extends Model
{
    protected $table = 'cat_29_tipo_persona';
    protected $primaryKey = 'tipoPersonaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['tipoPersonaId','tipoPersona','codigoMH','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}