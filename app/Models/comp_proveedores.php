<?php

namespace App\Models;

use CodeIgniter\Model;

class comp_proveedores extends Model
{
    protected $table = 'comp_proveedores';
    protected $primaryKey = 'proveedorId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['proveedorId', 'tipoProveedorOrigen', 'tipoPersonaId', 'documentoIdentificacionId', 'ncrProveedor', 'numDocumentoIdentificacion', 'proveedor', 'proveedorComercial', 'actividadEconomicaId', 'tipoContribuyenteId', 'direccionProveedor', 'estadoProveedor', 'flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}