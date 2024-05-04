<?php

namespace App\Models;

use CodeIgniter\Model;

class comp_proveedores_contacto extends Model
{
    protected $table = 'comp_proveedores_contacto';
    protected $primaryKey = 'proveedorContactoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['proveedorContactoId', 'proveedorId', 'tipoContactoId', 'contactoProveedor', 'flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}