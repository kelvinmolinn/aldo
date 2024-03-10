<?php

namespace App\Models;

use CodeIgniter\Model;

class TblUsuarios extends Model
{
    protected $table = 'conf_usuarios';
    protected $primaryKey = 'usuarioId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['empleadoId','rolId','correo','clave','estadoUsuario','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar
}
