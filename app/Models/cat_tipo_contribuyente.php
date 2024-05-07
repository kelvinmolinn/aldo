<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_tipo_contribuyente extends Model
{
    protected $table = 'cat_tipo_contribuyente';
    protected $primaryKey = 'actividadEconomicaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['tipoContribuyenteId','tipoContribuyente','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

}