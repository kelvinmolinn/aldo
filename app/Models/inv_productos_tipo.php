<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_tipo extends Model
{
    protected $table = 'inv_productos_tipo';
    protected $primaryKey = 'productoTipoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoTipo','flgElimina','fhAgrega','fhEdita'.'usuarioIdAgrega','usuarioIdEdita','fhElimina','usuarioIdElimina'];

    //protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    //protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    //protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

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


}