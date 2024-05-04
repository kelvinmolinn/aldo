<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;
use App\Models\comp_proveedores_contacto;
use App\Models\cat_tipo_contacto;

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