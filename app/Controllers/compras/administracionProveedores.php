<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


class administracionProveedores extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){

        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;
            // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
            return view('compras/vistas/proveedores', $data);
        }
    }
    
}