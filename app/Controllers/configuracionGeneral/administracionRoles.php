<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_roles;
use App\Models\conf_menus;
use App\Models\conf_menu_permisos;
use App\Models\conf_roles_permisos;

class AdministracionRoles extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index()
    {
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'conf-general/admin-roles/index',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('configuracion-general/vistas/administracionRoles', $data);
    }
    public function tablaRoles()
    {
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
            $jsonPermisos = [
                "rolId"         => $campos['rolId'],
                "rol"           => $campos['rol']
            ];

            $columna3 = '
                <button type="button" onclick="cambiarInterfaz(`conf-general/admin-roles/vista/permisos/rol`, '.htmlspecialchars(json_encode($jsonPermisos)).');" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="Menús">
                    <i class="fas fa-bars nav-icon"></i>
                </button>
            ';
            
            $columna3 .= '
                <button type="button" class="btn btn-primary mb-1" onclick="modalRoles(`'.$campos['rolId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            '; 
            /*
            $columna3 .= '
                <button  type="button" class="btn btn-danger mb-1" onclick="eliminarRol(`'.$campos['rolId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            '; 
            */
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
    public function eliminarRol()
    {
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

    public function menusModulos()
    {
        $session = session();
        $data["rolId"] = $this->request->getPost('rolId');
        $data["rol"] = $this->request->getPost('rol');

        $camposSession = [
            'renderVista'       => 'No',
            'rolId'             => $data['rolId'],
            'rol'               => $data['rol']
        ];
        $session->set([
            'route'             => 'conf-general/admin-roles/vista/permisos/rol',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('configuracion-general/vistas/pagePermisosRoles', $data);
    }

    public function tablaPermisosRol()
    {
        $rol = $this->request->getPost('rol');
        $rolId = $this->request->getPost('rolId');

        $rolesPermiso = new conf_roles_permisos();

        $datos = $rolesPermiso
            ->select('conf_menus.menu, conf_menus.menuId, conf_roles_permisos.rolId')
            ->join('conf_menu_permisos', 'conf_menu_permisos.menuPermisoId = conf_roles_permisos.menuPermisoId')
            ->join('conf_menus', 'conf_menus.menuId = conf_menu_permisos.menuId')
            ->where('conf_roles_permisos.flgElimina', 0)
            ->where('conf_roles_permisos.rolId', $rolId)
            ->groupBy('conf_menu_permisos.menuId')
            ->findAll();
            
        $output['data'] = array();
        $n = 1;
        foreach($datos as $campos){
            $columna1 = $n;
            $columna2 = "<b>Menú: </b>".$campos['menu'];
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalPermisosRolMenu(`'.$campos['menuId'].'`,`'.$campos['menu'].'`,`'.$campos['rolId'].'`)" data-toggle="tooltip" data-placement="top" title="Permisos del menú">
                    <i class="fas fa-bars nav-icon"></i>
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

    public function modalNuevoPermiso(){

        $menus = new conf_menus();
        $data['rol'] = $this->request->getPost('rol');
        $data['rolId'] = $this->request->getPost('rolId');

        $data['menu'] = $menus
            ->select('menuId, menu')
            ->where('flgElimina', 0)
            ->findAll();

        return view('configuracion-general/modals/modalNuevoPermisoRol',$data);
    }
    
    public function obtenerPermisos() {
        $menuId = $this->request->getPost('menuId');
        //$rolId = $this->request->getPost('rolId');
        $existePermisos = $this->request->getPost('existePermisos');


        if($existePermisos == 'Si') {
            $permisosMenu = new conf_menu_permisos();
            $datos = $permisosMenu
                ->select('conf_menu_permisos.menuPermisoId, conf_menu_permisos.menuPermiso')
                ->where('conf_menu_permisos.flgElimina', 0)
                ->where('conf_menu_permisos.menuId', $menuId)
                ->whereNotIn('conf_menu_permisos.menuPermisoId', function($builder) {
                    $builder->select('conf_roles_permisos.menuPermisoId')
                            ->from('conf_roles_permisos')
                            ->where('conf_roles_permisos.rolId', 1)
                            ->where('conf_roles_permisos.flgElimina', 0)
                            ->where('conf_roles_permisos.menuPermisoId','conf_menu_permisos.menuPermisoId');
                })
                ->findAll();
            
        } else {
            $permisos = new conf_menu_permisos();
            $datos = $permisos
                ->select('menuPermisoId, menuPermiso')
                ->where('flgElimina', 0)
                ->where('menuId', $menuId)
                ->findAll();
        }

       $opcionesSelect = array();

        $n = 0;
        foreach($datos as $permiso){
            $n += 1;
            $opcionesSelect[] = array("valor" => $permiso['menuPermisoId'], "texto" => $permiso['menuPermiso']);
        }
        
        if ($n > 0) {
            echo json_encode($opcionesSelect);
        }else{
            echo json_encode(array('data'=>''));
        }
    }
    
     public function permisosMenusOperacion()
    {
        $menuPermiso    = $this->request->getPost('menuPermiso');
        $rol            = $this->request->getPost('rol');
        $rolId          = $this->request->getPost('rolId');
        
        $rolesPermiso = new conf_roles_permisos();


        $data = [
            'rolId'                    => $rolId,
            'menuPermisoId'            => $this->request->getPost('menuPermiso')
        ];

            // Insertar datos en la base de datos
            $OperacionRolesPermiso = $rolesPermiso->insert($data);
        
        if ($OperacionRolesPermiso) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Permiso se agrego correctamente',
                'rolMenuId' => $rolesPermiso->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el permiso'
            ]);
        }
    }
    public function modalPermisosRolMenu(){
        /*$menus = new conf_menus();
        $data['rol'] = $this->request->getPost('rol');
        $data['rolId'] = $this->request->getPost('rolId');

        $data['menu'] = $menus
            ->select('menuId, menu')
            ->where('flgElimina', 0)
            ->findAll();*/
        $data['menuId'] = $this->request->getPost('menuId');

        return view('configuracion-general/modals/modalPermisosRolMenu',$data);
    }

}