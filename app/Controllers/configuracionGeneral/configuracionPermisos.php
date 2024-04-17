<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_menu_permisos;

class ConfiguracionPermisos extends Controller
{
    public function indexPermisos()
    {        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
    
        $data['variable'] = 0;
        
        return view('configuracion-general/vistas/administracionPermisos', $data);
        }
    }

    public function tablaPermisos()
    {
        $mostrarPermisos = new conf_menu_permisos();
        $datos = $mostrarPermisos
        ->select('conf_menus.menu, conf_menu_permisos.menuPermiso, conf_menu_permisos.descripcionMenuPermiso')
        ->join('conf_menus', 'conf_menus.menuId = conf_menu_permisos.menuId')
        ->where('conf_menu_permisos.flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Módulo: </b>" . $columna['menu'];
            $columna3 = "<b>Permiso: </b>" . $columna['menuPermiso'] . "<br>" . "<b>Descripción: </b>" . $columna['descripcionMenuPermiso'];
            // Aquí puedes construir tus botones en la última columna
            $columna4 = '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="usuarios">
                    <i class="fas fa-user-tie"></i>
                </button>
            ';

            $columna4 .= '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna4 .= '
                <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-ban"></i>
                </button>
            ';    
            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4
            );
    
            $n++;
        }
        // Verifica si hay datos
        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }
    
}