<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\Modulos;

class AdministracionPermisos extends Controller
{
    public function configuracionModulos()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionModulos');
    }
    public function modalnuevoModulo()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos');
    }

    public function insertarNuevoModulo()
    {
        $model = new Modulos();

        $modulo = $this->request->getPost('modulo');
            
        $data = [
            'modulo'            => $this->request->getPost('modulo'),
            'iconoModulo'       => $this->request->getPost('iconoModulo'),
            'urlModulo'         => $this->request->getPost('urlModulo')
            
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        // Insertar datos en la base de datos
        $insertModulo = $model->insert($data);
        if ($insertModulo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Modulo Agregado correctamente',
                'moduloId' => $model->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el empleado'
            ]);
        }
    }

}
