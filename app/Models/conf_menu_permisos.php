<?php

namespace App\Models;

use CodeIgniter\Model;

class conf_menu_permisos extends Model
{
    protected $table = 'conf_menu_permisos';
    protected $primaryKey = 'menuPermisoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['menuPermisoId','menuId','menuPermiso','descripcionMenuPermiso','fhEdita','fhAgrega','usuarioIdEdita','usuarioIdAgrega','flgElimina','fhElimina','usuarioIdElimina']; // Campos permitidos para la inserción


    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}
