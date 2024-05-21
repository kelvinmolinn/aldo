<?php

namespace App\Models;

use CodeIgniter\Model;

class log_productos_precios extends Model
{
    protected $table = 'log_productos_precios';
    protected $primaryKey = 'logProductoPrecioId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoId','costoUnitarioFOB','CostoUnitarioRetaceo','costoPromedio','precioVentaAntes','precioVentaNuevo','fhAgrega','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}