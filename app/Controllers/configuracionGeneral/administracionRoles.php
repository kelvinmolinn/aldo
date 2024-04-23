<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_roles;
use App\Models\conf_menus;

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
        ->select('rolId, rol')
        ->where('flgElimina', 0)
        ->findAll();
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach($datos as $campos){

            $columna1 = $n;
            $columna2 = "<b>Rol: </b>". $campos['rol'];
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <a href="'.site_url('conf-general/admin-roles/vista/permisos/rol/' . $campos['rolId'] . '/' . $campos['rol']).'" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="Menús">
                    <i class="fas fa-bars nav-icon"></i>
                </a>
            ';
            
            $columna3 .= '
                <button class="btn btn-primary mb-1" onclick="modalRoles(`'.$campos['rolId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            '; 

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarRol(`'.$campos['rolId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
            $rolId = $this->request->getPost('rolId');

            $roles = new conf_roles();
            $data['campos'] = $roles
            ->select('rolId, rol')
            ->where('flgElimina', 0)
            ->where('rolId', $rolId)
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

    public function modalRolOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $roles = new conf_roles();

        $data = [
            'rol'  => $this->request->getPost('nombreRol')
        ];

        if($operacion == 'editar') {
            $operacionRol = $roles->update($this->request->getPost('rolId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionRol = $roles->insert($data);
        }
        if ($operacionRol) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Rol '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'rolId' => ($operacion == 'editar' ? $this->request->getPost('rolId') : $roles->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el menu'
            ]);
        }
    }
    public function eliminarRol(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $roles = new conf_roles();
        
        $rolId = $this->request->getPost('rolId');
        $data = ['flgElimina' => 1];
        
        $roles->update($rolId, $data);

        if($roles) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Rol eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el Rol'
            ]);
        }
    }

    public function menusModulos($rolId, $rol)
    {
        $data["rolId"] = $rolId;
        $data["rol"] = $rol;

         // Esto es un ejemplo, ajusta según tu situación
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/pagePermisosRoles', $data);
    }

    public function tablaPermisosRol(){
        $n = 1;

            $columna1 = $n;
            $columna2 = "<b>Menú: </b>";
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <a href="" data-toggle="tooltip" data-placement="top" title="Menús">
                    <i class="fas fa-bars nav-icon"></i>
                </a>
            ';
            
            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3
            );
    
            $n++;
        // Verifica si hay datos
        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalNuevoPermiso(){

        $menus = new conf_menus();

        $data['menu'] = $menus
            ->select('menuId, menu')
            ->where('flgElimina', 0)
            ->findAll();

        return view('configuracion-general/modals/modalNuevoPermisoRol',$data);
    }
    
}