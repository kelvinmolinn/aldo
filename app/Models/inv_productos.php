<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos extends Model
{
    protected $table = 'inv_productos';
    protected $primaryKey = 'productoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoTipoId','productoPlataformaId','unidadMedidaId','codigoProducto','producto','descripcionProducto','existenciaMinima','fechaInicioInventario','costoUnitarioFOB','CostoUnitarioRetaceo','CostoPromedio','flgProductoVenta','precioVenta','estadoProducto','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}