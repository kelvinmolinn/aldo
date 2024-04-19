<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_menu_permisos;
use App\Models\conf_menus;

class ConfiguracionPermisos extends Controller
{
    public function indexPermisos($menu, $menuId)
    {        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
    
        $data['menu'] = $menu;
        $data['menuId'] = $menuId;

        $menus = new conf_menus();

        $datos = $menus->select('conf_modulos.modulo,conf_modulos.moduloId')
        ->join('conf_modulos', 'conf_modulos.moduloId = conf_menus.moduloId')
        ->where('conf_menus.flgElimina', 0)
        ->where('conf_menus.menuId', $menuId)
        ->first();
        //$modeloModulos = consulta hacia menus con jOIN a modulos
        $data['modulo'] = $datos;
        // $data['moduloId']
        return view('configuracion-general/vistas/administracionPermisos', $data);
        }
    }

    public function tablaPermisos()
    {
        $mostrarPermisos = new conf_menu_permisos();
        $datos = $mostrarPermisos
        ->select('conf_menus.menu, conf_menu_permisos.menuPermisoId, conf_menu_permisos.menuPermiso, conf_menu_permisos.descripcionMenuPermiso')
        ->join('conf_menus', 'conf_menus.menuId = conf_menu_permisos.menuId')
        ->where('conf_menu_permisos.flgElimina', 0)
        ->where('conf_menu_permisos.menuId', $this->request->getPost('menuId'))
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
                <button class="btn btn-primary mb-1" onclick="modalPermisos(`'.$columna['menuPermisoId'].'`, `editar`)" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna4 .= '
                <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
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
    public function modalPermiso()
    {
        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $menuPermisoId = $this->request->getPost('menuPermisoId');
            $permisos = new conf_menu_permisos();
            $data['campos'] = $permisos->select('conf_menus.menu,conf_menu_permisos.menuPermisoId,conf_menu_permisos.menuId,conf_menu_permisos.menuPermiso, conf_menu_permisos.descripcionMenuPermiso')
            ->join('conf_menus', 'conf_menus.menuId = conf_menu_permisos.menuId')
            ->where('conf_menu_permisos.flgElimina', 0)
            ->where('conf_menu_permisos.menuPermisoId', $menuPermisoId)->first();
        } else {
            $data['campos'] = [
                'menuPermisoId'             => 0,
                'menuId'                    => $this->request->getPost('menuId'),
                'menu'                      => $this->request->getPost('menu'),
                'menuPermiso'               => '', 
                'descripcionMenuPermiso'    => ''
            ];
        }
        $data['operacion'] = $operacion;

        return view('configuracion-general/modals/modalpermisos', $data);
    }
    
}