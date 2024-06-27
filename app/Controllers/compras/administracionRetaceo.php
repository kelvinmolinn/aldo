<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionRetaceo extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras/admin-retaceo/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('compras/vistas/retaceo', $data);
    }
}
