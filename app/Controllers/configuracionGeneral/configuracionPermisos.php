<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_menu_permisos;
use App\Models\conf_menus;
use App\Models\conf_roles_permisos;

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
            $columna2 = "<b>Permiso: </b>" . $columna['menuPermiso'] . "<br>" . "<b>Descripción: </b>" . $columna['descripcionMenuPermiso'];
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalUsuariosPermisos(`'.$columna['menuPermisoId'].'`,`'.$columna['menu'].'`,`'.$columna['menuPermiso'].'`)" data-toggle="tooltip" data-placement="top" title="usuarios">
                    <i class="fas fa-user-tie"></i>
                </button>
            ';

            $columna3 .= '
                <button class="btn btn-primary mb-1" onclick="modalPermisos(`'.$columna['menuPermisoId'].'`, `editar`)" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarPermiso(`'.$columna['menuPermisoId'].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function modalPermisosOperacion()
    {
        $operacion  = $this->request->getPost('operacion');
        $menuId     = $this->request->getPost('menuId');
        
        $permisos = new conf_menu_permisos();


        $data = [
            'menuId'                    => $menuId,
            'menuPermiso'               => $this->request->getPost('nombrePermiso'),
            'descripcionMenuPermiso'    => $this->request->getPost('descripcionPermiso')
        ];

        if($operacion == 'editar') {
            $operacionPermiso = $permisos->update($this->request->getPost('menuPermisoId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionPermiso = $permisos->insert($data);
        }
        if ($operacionPermiso) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Menu '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'menuPermisoId' => ($operacion == 'editar' ? $this->request->getPost('menuPermisoId') : $permisos->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el menu'
            ]);
        }
    }

    public function eliminarPermiso(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $eliminarPermiso = new conf_menu_permisos();
        
        $menuPermisoId = $this->request->getPost('menuPermisoId');
        $data = ['flgElimina' => 1];
        
        $eliminarPermiso->update($menuPermisoId, $data);

        if($eliminarPermiso) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Permiso eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el Permiso'
            ]);
        }
    }

    public function modalUsuariosPermisos()
    {
        
        $mostrarUsuariosPermiso = new conf_roles_permisos();
        $menuPermisoId = $this->request->getPost('menuPermisoId');
        $menu = $this->request->getPost('menu');
        $menuPermiso = $this->request->getPost('menuPermiso');
        $data['menuPermisoId'] = $menuPermisoId;
        $data['menu'] = $menu;
        $data['menuPermiso'] = $menuPermiso;
        
        return view('configuracion-general/modals/modalUsuariosPermisos', $data);
    }

    public function tablaPermisosUsuarios()
    {

        $mostrarUsuariosPermiso = new conf_roles_permisos();
        $menuPermisoId = $this->request->getPost('menuPermisoId');
        
        $datos = $mostrarUsuariosPermiso
            ->select('conf_roles.rol,conf_empleados.primerNombre,conf_empleados.segundoNombre,conf_empleados.primerApellido,conf_empleados.segundoApellido')
            ->join('conf_roles', 'conf_roles.rolId = conf_roles_permisos.rolId')
            ->join('conf_menu_permisos', 'conf_menu_permisos.menuPermisoId = conf_roles_permisos.menuPermisoId')
            ->join('conf_usuarios', 'conf_usuarios.rolId = conf_roles.rolId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = conf_usuarios.empleadoId')
            ->where('conf_roles_permisos.flgElimina', 0)
            ->where('conf_roles_permisos.menuPermisoId', $menuPermisoId)
            ->findAll();
        // Construye el array de salida
        $data['data'] = array();
        $n = 1;
        foreach($datos as $campos) {
            $columna1 = $n;
            $columna2 = "<b>Empleados: </b>" . $campos["primerNombre"]." ". $campos["segundoNombre"]." ". $campos["primerApellido"]." ". $campos["segundoApellido"];
            $columna3 = "<b>Rol: </b>". $campos["rol"];
            // Aquí puedes construir tus botones en la última columna  
            // Agrega la fila al array de salida
            $data['data'][] = array(
                $columna1,
                $columna2,
                $columna3
            );
            $n++;
        }
        

        // Verifica si hay datos
        if ($n > 1) {
            return $this->response->setJSON($data);
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
    
    public function obtenerPermisos() {
        $menuId = $this->request->getPost('menuId');

        $permisos = new conf_menu_permisos();

        $datos = $permisos
            ->select('menuPermisoId, menuPermiso')
            ->where('flgElimina', 0)
            ->where('menuId', $menuId)
            ->findAll();
        /*
        // Generar las opciones HTML para los permisos
        $options = '<option value=""></option>';
        foreach ($datos as $permiso) {
            $options .= '<option value="' . $permiso['menuPermisoId'] . '">' . $permiso['menuPermiso'] . '</option>';
        }

        // Devolver las opciones generadas como respuesta
        echo $options;
        */
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
        $menu  = $this->request->getPost('menu');
        $menuPermiso     = $this->request->getPost('menuPermiso');
        
        $permisos = new conf_menu_permisos();


        $data = [
            'menuId'                    => $menuId,
            'menuPermiso'               => $this->request->getPost('nombrePermiso'),
            'descripcionMenuPermiso'    => $this->request->getPost('descripcionPermiso')
        ];

            // Insertar datos en la base de datos
            $operacionPermiso = $permisos->insert($data);
        
        if ($operacionPermiso) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Menu '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'menuPermisoId' => ($operacion == 'editar' ? $this->request->getPost('menuPermisoId') : $permisos->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el menu'
            ]);
        }
    }
}
