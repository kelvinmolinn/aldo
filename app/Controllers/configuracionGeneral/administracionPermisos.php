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
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionModulos', $data);
    }
    public function modalnuevoModulo()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos');
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

    public function insertarNuevoModulo()
    {
        $model = new conf_modulos();

        $modulo = $this->request->getPost('modulo');
        $iconoModulo = $this->request->getPost('iconoModulo');
        $urlModulo = $this->request->getPost('urlModulo');
            
        $data = [
            'modulo'            => $this->request->getPost('modulo'),
            'iconoModulo'       => $this->request->getPost('iconoModulo'),
            'urlModulo'         => $this->request->getPost('urlModulo')
            
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        // Insertar datos en la base de datos
        $insertModulo = $model->insert($data);
        if ($insertModulo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Modulo Agregado correctamente',
                'moduloId' => $model->insertID()
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

    public function editarModulo(){
        $data['moduloId'] = $this->request->getPost('moduloId');
        $data['modulo'] = $this->request->getPost('modulo');
        $data['iconoModulo'] = $this->request->getPost('iconoModulo');
        $data['urlModulo'] = $this->request->getPost('urlModulo');


        return view('configuracion-general/modals/modalEditarModulos', $data);
    }
    public function modalEditarModulo(){
        
        $modulo = new conf_modulos();
        $data['modulo'] = $modulo->where('flgElimina', 0)->findAll();
        $data['moduloId'] = $this->request->getPost('moduloId');
        $data['iconoModulo'] = $this->request->getPost('iconoModulo');
        $data['urlModulo'] = $this->request->getPost('urlModulo');

        return view('configuracion-general/modals/modalEditarModulos',$data);

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

    public function insertModuloMenu(){
        $asignarMenu = new conf_menus();

         $data = [
            'menuId'     => $this->request->getPost('menuId'),
            'moduloId'      => $this->request->getPost('moduloId')
        ];
        // Insertar datos en la base de datos
        $insertAsignarMenu = $asignarMenu->insert($data);
        if ($insertAsignarMenu) {
                
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

    public function mostrarDatosMenu()
    {
        // Aquí obtienes los datos de tu base de datos, ya sea usando un modelo o directamente
        $Modulos = new conf_menus(); // Ajusta el nombre del modelo según sea necesario
        $datos = $Modulos->findAll(); // Esto es un ejemplo, ajusta según tu situación
    
        // Pasar los datos a la vista
        return view('modalAdministracionMenus', ['datos' => $datos]);
    }

}
