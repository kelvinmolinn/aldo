<?php

namespace App\Models;

use CodeIgniter\Model;

class InsertNuevoModulo extends Model
{
    protected $table = 'conf_modulos';
    protected $primaryKey = 'moduloId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['modulo','iconoModulo','urlModulo']; // Campos permitidos para la inserción

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

    public function moduloExiste($urlModulo)
    {
        // Realizar una consulta para verificar si la url ya existe en la base de datos
        $resultado = $this->where('urlModulo', $urlModulo)->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }
}

