<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;

class AdministracionUsuarios extends Controller
{
    public function index()
    {
        // Aquí puedes cargar cualquier modelo necesario
        // $usuarioModel = new UsuarioModel();
        // $usuarios = $usuarioModel->getUsuarios();

        // Puedes pasar datos a la vista si es necesario
        // $data['usuarios'] = $usuarios;

        // Llamar al método mensaje y pasar su resultado a la vista
        $data['mensaje'] = $this->mensaje();


        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionUsuarios', $data);
    }

    public function mensaje() {
        return "Hola mundo";
    }

    public function modalAdministracionUsuarios()
    {
                // Llamar al método mensaje y pasar su resultado a la vista
                
        // Cargar la modal
        return view('configuracion-general/modals/modalAdministracionUsuarios');
    }
}
