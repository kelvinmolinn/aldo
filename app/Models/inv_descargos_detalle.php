<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_descargos_detalle extends Model
{
    protected $table = 'inv_descargos_detalle';
    protected $primaryKey = 'descargoDetalleId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['descargosId','productoId','cantidadDescargo','obsDescargoDetalle','flgElimina','usuarioIdAgrega','usuarioIdEdita','fhElimina','usuarioIdElimina'];

    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at
    protected $beforeUpdate = ['manageTimestamps'];
    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar


    // Función para gestionar los timestamps antes de actualizar
    protected function manageTimestamps(array $data)
    {
        // Obtén la sesión y el ID del usuario
        $session = session();
        $usuarioId = $session->get('usuarioId');

        // Si es una inserción y usuarioIdAgrega no está establecido
        if (!isset($data['data']['descargoDetalleId'])) {
            $data['data']['usuarioIdAgrega'] = $usuarioId;
        } else {
            // Si es una actualización y usuarioIdEdita no está establecido
            if (!isset($data['data']['usuarioIdEdita'])) {
                $data['data']['usuarioIdEdita'] = $usuarioId;
            }
        }

        // Actualiza fhEdita
        $data['data'][$this->updatedField] = date('Y-m-d H:i:s');

        // Si se hace una eliminación lógica, actualiza fhElimina y usuarioIdElimina
        if (isset($data['data']['flgElimina']) && $data['data']['flgElimina'] == 1) {
            $data['data']['fhElimina'] = date('Y-m-d H:i:s');
            $data['data']['usuarioIdElimina'] = $usuarioId; // Establece usuarioIdElimina desde la sesión
        }

        return $data;
    }


    //esta funcion de abajo es para validar si existe algo.. lo tome de existe codigo...

    public function existeCodigo($codigoProducto, $productoId)
    {
        // Realizar una consulta para verificar si el DUI ya existe en la base de datos
        $resultado = $this->where('codigoProducto', $codigoProducto)->whereNotIn('productoId', [$productoId])->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }

}