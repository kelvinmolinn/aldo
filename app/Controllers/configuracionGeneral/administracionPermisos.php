<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_modulos;
use App\Models\conf_menus;

class AdministracionPermisos extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function configuracionModulos()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;
            // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
            return view('configuracion-general/vistas/administracionModulos', $data);
        }
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

        $directorio = APPPATH . 'Views';
        $data['directorio'] = $directorio;
        $carpetas = [];

        // Obtener el contenido del directorio
        $contenido = scandir($directorio);
    
        // Iterar sobre el contenido
        foreach ($contenido as $item) {
            // Ignorar los directorios especiales (., ..)
            if ($item !== '.' && $item !== '..') {
                // Verificar si es un directorio
                if (is_dir($directorio . '/' . $item)) {
                    // Agregar a la lista de carpetas
                    $carpetas[] = $item;
                }
            }
        }
        $data['carpetas'] = $carpetas;
        // Cargar la vista 'modalAdministracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos', $data);
    }

    public function modalMenu()
    {
        $operacion = $this->request->getPost('operacion');
        
        $data['operacion'] = $operacion;
        $data['moduloId'] = $this->request->getPost('moduloId');
        $data['menuId'] = $this->request->getPost('menuId');

        $modelModulo = new conf_modulos();

        $infoCarpeta =  $modelModulo->select('urlModulo')->where('flgElimina', 0)->where('moduloId', $data['moduloId'])->first();

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

        $directorio = APPPATH . 'Views/'.$infoCarpeta['urlModulo'].($infoCarpeta['urlModulo'] == "Panel" ? '' : '/vistas');
        $archivos = [];
    
        // Obtener el contenido del directorio
        $contenido = scandir($directorio);
        
        // Iterar sobre el contenido
        foreach ($contenido as $item) {
            // Ignorar los directorios especiales (., ..)
            if ($item !== '.' && $item !== '..') {
                // Verificar si es un archivo
                if (is_file($directorio . '/' . $item)) {
                    if(substr($item, 0, 4) != "page") {
                        // Agregar a la lista de archivos
                        $archivos[] = $item;
                    }
                }
            }
        }
        $data['archivos'] = $archivos;

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
            'moduloId'        => $this->request->getPost('moduloId'),
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
        return view('pageMenusModulos', ['datos' => $datos]);

    }
    
    public function menusModulos($moduloId, $modulo)
    {
        $data["moduloId"] = $moduloId;
        $data["modulo"] = $modulo;

        $Menus = new conf_menus(); // Ajusta el nombre del modelo según sea necesario
        $data['menus'] = $Menus->where('moduloId',$moduloId)
                               ->where('flgElimina', 0)
                               ->findAll();
         // Esto es un ejemplo, ajusta según tu situación
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

    public function eliminarMenu(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $eliminarMenu = new conf_menus();
        
        $menuId = $this->request->getPost('menuId');
        $data = ['flgElimina' => 1];
        
        $eliminarMenu->update($menuId, $data);

        if($eliminarMenu) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Menu eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el Menu'
            ]);
        }
    }
    public function tablaModulos()
    {
        $mostrarModulos = new conf_modulos();
        $datos = $mostrarModulos
        ->select('moduloId, modulo, iconoModulo, urlModulo')
        ->where('flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Módulo: </b>" . $columna['modulo'];
            $columna3 = "<b>Url: </b>" . $columna['urlModulo'];
            // Aquí puedes construir tus botones en la última columna
            $columna4 = '
                <button class="btn btn-primary mb-1" onclick="modalModulo(`'.$columna['moduloId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            
            $columna4 .= '
                <a href="'.site_url('conf-general/admin-modulos/vista/modulos/menus/' . $columna['moduloId'] . '/' . $columna['modulo']).'" class="btn btn-secondary mb-1" data-toggle="tooltip" data-placement="top" title="Menús">
                    <i class="fas fa-bars nav-icon"></i>
                </a>
            ';

            $columna4 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarModulo(`'.$columna['moduloId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function tablaModulosMenus()
    {
        $datos["moduloId"] = $this->request->getPost('moduloId');
        $datos["modulo"] = $this->request->getPost('modulo');

        $Menus = new conf_menus(); // Ajusta el nombre del modelo según sea necesario
        $datos['menus'] = $Menus
        ->select('menuId, moduloId, menu, iconoMenu, urlMenu')
        ->where('moduloId',$datos["moduloId"])
        ->where('flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos['menus'] as $columna) {

            $menuId = $columna['menuId'];
            $menu = $columna['menu'];
            $iconoMenu = $columna['iconoMenu'];
            $urlMenu = $columna['urlMenu']; 

            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Menu: </b>" . $columna['menu'];
            $columna3 = "<b>Url: </b>" . $columna['urlMenu'];
            // Aquí puedes construir tus botones en la última columna
            $columna4 = '
                <button class="btn btn-primary mb-1" onclick="modalMenu(`'.$columna['moduloId'].'`, `'.$columna['menuId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';

            $columna4 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarMenu(`'.$columna['menuId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
    
}