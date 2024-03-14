<?php

namespace App\Models;

use CodeIgniter\Model;

class conf_modulos extends Model
{
    protected $table = 'conf_modulos';
    protected $primaryKey = 'moduloId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['modulo','iconoModulo','urlModulo', 'flgElimina']; // Campos permitidos para la inserción

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar
    
   
}

