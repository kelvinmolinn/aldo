<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_existencias extends Model
{
    protected $table = 'inv_productos_existencias';
    protected $primaryKey = 'productoExistenciaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoId','sucursalId','existenciaProducto','existenciaReservada','flgElimina','fhAgrega','fhEdita','usuarioIdAgrega','usuarioIdEdita','fhElimina','usuarioIdElimina'];

        //Agregar desde aqui

        protected $beforeInsert = ['manageInsertTimestamps'];
        protected $beforeUpdate = ['manageUpdateTimestamps'];
    
        protected function manageInsertTimestamps(array $data)
        {
            // Obtén la sesión y el ID del usuario
            $session = session();
            $usuarioId = $session->get('usuarioId');
    
            // Establecer usuarioIdAgrega
            $data['data']['usuarioIdAgrega'] = $usuarioId;
    
            // Establecer fhAgrega
            $data['data']['fhAgrega'] = date('Y-m-d H:i:s');
    
            return $data;
        }
    
        protected function manageUpdateTimestamps(array $data)
        {
            // Obtén la sesión y el ID del usuario
            $session = session();
            $usuarioId = $session->get('usuarioId');
    
            // Establecer usuarioIdEdita
            $data['data']['usuarioIdEdita'] = $usuarioId;
    
            // Actualiza fhEdita
            $data['data']['fhEdita'] = date('Y-m-d H:i:s');
    
            // Si se hace una eliminación lógica, actualiza fhElimina y usuarioIdElimina
            if (isset($data['data']['flgElimina']) && $data['data']['flgElimina'] == 1) {
                $data['data']['fhElimina'] = date('Y-m-d H:i:s');
                $data['data']['usuarioIdElimina'] = $usuarioId; // Establece usuarioIdElimina desde la sesión
            }
    
            return $data;
        }
    
    

    public function existeCodigo($codigoProducto, $productoId)
    {
        // Realizar una consulta para verificar si el DUI ya existe en la base de datos
        $resultado = $this->where('codigoProducto', $codigoProducto)->whereNotIn('productoId', [$productoId])->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }

}