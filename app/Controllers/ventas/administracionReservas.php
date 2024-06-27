<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionReservas extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-reservas',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/reservas', $data);
    }
}
