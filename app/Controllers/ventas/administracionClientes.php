<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionClientes extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-clientes',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/clientes', $data);
    }
}
