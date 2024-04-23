<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_existencias extends Model
{
    protected $table = 'inv_productos_existencias';
    protected $primaryKey = 'productoExistenciaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoId','sucursalId','existenciaProducto','existenciaReservada','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

    public function existeCodigo($codigoProducto, $productoId)
    {
        // Realizar una consulta para verificar si el DUI ya existe en la base de datos
        $resultado = $this->where('codigoProducto', $codigoProducto)->whereNotIn('productoId', [$productoId])->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }

}