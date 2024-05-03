<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_unidades_medida extends Model
{
    protected $table = 'cat_unidades_medida';
    protected $primaryKey = 'unidadMedidaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['unidadMedida','abreviaturaUnidadMedida','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

    public function UnidadExiste($unidadMedida, $unidadMedidaId )
    {
        // Realizar una consulta para verificar si la unidad de medida ya existe en la base de datos
        $resultado = $this->where('unidadMedida', $unidadMedida)->whereNotIn('unidadMedidaId', [$unidadMedidaId])->countAllResults();

        return $resultado > 0; // Devuelve true si la unidad de medida existe, false en caso contrario
    }
}