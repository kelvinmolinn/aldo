<?php

namespace App\Models;

use CodeIgniter\Model;

class cat_14_unidades_medida extends Model
{
    protected $table = 'cat_14_unidades_medida';
    protected $primaryKey = 'unidadMedidaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['unidadMedida','abreviaturaUnidadMedida','codigoMH','flgElimina','fhAgrega','fhEdita','usuarioIdAgrega','usuarioIdEdita','fhElimina','usuarioIdElimina'];

    
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
    
    public function UnidadExiste($unidadMedida, $unidadMedidaId )
    {
        // Realizar una consulta para verificar si la unidad de medida ya existe en la base de datos
        $resultado = $this->where('unidadMedida', $unidadMedida)->whereNotIn('unidadMedidaId', [$unidadMedidaId])->countAllResults();

        return $resultado > 0; // Devuelve true si la unidad de medida existe, false en caso contrario
    }
}