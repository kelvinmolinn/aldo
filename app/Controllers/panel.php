<?php 
namespace App\Controllers;
use App\Models\UsuarioLogin;
use App\Models\conf_usuarios;
use App\Models\conf_roles_permisos;

class Panel extends BaseController{
    public function index(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {            
            $data['renderVista'] = $this->request->getPost("renderVista");

            if($data['renderVista'] == "") {
                $data['renderVista'] = "Sí";
            } else {
                $data['renderVista'] = "No";
            }

            $data['route'] = $session->get('route');
            $data['tituloVentana'] = $session->get('tituloVentana');
            $data['campos'] = $session->get('camposSession');
            $data['defaultPass'] = $session->get('defaultPass');

            if($data['defaultPass'] == "Propia") {
                $rolesPermisos = new conf_roles_permisos();
                
                $modulosUsuario = $rolesPermisos->select('mo.moduloId AS moduloId, mo.modulo AS modulo, mo.iconoModulo AS iconoModulo')
                ->join('conf_menu_permisos mp', 'mp.menuPermisoId = conf_roles_permisos.menuPermisoId')
                ->join('conf_menus m', 'm.menuId = mp.menuId')
                ->join('conf_modulos mo', 'mo.moduloId = m.moduloId')
                ->where('conf_roles_permisos.rolId', $session->get('rolId'))
                ->where('conf_roles_permisos.flgElimina', 0)
                ->groupBy('mo.moduloId')
                ->orderBy('mo.moduloId')
                ->findAll();

                // Formar la sidebard
                $menuHTML = '
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                ';

                $m = 0;
                foreach ($modulosUsuario as $modulo) {
                    $m++;

                    if($m == 1) {
                        $menuHTML .= '
                            <li class="nav-item">
                                <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz(`escritorio/dashboard`, {renderVista: `No`});">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>INICIO</p>
                                </a>
                            </li>
                        ';
                    }

                    $menuHTML .= '
                        <li class="nav-item">
                            <a href="#" class="nav-link nav-color">
                                <i class="nav-icon '.$modulo["iconoModulo"].'"></i>
                                <p>
                                    '.$modulo["modulo"].'
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                    ';

                    $menusUsuario = $rolesPermisos->select('m.menu AS menu, m.iconoMenu AS iconoMenu, m.urlMenu AS urlMenu')
                    ->join('conf_menu_permisos mp', 'mp.menuPermisoId = conf_roles_permisos.menuPermisoId')
                    ->join('conf_menus m', 'm.menuId = mp.menuId')
                    ->where('conf_roles_permisos.rolId', session()->get('rolId'))
                    ->where('conf_roles_permisos.flgElimina', 0)
                    ->where('m.moduloId', $modulo['moduloId'])
                    ->groupBy('mp.menuId')
                    ->orderBy('m.menuId')
                    ->findAll();

                    $me = 0;
                    foreach($menusUsuario as $menu) {
                        $me++;

                        // Dibujar html del subarbol
                        if($me == 1) {
                            $menuHTML .= '<ul class="nav nav-treeview ml-3">';
                        }

                        $menuHTML .= '
                            <li class="nav-item">
                                <a role="button" class="nav-link nav-color" onclick="cambiarInterfaz(`'.$menu['urlMenu'].'`, {renderVista: `No`});">
                                    <i class="'.$menu['iconoMenu'].'"></i>
                                    <p>'.$menu['menu'].'</p>
                                </a>
                            </li>
                        ';
                    }

                    // Si se dibujo subarbol cerrarlo
                    if($me > 0) {
                        $menuHTML .= '</ul>';
                    }

                    // Cerrar el arbol principal
                    $menuHTML .= '</li>';
                }

                // Agregar el cerrar sesion
                $menuHTML .= '
                            <li class="nav-item">
                                <a href="#" onclick="cerrarSession();" class="nav-link nav-cerrar-sesion">
                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                    <p>Cerrar Sesión</p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                ';

                // Setear la variable que se dibujara en la vista
                $data['menuUsuario'] = $menuHTML;

                // Consultar y preparar la variable que controla los permisos
                $permisosUsuario = array();

                $dataPermisos = $rolesPermisos->select('conf_roles_permisos.menuPermisoId AS menuPermisoId')
                ->join('conf_menu_permisos mp', 'mp.menuPermisoId = conf_roles_permisos.menuPermisoId')
                ->where('conf_roles_permisos.rolId', $session->get('rolId'))
                ->where('conf_roles_permisos.flgElimina', 0)
                ->where('mp.flgElimina', 0)
                ->findAll();

                foreach($dataPermisos as $permiso) {
                    $permisosUsuario[] = $permiso['menuPermisoId'];
                }

                $session->set(["permisosUsuario" => $permisosUsuario]);
            } else {
                // No se muestra ningun menu, solo el cerrar sesion porque no ha cambiado la password
                $data['menuUsuario'] = '
                  <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                      <li class="nav-item">
                        <a href="#" onclick="cerrarSession();" class="nav-link">
                          <i class="fas fa-sign-out-alt text-danger"></i>
                          <p>Cerrar Sesión</p>
                        </a>
                      </li>
                    </ul>
                  </nav>
                ';

                $session->set(["permisosUsuario" => [0,0]]);
            }

            if($session->get('defaultPass') == "Default") {
                return view('Panel/defaultPassword', $data);
            } else {
                return view('Panel/app', $data);
            }
        }
    }

    public function cambiarClave() {
        $session = session();
        $modelUsuario = new conf_usuarios();

        $usuarioId = $session->get('usuarioId');
        $nuevaClave = $this->request->getPost("nuevaClave");
        $confirmarClave = $this->request->getPost("confirmarClave");

        if($nuevaClave == $confirmarClave) {
            $dataUsuarios = [
                'clave'             => password_hash($nuevaClave, PASSWORD_DEFAULT)
            ];
            $insertUsuario = $modelUsuario->update($usuarioId, $dataUsuarios);

            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Contraseña actualizada con éxito'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
        }

    }

    public function escritorio(){        
        $session = session();
    
        $usuario = new UsuarioLogin();

        $camposSession = [
            'renderVista' => 'No'
        ];

        // Programar aquí el html de las cards o info resumida para el escritorio

        $session->set([
            'route'             => 'escritorio/dashboard',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('Panel/escritorio');
    }
}

?>
