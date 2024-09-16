<?php

namespace App\Controllers\inventario\reportes;
use CodeIgniter\Controller;


class administracionReportes extends Controller{

    public function indexReportes(){
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'inventario/admin-reportes/index',
                'camposSession'     => json_encode($camposSession)
            ]);

         return view('inventario/vistas/administracionReportes', $data);
        }
    }
        
}