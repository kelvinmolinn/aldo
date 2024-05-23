<?php

namespace App\Models;

use CodeIgniter\Model;

class comp_compras extends Model
{
    protected $table = 'comp_compras';
    protected $primaryKey = 'comprasId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['compraId', 'proveedorId', 'tipoDTEId', 'fechaDocumento', 'numFactura', 'paisId', 'ObsCompra', 'porcentajeIva', 'flgRetaceo', 'estadoCompra', 'fechaAnulacion', 'obsAnulacion', 'flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}