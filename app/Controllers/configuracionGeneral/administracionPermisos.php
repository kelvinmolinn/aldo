<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_modulos;
use App\Models\conf_menus;


class AdministracionPermisos extends Controller
{
    public function configuracionModulos()
    {
        $mostrarModulos = new conf_modulos();

        $data['modulos'] = $mostrarModulos
        ->select('conf_modulos.*')
        ->where('conf_modulos.flgElimina', 0)
        ->findAll();
        // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionModulos', $data);
    }

    public function configuracionMenus()
    {
        $mostrarMenus = new conf_menus();

        $data['menus'] = $mostrarMenus
        ->select('conf_menus.*')
        ->where('conf_menus.flgElimina', 0)
        ->findAll();
        // Cargar la vista 'pageMenusModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/pageMenusModulos', $data);
    }

    public function modalModulo()
    {
        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $moduloId = $this->request->getPost('moduloId');
            $modulo = new conf_modulos();
            $data['campos'] = $modulo->select('moduloId,modulo,iconoModulo, urlModulo')->where('flgElimina', 0)->where('moduloId', $moduloId)->first();
        } else {
            $data['campos'] = [
                'moduloId'      => 0,
                'modulo'        => '',
                'iconoModulo'   => '', 
                'urlModulo'     => ''
            ];
        }
        $data['operacion'] = $operacion;
        // Cargar la vista 'modalAdministracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos', $data);
    }

    public function modalMenu()
    {
        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $menuId = $this->request->getPost('menuId');
            $modulo = new conf_menus();
            $data['campos'] = $modulo->select('menuId,menu,iconoMenu, urlMenu')->where('flgElimina', 0)->where('menuId', $menuId)->first();
        } else {
            $data['campos'] = [
                'menuId'      => 0,
                'menu'        => '',
                'iconoMenu'   => '', 
                'urlMenu'     => ''
            ];
        }
        $data['operacion'] = $operacion;
        // Cargar la vista 'modalAdministracionMenus.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionMenus', $data);
    }

    public function AdministracionMenus()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionMenus');
    }

    public function AdministracionPermisos()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionPermisos');
    }

    public function modalModuloOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $model = new conf_modulos();

        $data = [
            'modulo'            => $this->request->getPost('modulo'),
            'iconoModulo'       => $this->request->getPost('iconoModulo'),
            'urlModulo'         => $this->request->getPost('urlModulo')
            
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];

        if($operacion == 'editar') {
            $operacionModulo = $model->update($this->request->getPost('moduloId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionModulo = $model->insert($data);
        }
        if ($operacionModulo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Modulo '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'moduloId' => ($operacion == 'editar' ? $this->request->getPost('moduloId') : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el empleado'
            ]);
        }
    }

    public function modalMenuOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $model = new conf_menus();
        $modelModulo = new conf_modulos();

        $data = [
            'menu'            => $this->request->getPost('menu'),
            'iconoMenu'       => $this->request->getPost('iconoMenu'),
            'urlMenu'         => $this->request->getPost('urlMenu')
            
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];

        if($operacion == 'editar') {
            $operacionMenu = $model->update($this->request->getPost('menuId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionMenu = $model->insert($data);
        }
        if ($operacionMenu) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Menu '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'menuId' => ($operacion == 'editar' ? $this->request->getPost('menuId') : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el menu'
            ]);
        }
    }
    public function mostrarDatos()
    {
        // Aquí obtienes los datos de tu base de datos, ya sea usando un modelo o directamente
        $Modulos = new conf_modulos(); // Ajusta el nombre del modelo según sea necesario
        $datos = $Modulos->findAll(); // Esto es un ejemplo, ajusta según tu situación
    
        // Pasar los datos a la vista
        return view('administracionModulos', ['datos' => $datos]);

        // Aquí obtienes los datos de tu base de datos, ya sea usando un modelo o directamente
        $Menus = new conf_menus(); // Ajusta el nombre del modelo según sea necesario
        $datos = $Menus->findAll(); // Esto es un ejemplo, ajusta según tu situación
    
        // Pasar los datos a la vista
        return view('pageMenuModulos', ['datos' => $datos]);

    }
    
    public function menusModulos($moduloId, $modulo)
    {
        $data["moduloId"] = $moduloId;
        $data["modulo"] = $modulo;
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/pageMenusModulos', $data);
    }

    public function menusMenus($menuId, $menu)
    {
        $data["menuId"] = $menuId;
        $data["menu"] = $menu;
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/pageMenusModulos', $data);
    }

    public function eliminarModulo(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $eliminarModulo = new conf_modulos();
        
        $moduloId = $this->request->getPost('moduloId');
        $data = ['flgElimina' => 1];
        
        $eliminarModulo->update($moduloId, $data);

        if($eliminarModulo) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Módulo eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el módulo'
            ]);
        }
    }

    public function mostrarDatosMenu()
    {
        // Aquí obtienes los datos de tu base de datos, ya sea usando un modelo o directamente
        $Modulos = new conf_menus(); // Ajusta el nombre del modelo según sea necesario
        $datos = $Modulos->findAll(); // Esto es un ejemplo, ajusta según tu situación
    
        // Pasar los datos a la vista
        return view('modalAdministracionMenus', ['datos' => $datos]);
    }

}
