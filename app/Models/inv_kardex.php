<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_kardex extends Model
{
    protected $table = 'inv_kardex';
    protected $primaryKey = 'kardexId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['tipoMovimiento','descripcionMovimiento','productoExistenciaId','existenciaAntesMovimiento','cantidadMovimiento','existenciaDespuesMovimiento','costoUnitarioFOB','costoUnitarioRetaceo','costoPromedio','precioVentaUnitario','fechaDocumento','fechaMovimiento','tablaMovimiento','tablaMovimientoId','flgElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

    //esta funcion de abajo es para validar si existe algo.. lo tome de existe codigo...

    public function existeCodigo($codigoProducto, $productoId)
    {
        // Realizar una consulta para verificar si el DUI ya existe en la base de datos
        $resultado = $this->where('codigoProducto', $codigoProducto)->whereNotIn('productoId', [$productoId])->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }

}