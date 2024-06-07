<?php

namespace App\Models;

use CodeIgniter\Model;

class inv_productos_plataforma extends Model
{
    protected $table = 'inv_productos_plataforma';
    protected $primaryKey = 'productoPlataformaId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['productoPlataforma','flgElimina','fhAgrega','fhEdita','usuarioIdAgrega','usuarioIdEdita','fhElimina','usuarioIdElimina'];
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


    public function existePlataforma($productoPlataforma, $productoPlataformaId)
    {
        // Realizar una consulta para verificar si el producto ya existe en la base de datos
        $resultado = $this->where('productoPlataforma', $productoPlataforma)
        ->where('flgElimina', 0)
        ->whereNotIn('productoPlataformaId', [$productoPlataformaId])->countAllResults();

        return $resultado > 0; // Devuelve true si el producto existe, false en caso contrario
    }

   


}