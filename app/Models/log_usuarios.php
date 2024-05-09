<?php

namespace App\Models;

use CodeIgniter\Model;

class log_usuarios extends Model
{
    protected $table = 'log_usuarios';
    protected $primaryKey = 'logUsuarioId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['usuarioId', 'fhIngreso', 'fhSalida', 'logInterfaces', 'logReportes', 'logAgrega', 'logEdita', 'logElimina', 'ipIngreso', 'infoNavegador', 'usuarioIdEdita', 'usuarioIdAgrega', 'flgElimina', 'usuarioIdElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}