<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_modulos;


class AdministracionPermisos extends Controller
{
    public function configuracionModulos()
    {
        $mostrarModulos = new conf_modulos();

        $data['modulos'] = $mostrarModulos
        ->select('conf_modulos.*')
        ->where('conf_modulos.flgElimina', 0)
        ->findAll();
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionModulos', $data);
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
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos', $data);
    }

    public function modalnuevoMenu()
    {
        $data["moduloId"] = $this->request->getPost("moduloId");
        // Cargar la vista 'administracionMenus.php' desde la carpeta 'Views/configuracion-general/vistas'
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
    public function mostrarDatos()
    {
        // Aquí obtienes los datos de tu base de datos, ya sea usando un modelo o directamente
        $Modulos = new conf_modulos(); // Ajusta el nombre del modelo según sea necesario
        $datos = $Modulos->findAll(); // Esto es un ejemplo, ajusta según tu situación
    
        // Pasar los datos a la vista
        return view('administracionModulos', ['datos' => $datos]);
    }
    
    public function menusModulos($moduloId, $modulo)
    {
        $data["moduloId"] = $moduloId;
        $data["modulo"] = $modulo;
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/pageMenusModulos', $data);
    }

    public function insertUsuariosSucursal(){
        $asignarSucursal = new conf_modulos_usuarios();

         $data = [
            'sucursalId'     => $this->request->getPost('selectmodulos'),
            'moduloId'      => $this->request->getPost('moduloId')
        ];
        // Insertar datos en la base de datos
        $insertAsignarSucursal = $asignarSucursal->insert($data);
        if ($insertAsignarSucursal) {
                
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Sucursal asignada correctamente'
            ]);

        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo asignar la sucursal al empleado'
            ]);
        }
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

    public function insertarNuevoMenu()
    {
        $model = new conf_menus();
        $modelModulo = new conf_modulos(); 

        $menu = $this->request->getPost('menu');
        $iconoModulo = $this->request->getPost('iconoMenu');
        $urlModulo = $this->request->getPost('urlMenu');
            
        $data = [
            'menu'            => $this->request->getPost('menu'),
            'moduloId'        => $this->request->getPost('menu'),
            'iconoMenu'       => $this->request->getPost('iconoMenu'),
            'urlMenu'         => $this->request->getPost('urlMenu')
            
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        // Insertar datos en la base de datos
        $insertMenu = $model->insert($data);
        if ($insertMenu) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Menú Agregado correctamente',
                'menuId' => $model->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el menú'
            ]);
        }
    }

}
