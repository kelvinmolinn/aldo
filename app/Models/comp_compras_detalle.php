<?php

namespace App\Models;

use CodeIgniter\Model;

class comp_compras_detalle extends Model
{
    protected $table = 'comp_compras_detalle';
    protected $primaryKey = 'compraDetalleId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['compraDetalleId', 'compraId', 'productoId', 'cantidadProducto', 'precioUnitario', 'precioUnitarioIVA', 'ivaUnitario', 'ivaTotal', 'totalCompraDetalle', 'totalCompraDetalleIVA', 'flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}