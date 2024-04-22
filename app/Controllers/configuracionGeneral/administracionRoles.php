<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_roles;

class AdministracionRoles extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            return view('configuracion-general/vistas/administracionRoles', $data);
        }
    }
    public function tablaRoles(){

        $mostrarRoles = new conf_roles();
        $rolId = $this->request->getPost('rolId');

        $datos = $mostrarRoles
        ->select('rolId','rol')
        ->where('flgElimina', 0)
        ->where('rolId', $rolId)
        ->findAll();
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach($datos as $columna){

            $columna1 = $n;
            $columna2 = "<b>Rol: </b>". $columna['rol'];
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Menus">
                    <i class="fas fa-bars"></i>
                </button>
            ';
            
            $columna3 .= '
                <button class="btn btn-primary mb-1" onclick="modalRoles(`'.$columna['rolId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            '; 

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            '; 
            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3
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

    public function modalRol()
    {
        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {

            $roles = new conf_roles();
            $data['campos'] = $permisos
            ->select('rolId','rol')
            ->where('flgElimina', 0)
            ->first();
        } else {
            $data['campos'] = [
                'rolId'             => 0,
                'rol'               => '',
            ];
        }
        $data['operacion'] = $operacion;

        return view('configuracion-general/modals/modalRoles', $data);
    }
    
}