<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_22_documentos_identificacion extends Model
{
    protected $table = 'cat_22_documentos_identificacion';
    protected $primaryKey = 'documentoIdentificacionId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['documentoIdentificacionId','documentoIdentificacion','formatoDocumentoIdentificacion','codigoMH','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}