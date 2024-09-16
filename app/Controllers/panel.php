<?php 
namespace App\Controllers;
use App\Models\UsuarioLogin;
use App\Models\conf_usuarios;
use App\Models\conf_roles_permisos;
use App\Models\comp_proveedores;
use App\Models\fel_clientes;
use App\Models\fel_reservas;
use App\Models\fel_reservas_detalle;
use App\Models\fel_facturas;
use App\Models\fel_facturas_detalle;

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
        $modelProveedores = new comp_proveedores();
        $modelClientes = new fel_clientes();
        $modelReservas = new fel_reservas();
        $modelReservasDetalle = new fel_reservas_detalle();
        $modelFacturas = new fel_facturas();
        $modelFacturasDetalle = new fel_facturas_detalle();

        $fechaActual = date('Y-m-d');

        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        $fechaInicioMes = date('Y-m-01');
        $fechaFinMes = date('Y-m-t'); 

        $nombreMes = $meses[date('n')];

        $camposSession = [
            'renderVista' => 'No'
        ];

        $session->set([
            'route'             => 'escritorio/dashboard',
            'camposSession'     => json_encode($camposSession)
        ]);

        $totalProveedores = $modelProveedores
            ->where('flgElimina', 0)
            ->where('estadoProveedor', 'Activo')
            ->countAllResults();

        $totalClientes = $modelClientes
            ->where('flgElimina', 0)
            ->where('estadoCliente', 'Activo')
            ->countAllResults();

        $reservasPendientes = $modelReservas
            ->where('flgElimina', 0)
            ->where('estadoReserva', 'Pendiente')
            ->countAllResults();

        $facturasPendientes = $modelFacturas
            ->where('flgElimina', 0)
            ->where('estadoFactura', 'Pendiente')
            ->countAllResults();

        $montoReservasDia = $modelReservas
            ->select('SUM(fel_reservas_detalle.totalReservaDetalleIVA) as montoReservadoIVA, SUM(fel_reservas_detalle.totalReservaDetalle) as montoReservado')
            ->join('fel_reservas_detalle', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
            ->where('fel_reservas.fechaReserva', $fechaActual)
            ->where('fel_reservas.flgElimina', 0)
            ->where('fel_reservas_detalle.flgElimina', 0)
            ->first();

        $reservasHoy = $montoReservasDia['montoReservado'] ?? 0;
        $reservasHoyIVA = $montoReservasDia['montoReservadoIVA'] ?? 0;

        $montoReservasMes = $modelReservas
            ->select('SUM(fel_reservas_detalle.totalReservaDetalleIVA) as montoReservadoIVA, SUM(fel_reservas_detalle.totalReservaDetalle) as montoReservado')
            ->join('fel_reservas_detalle', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
            ->where('fel_reservas.fechaReserva >=', $fechaInicioMes)
            ->where('fel_reservas.fechaReserva <=', $fechaFinMes)
            ->where('fel_reservas.flgElimina', 0)
            ->where('fel_reservas_detalle.flgElimina', 0)
            ->first();

        $reservasMes = $montoReservasMes['montoReservado'] ?? 0;
        $reservasMesIVA = $montoReservasMes['montoReservadoIVA'] ?? 0;

        $montoFacturasDia = $modelFacturas
            ->select('SUM(fel_facturas_detalle.totalDetalle) as montoVenta, SUM(fel_facturas_detalle.totalDetalleIVA) as montoVentaIVA')
            ->join('fel_facturas_detalle', 'fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->where('fel_facturas.fechaEmision', $fechaActual)
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->first();

        $ventasHoy = $montoFacturasDia['montoVenta'] ?? 0;
        $ventasHoyIVA = $montoFacturasDia['montoVentaIVA'] ?? 0;

        $montoFacturasMes = $modelFacturas
            ->select('SUM(fel_facturas_detalle.totalDetalle) as montoVenta, SUM(fel_facturas_detalle.totalDetalleIVA) as montoVentaIVA')
            ->join('fel_facturas_detalle', 'fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->where('fel_facturas.fechaEmision >=', $fechaInicioMes)
            ->where('fel_facturas.fechaEmision <=', $fechaFinMes)
            ->where('fel_facturas.estadoFactura', 'Certificado')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->first();

        $ventasMes = $montoFacturasMes['montoVenta'] ?? 0;
        $ventasMesIVA = $montoFacturasMes['montoVentaIVA'] ?? 0;

        $productoMasReservadoUnidades = $modelReservasDetalle
            ->select('inv_productos.producto, SUM(fel_reservas_detalle.cantidadProducto) as totalCantidad')
            ->join('inv_productos', 'inv_productos.productoId = fel_reservas_detalle.productoId')
            ->join('fel_reservas', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
            ->where('fel_reservas_detalle.flgElimina', 0)
            ->where('fel_reservas.flgElimina', 0)
            ->groupBy('fel_reservas_detalle.productoId')
            ->orderBy('totalCantidad', 'DESC')
            ->first();

        if (!$productoMasReservadoUnidades) {
            $productoMasReservadoUnidadesNombre = "-";
            $productoMasReservadoUnidadesCantidad = "";
        } else {
            $productoMasReservadoUnidadesNombre = $productoMasReservadoUnidades['producto'];
            $productoMasReservadoUnidadesCantidad = $productoMasReservadoUnidades['totalCantidad'];
        }

        $productoMasVendidoUnidades = $modelFacturasDetalle
            ->select('inv_productos.producto, SUM(fel_facturas_detalle.cantidadProducto) as totalCantidad')
            ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
            ->join('fel_facturas', 'fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->where('fel_facturas.estadoFactura', 'Certificado')
            ->groupBy('fel_facturas_detalle.productoId')
            ->orderBy('totalCantidad', 'DESC')
            ->first();

        if (!$productoMasVendidoUnidades) {
            $productoMasVendidoUnidadesNombre = "-";
            $productoMasVendidoUnidadesCantidad = "";
        } else {
            $productoMasVendidoUnidadesNombre = $productoMasVendidoUnidades['producto'];
            $productoMasVendidoUnidadesCantidad = $productoMasVendidoUnidades['totalCantidad'];
        }

        $productoMasReservadoMonto = $modelReservasDetalle
            ->select('inv_productos.producto, SUM(fel_reservas_detalle.totalReservaDetalle) as totalMonto, SUM(fel_reservas_detalle.totalReservaDetalleIVA) as totalMontoIVA')
            ->join('inv_productos', 'inv_productos.productoId = fel_reservas_detalle.productoId')
            ->join('fel_reservas', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
            ->where('fel_reservas_detalle.flgElimina', 0)
            ->where('fel_reservas.flgElimina', 0)
            ->groupBy('fel_reservas_detalle.productoId')
            ->orderBy('totalMontoIVA', 'DESC')
            ->first();

        if (!$productoMasReservadoMonto) {
            $productoMasReservadoMontoNombre = "-";
            $productoMasReservadoMontoCantidad = "";
            $productoMasReservadoMontoCantidadIVA = "";
        } else {
            $productoMasReservadoMontoNombre = $productoMasReservadoMonto['producto'];
            $productoMasReservadoMontoCantidad = $productoMasReservadoMonto['totalMonto'];
            $productoMasReservadoMontoCantidadIVA = $productoMasReservadoMonto['totalMontoIVA'];
        }

        $productoMasVendidoMonto = $modelFacturasDetalle
            ->select('inv_productos.producto, SUM(fel_facturas_detalle.totalDetalle) as totalMonto, SUM(fel_facturas_detalle.totalDetalleIVA) as totalMontoIVA')
            ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
            ->join('fel_facturas', 'fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->where('fel_facturas.estadoFactura', 'Certificado')
            ->groupBy('fel_facturas_detalle.productoId')
            ->orderBy('totalMontoIVA', 'DESC')
            ->first();

        if (!$productoMasVendidoMonto) {
            $productoMasVendidoMontoNombre = "-";
            $productoMasVendidoMontoCantidad = "";
        } else {
            $productoMasVendidoMontoNombre = $productoMasVendidoMonto['producto'];
             $productoMasVendidoMontoCantidad = $productoMasVendidoMonto['totalMonto'];
            $productoMasVendidoMontoCantidadIVA = $productoMasVendidoMonto['totalMontoIVA'];
        }

        $data = [
            'totalProveedores' => $totalProveedores,
            'totalClientes' => $totalClientes,
            'reservasPendientes' => $reservasPendientes,
            'facturasPendientes' => $facturasPendientes,
            'reservasHoy' => $reservasHoy,
            'reservasHoyIVA' => $reservasHoyIVA,
            'nombreMes' => ucfirst($nombreMes),
            'reservasMes' => $reservasMes,
            'reservasMesIVA' => $reservasMesIVA,
            'ventasHoy' => $ventasHoy,
            'ventasHoyIVA' => $ventasHoyIVA,
            'ventasMes' => $ventasMes,
            'ventasMesIVA' => $ventasMesIVA,
            'productoMasReservadoUnidadesNombre' => $productoMasReservadoUnidadesNombre,
            'productoMasReservadoUnidadesCantidad' => $productoMasReservadoUnidadesCantidad,
            'productoMasVendidoUnidadesNombre' => $productoMasVendidoUnidadesNombre,
            'productoMasVendidoUnidadesCantidad' => $productoMasVendidoUnidadesCantidad,
            'productoMasReservadoMontoNombre' => $productoMasReservadoMontoNombre,
            'productoMasReservadoMontoCantidad' => $productoMasReservadoMontoCantidad,
            'productoMasReservadoMontoCantidadIVA' => $productoMasReservadoMontoCantidadIVA,
            'productoMasVendidoMontoNombre' => $productoMasVendidoMontoNombre,
            'productoMasVendidoMontoCantidad' => $productoMasVendidoMontoCantidad,
            'productoMasVendidoMontoCantidadIVA' => $productoMasVendidoMontoCantidadIVA,
        ];

        return view('Panel/escritorio', $data);
    }
}

?>
