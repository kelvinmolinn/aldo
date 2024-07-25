<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;
use App\Models\inv_productos;
use App\Models\inv_productos_existencias;
use App\Models\cat_14_unidades_medida;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos_plataforma;
use App\Models\conf_sucursales;
use App\Models\inv_kardex;
use App\Models\log_productos_precios;
use App\Models\conf_parametrizaciones;
use App\Models\fel_reservas;
use App\Models\fel_reservas_detalle;
use App\Models\fel_reservas_pago;
use App\Models\fel_clientes;
use App\Models\vista_usuarios_empleados;
use App\Models\comp_compras_detalle;
use App\Models\cat_17_forma_pago;


use App\Models\comp_proveedores;

class administracionReservas extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'ventas/admin-reservas/index',
                'camposSession'     => json_encode($camposSession)
            ]);
        return view('ventas/vistas/reservas', $data);
        }
    }

    public function modalNuevaReserva()
    { 
               // Cargar el modelos
            $sucursalesModel = new conf_sucursales();
            $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
            $clientesModel = new fel_clientes();
            $data['clientes'] = $clientesModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['sucursalId'] = $this->request->getPost('sucursalId');
            $data['clienteId'] = $this->request->getPost('clienteId');
    
            if($operacion == 'editar') {
                $reservaId = $this->request->getPost('reservaId');
                $ReservaProducto = new fel_reservas();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $producto->select('fel_reservas.reservaId,fel_reservas.fechaReserva,fel_reservas.comentarioReserva,fel_reservas.estadoReserva,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente')
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_reservas.sucursalId')
                ->join('fel_clientes', 'fel_clientes.clienteId = fel_reservas.clienteId')
                ->where('fel_reservas.flgElimina', 0)
                ->where('fel_reservas.reservaId', $reservaId)->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'reservaId'              => 0,
                    'sucursalId'             => '',
                    'clienteId'              => '',
                    'fechaReserva'           => '',
                    'comentarioReserva'      => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('ventas/modals/modalNuevaReserva', $data);
        }

        public function modalReservaOperacion()
        {
            // Continuar con la operación de inserción o actualización en la base de datos
            $operacion = $this->request->getPost('operacion');
            $reservaId = $this->request->getPost('reservaId');
            $model = new fel_reservas();
            $modelParametrizaciones = new conf_parametrizaciones();

            $porcentajeIVA = $modelParametrizaciones->select('valorParametrizacion')
            ->where('flgElimina', 0)
            ->where('parametrizacionId', 1)
            ->first();
    
            $data = [
                'sucursalId'           => $this->request->getPost('sucursalId'),
                'fechaReserva'         => $this->request->getPost('fechaReserva'),
                'clienteId'            => $this->request->getPost('clienteId'),
                'comentarioReserva'    => $this->request->getPost('comentarioReserva'),
                'porcentajeIva'        => $porcentajeIVA,
                'estadoReserva'        => "Pendiente",
            ];
        
            if ($operacion == 'editar') {
                $operacionReserva = $model->update($this->request->getPost('reservaId'), $data);
            } else {
                // Insertar datos en la base de datos
                $operacionReserva = $model->insert($data);
            }
        
            if ($operacionReserva) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva ' . ($operacion == 'editar' ? 'actualizada' : 'agregada') . ' correctamente',
                    'reservaId' => ($operacion == 'editar' ? $this->request->getPost('reservaId') : $model->insertID())
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar la reserva'
                ]);
            }
          
        }


public function tablaReservas()
{
    $reservaId = $this->request->getPost('reservaId');
    $mostrarReserva = new fel_reservas();
    $vistaUsuariosEmpleados = new vista_usuarios_empleados();
    $usuarioIdAgrega = $this->request->getPost("usuarioId");
    $datos = $mostrarReserva
        ->select('fel_reservas.reservaId,fel_reservas.fechaReserva,fel_reservas.fechaAnulacionReserva,fel_reservas.obsAnulacionReserva,fel_reservas.comentarioReserva,fel_reservas.estadoReserva,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_reservas.sucursalId')
        ->join('fel_clientes', 'fel_clientes.clienteId = fel_reservas.clienteId')
        ->where('fel_reservas.flgElimina', 0)
         ->orderBy('fel_reservas.reservaId', 'DESC') // Ordenar por ID en orden descendente
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas
    foreach ($datos as $columna) {
        // Determina la clase Bootstrap basada en el estado del descargo
        $estadoClase = '';
        if ($columna['estadoReserva'] === 'Pendiente') {
            $estadoClase = 'badge badge-secondary';
        } elseif ($columna['estadoReserva'] === 'Finalizado') {
            $estadoClase = 'badge badge-success';
        } elseif ($columna['estadoReserva'] === 'Anulado') {
            $estadoClase = 'badge badge-danger';
        }

        // Construir columna 2
        $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Cliente:</b> " . $columna['cliente'];

        // Construir columna 3
        $columna3 = "<b>Fecha:</b> " . $columna['fechaReserva'];

        // Construir columna 4 basada en el estadoReserva
        if ($columna['estadoReserva'] === 'Anulado') {
            $columna4 = 
                        "<b>Fecha de Anulación:</b> " . $columna['fechaAnulacionReserva'] . "<br>" .
                        "<b>Motivo/Justificación</b> " . $columna['obsAnulacionReserva'] . "<br>" .
                        "<b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoReserva'] . "</span>";
        } else {
            $columna4 = "<b>Observación:</b> " . $columna['comentarioReserva'] . "<br>" .
                        "<b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoReserva'] . "</span>";
        }

        // Construir botones basado en estadoReserva
        if ($columna['estadoReserva'] === 'Pendiente') {
            $jsonActualizarReserva = [
                "reservaId" => $columna['reservaId']
            ];
            $columna5 = '
                <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-reservas/vista/actualizar/reserva`, ' . htmlspecialchars(json_encode($jsonActualizarReserva)) . ');" data-toggle="tooltip" data-placement="top" title="Continuar reserva">
                    <i class="fas fa-sync-alt"></i> <span> </span>
                </button>

                <button class="btn btn-info mb-1" onclick="modalVerReserva(`' . $columna['reservaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver reserva">
                    <i class="fas fa-eye"></i><span> </span>
                </button>

                <button class="btn btn-danger mb-1" onclick="modalAnularReserva(' . $columna['reservaId'] . ')" data-toggle="tooltip" data-placement="top" title="Anular">
                    <i class="fas fa-ban"></i>
                </button>
            ';
        } elseif ($columna['estadoReserva'] === 'Finalizado') {
            $columna5 = '

                <button class="btn btn-primary mb-1" onclick="modalFacturar(`' . $columna['reservaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Facturar">
                    <i class="fas fa-hand-holding-usd"></i><span> </span>
                </button>

                <button class="btn btn-info mb-1" onclick="modalVerReserva(`' . $columna['reservaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver reserva">
                    <i class="fas fa-eye"></i><span> </span>
                </button>

                <button class="btn btn-danger mb-1" onclick="modalAnularReserva(' . $columna['reservaId'] . ')" data-toggle="tooltip" data-placement="top" title="Anular">
                    <i class="fas fa-ban"></i>
                </button>

            ';
        } else {
            $columna5 = '
                <button class="btn btn-info mb-1" onclick="modalVerReserva(`' . $columna['reservaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver reserva">
                    <i class="fas fa-eye"></i><span> </span>
                </button>
            ';
        }

        // Agrega la fila al array de salida
        $output['data'][] = array(
            $n,
            $columna2,
            $columna3,
            $columna4,
            $columna5
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


    public function vistaContinuarReserva(){

        $session = session();
        $reservaId = $this->request->getPost('reservaId');

        $camposSession = [
            'renderVista' => 'No',
            'reservaId'    => $reservaId
        ];
        $session->set([
            'route'             => 'ventas/admin-reservas/vista/continuar/reserva',
            'camposSession'     => json_encode($camposSession)
        ]);

        $data['reservaId'] = $reservaId;
        $mostrarSalida = new fel_reservas();
    
                $sucursales = new conf_sucursales;
                $clientes = new fel_clientes;

                $data['sucursales'] = $sucursales
                ->select("sucursalId,sucursal")
                ->where("flgElimina", 0)
                ->findAll();

                $data['clientes'] = $clientes
                ->select("clienteId,cliente")
                ->where("flgElimina", 0)
                ->findAll();

        // Consulta para traer los valores de los input que se pueden actualizar
        $consultaTraslados = $mostrarSalida
        ->select('fel_reservas.reservaId,fel_reservas.fechaReserva,fel_reservas.comentarioReserva,fel_reservas.estadoReserva,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_reservas.sucursalId')
        ->join('fel_clientes', 'fel_clientes.clienteId = fel_reservas.clienteId')
        ->where('fel_reservas.flgElimina', 0)
        ->where("fel_reservas.reservaId", $reservaId)
        ->first(); 

        $data['campos'] = [
        'sucursalId'             => $consultaTraslados['sucursalId'],
        'clienteId'              => $consultaTraslados['clienteId'],
        'fechaReserva'           => $consultaTraslados['fechaReserva'],
        'comentarioReserva'      => $consultaTraslados['comentarioReserva']
        // Sacar estos valores de la consulta
        ]; 
        return view('ventas/vistas/pageContinuarReserva', $data);       
        
    }


    function vistaActualizarReservaOperacion(){
        $reservas = new fel_reservas;
        $reservaId = $this->request->getPost('reservaId');
            // Verificar si hay productos agregados al traslado
            $reservaDetalleModel = new fel_reservas_detalle();
            $productosAgregados = $reservaDetalleModel
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->countAllResults();
        
        if ($productosAgregados > 0) {
            // Mostrar mensaje de error si hay productos agregados
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede actualizar porque ya se han agregado productos a la reserva.'
            ]);
        }

        $data = [
            'sucursalId'            => $this->request->getPost('sucursalId'),
            'clienteId'             => $this->request->getPost('clienteId'),
            'fechaReserva'          => $this->request->getPost('fechaReserva'),
            'comentarioReserva'     => $this->request->getPost('comentarioReserva')
        ];

            // Insertar datos en la base de datos
            $operacionReserva = $reservas->update($this->request->getPost('reservaId'), $data);

        if ($operacionReserva) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Reserva actualizada correctamente',
                'reservaId' => $this->request->getPost('reservaId')
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo actualizar la reserva'
            ]);
        }
    }

    public function modalNuevoProductoReserva()
    {

        // Cargar el modelos
        $productosModel = new inv_productos();
        $data['producto'] = $productosModel->where('flgElimina', 0)->findAll();
        $operacion = $this->request->getPost('operacion');
        $data['productoId'] = $this->request->getPost('productoId');
        $reservaId = $this->request->getPost('reservaId');
        $productoId = $this->request->getPost('productoId');

        // Consulta para traer el 13% de la parametrizacion
        $porcentajeIva = new conf_parametrizaciones;
        $IVA = $porcentajeIva 
        ->select("valorParametrizacion")
        ->where("flgElimina", 0)
        ->where("parametrizacionId", 1)
        ->first();
     
        $consultaCompra = $productosModel
        ->select("productoId, precioVenta")
        ->where("flgElimina", 0)
        ->where("productoId", $productoId)
        ->first();

        if($operacion == 'editar') {
            $reservaDetalleId = $this->request->getPost('reservaDetalleId');
            $salidaProducto = new fel_reservas_detalle();

            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $salidaProducto->select('fel_reservas_detalle.reservaDetalleId,fel_reservas_detalle.reservaId,fel_reservas_detalle.cantidadProducto,fel_reservas_detalle.precioUnitario,fel_reservas_detalle.porcentajeDescuento,inv_productos.productoId')
            ->join('inv_productos', 'inv_productos.productoId = fel_reservas_detalle.productoId')
            ->where('fel_reservas_detalle.flgElimina', 0)
            ->where('fel_reservas_detalle.reservaDetalleId', $reservaDetalleId)
            ->first();
        } else {

            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'reservaDetalleId'    => 0,
                'reservaId'           => $reservaId,
                'productoId'          => '',
                'cantidadProducto'    => '',
                'precioUnitario'      => '',
                'porcentajeDescuento' => ''

            ];
        }
        $data['operacion'] = $operacion;
        $data['precioUnitarioIVA'] = ($IVA['valorParametrizacion'] / 100) + 1;

        return view('ventas/modals/modalProductoReserva', $data);
 
    }


   /* public function modalNuevaReservaOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $reservaDetalleId = $this->request->getPost('reservaDetalleId');
        $model = new fel_reservas_detalle();
        $sucursalModel = new fel_reservas();  
        $reservaId = $this->request->getPost('reservaId');
        $productoId = $this->request->getPost('productoId');
        $precioUnitario = $this->request->getPost('hiddenPrecioUnitario');
        $cantidadProducto = $this->request->getPost('cantidadProducto');
        $porcentajeDescuento = $this->request->getPost('porcentajeDescuento');

        // Consulta para traer el 13% de la parametrización
        $porcentajeIva = new conf_parametrizaciones;
        $IVA = $porcentajeIva 
            ->select("valorParametrizacion")
            ->where("flgElimina", 0)
            ->where("parametrizacionId", 1)
            ->first(); 

        $IvaCalcular = ($precioUnitario * $IVA['valorParametrizacion']) / 100;
        $precioUnitarioIVA = $precioUnitario + $IvaCalcular;
        $ivaTotal = $IvaCalcular * $cantidadProducto;
        $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
        $IvaVentaCalcular = ($precioUnitarioVenta * $IVA['valorParametrizacion']) / 100;
        $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
        $totalReservaDetalle = $precioUnitarioVenta * $cantidadProducto;
        $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

        // Necesito Traer sucursalId de fel_reservas 
        $reservaData = $sucursalModel->find($reservaId);
        $sucursalId = $reservaData['sucursalId'];  

        // Obtener la existencia actual del producto en la sucursal
        $productosModel = new inv_productos_existencias();

        $producto = $productosModel->select('existenciaProducto')
                    ->where('flgElimina', 0)
                    ->where('sucursalId', $sucursalId)
                    ->where('productoId', $productoId)
                    ->first();

        if (!$producto) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Producto no encontrado'
            ]);
        }

        $existenciaActual = $producto['existenciaProducto'];

        // Verificar si el producto ya está en la reserva
        $detalleActual = $model->select('cantidadProducto, precioUnitario, porcentajeDescuento')
                                ->where('flgElimina', 0)
                                ->where('reservaId', $reservaId)
                                ->where('productoId', $productoId)
                                ->first();

        if ($operacion == 'editar' && $reservaDetalleId) {
            // Es una operación de edición
            $detalleActualEditar = $model->select('cantidadProducto')
                                        ->where('flgElimina', 0)
                                        ->where('reservaId', $reservaId)
                                        ->where('productoId', $productoId)
                                        ->where('reservaDetalleId', $reservaDetalleId)
                                        ->first();

            if (($cantidadProducto - $detalleActualEditar['cantidadProducto']) > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                ]);
            }

            // Recalcular los valores basados en la nueva cantidad
            $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
            $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
            $totalReservaDetalle = $precioUnitarioVenta * $cantidadProducto;
            $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

            $data = [
                'cantidadProducto'          => $cantidadProducto,
                'precioUnitario'            => $precioUnitario,
                'porcentajeDescuento'       => $porcentajeDescuento,
                'precioUnitarioIVA'         => $precioUnitarioIVA,
                'ivaUnitario'               => $IvaCalcular,
                'ivaTotal'                  => $ivaTotal,
                'precioUnitarioVenta'       => $precioUnitarioVenta,
                'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                'totalReservaDetalle'       => $totalReservaDetalle,
                'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
            ];

            $operacionReserva = $model->update($reservaDetalleId, $data);
            
            if ($operacionReserva) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva actualizada correctamente',
                    'reservaDetalleId' => $reservaDetalleId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo actualizar la reserva'
                ]);
            }
        } else {
            // Es una operación de agregar o no se especificó reservaDetalleId
            if ($detalleActual && $detalleActual['cantidadProducto'] > 0) {
                // El producto ya está en la reserva, actualizar la cantidad
                $nuevaCantidad = $detalleActual['cantidadProducto'] + $cantidadProducto;

                if ($nuevaCantidad > $existenciaActual) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                    ]);
                }

                // Recalcular los valores basados en la nueva cantidad
                $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
                $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
                $totalReservaDetalle = $precioUnitarioVenta * $nuevaCantidad;
                $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $nuevaCantidad;

                $data = [
                    'cantidadProducto'          => $nuevaCantidad,
                    'precioUnitario'            => $precioUnitario,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalReservaDetalle'       => $totalReservaDetalle,
                    'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
                ];

                $model->set($data)
                    ->where('flgElimina', 0)
                    ->where('reservaId', $reservaId)
                    ->where('productoId', $productoId)
                    ->update();
                
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva actualizada correctamente',
                    'reservaDetalleId' => $reservaDetalleId
                ]);
            } else {
                // Validar si la existencia es suficiente para una nueva inserción
                if ($cantidadProducto > $existenciaActual) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                    ]);
                }

                // Insertar nuevo detalle de reserva
                $data = [
                    'productoId'                => $productoId,
                    'cantidadProducto'          => $cantidadProducto,
                    'precioUnitario'            => $precioUnitario,
                    'reservaId'                 => $reservaId,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalReservaDetalle'       => $totalReservaDetalle,
                    'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
                ];

                $operacionReserva = $model->insert($data);

                if ($operacionReserva) {
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'Reserva agregada correctamente',
                        'reservaDetalleId' => $model->insertID()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se pudo insertar la reserva'
                    ]);
                }
            }
        }

    }*/

    public function modalNuevaReservaOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $reservaDetalleId = $this->request->getPost('reservaDetalleId');
        $model = new fel_reservas_detalle();
        $sucursalModel = new fel_reservas();  
        $reservaId = $this->request->getPost('reservaId');
        $productoId = $this->request->getPost('productoId');
        $precioUnitario = $this->request->getPost('hiddenPrecioUnitario');
        $cantidadProducto = $this->request->getPost('cantidadProducto');
        $porcentajeDescuento = $this->request->getPost('porcentajeDescuento');
    
        // Consulta para traer el 13% de la parametrización
        $porcentajeIva = new conf_parametrizaciones;
        $IVA = $porcentajeIva 
            ->select("valorParametrizacion")
            ->where("flgElimina", 0)
            ->where("parametrizacionId", 1)
            ->first(); 
    
        $IvaCalcular = ($precioUnitario * $IVA['valorParametrizacion']) / 100;
        $precioUnitarioIVA = $precioUnitario + $IvaCalcular;
        $ivaTotal = $IvaCalcular * $cantidadProducto;
        $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
        $IvaVentaCalcular = ($precioUnitarioVenta * $IVA['valorParametrizacion']) / 100;
        $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
        $totalReservaDetalle = $precioUnitarioVenta * $cantidadProducto;
        $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;
    
        // Necesito Traer sucursalId de fel_reservas 
        $reservaData = $sucursalModel->find($reservaId);
        $sucursalId = $reservaData['sucursalId'];  
    
        // Obtener la existencia actual del producto en la sucursal
        $productosModel = new inv_productos_existencias();
    
        $producto = $productosModel->select('existenciaProducto')
                    ->where('flgElimina', 0)
                    ->where('sucursalId', $sucursalId)
                    ->where('productoId', $productoId)
                    ->first();
    
        if (!$producto) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Producto no encontrado'
            ]);
        }
    
        $existenciaActual = $producto['existenciaProducto'];
    
        // Verificar si el producto ya está en la reserva
        $detalleActual = $model->select('cantidadProducto, precioUnitario, porcentajeDescuento')
                                ->where('flgElimina', 0)
                                ->where('reservaId', $reservaId)
                                ->where('productoId', $productoId)
                                ->first();
    
        if ($operacion == 'editar' && $reservaDetalleId) {
            // Es una operación de edición
            $detalleActualEditar = $model->select('cantidadProducto')
                                         ->where('flgElimina', 0)
                                         ->where('reservaId', $reservaId)
                                         ->where('productoId', $productoId)
                                         ->where('reservaDetalleId', $reservaDetalleId)
                                         ->first();
    
            /* if (($cantidadProducto - $detalleActualEditar['cantidadProducto']) > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                ]);
            } */
    
            // Recalcular los valores basados en la nueva cantidad
            $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
            $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
            $totalReservaDetalle = $precioUnitarioVenta * $cantidadProducto;
            $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;
    
            $data = [
                'cantidadProducto'          => $cantidadProducto,
                'precioUnitario'            => $precioUnitario,
                'porcentajeDescuento'       => $porcentajeDescuento,
                'precioUnitarioIVA'         => $precioUnitarioIVA,
                'ivaUnitario'               => $IvaCalcular,
                'ivaTotal'                  => $ivaTotal,
                'precioUnitarioVenta'       => $precioUnitarioVenta,
                'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                'totalReservaDetalle'       => $totalReservaDetalle,
                'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
            ];
    
            $operacionReserva = $model->update($reservaDetalleId, $data);
            
            if ($operacionReserva) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva actualizada correctamente',
                    'reservaDetalleId' => $reservaDetalleId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo actualizar la reserva'
                ]);
            }
        } else {
            // Es una operación de agregar o no se especificó reservaDetalleId
            if ($detalleActual && $detalleActual['cantidadProducto'] > 0) {
                // El producto ya está en la reserva, actualizar la cantidad
                $nuevaCantidad = $detalleActual['cantidadProducto'] + $cantidadProducto;
    
                /* if ($nuevaCantidad > $existenciaActual) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                    ]);
                } */
    
                // Recalcular los valores basados en la nueva cantidad
                $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
                $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
                $totalReservaDetalle = $precioUnitarioVenta * $nuevaCantidad;
                $totalReservaDetalleIVA = $precioUnitarioVentaIVA * $nuevaCantidad;
    
                $data = [
                    'cantidadProducto'          => $nuevaCantidad,
                    'precioUnitario'            => $precioUnitario,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalReservaDetalle'       => $totalReservaDetalle,
                    'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
                ];
    
                $model->set($data)
                      ->where('flgElimina', 0)
                      ->where('reservaId', $reservaId)
                      ->where('productoId', $productoId)
                      ->update();
                
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva actualizada correctamente',
                    'reservaDetalleId' => $reservaDetalleId
                ]);
            } else {
                // Validar si la existencia es suficiente para una nueva inserción
                /* if ($cantidadProducto > $existenciaActual) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No hay existencias suficientes para realizar la reserva'
                    ]);
                } */
    
                // Insertar nuevo detalle de reserva
                $data = [
                    'productoId'                => $productoId,
                    'cantidadProducto'          => $cantidadProducto,
                    'precioUnitario'            => $precioUnitario,
                    'reservaId'                 => $reservaId,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalReservaDetalle'       => $totalReservaDetalle,
                    'totalReservaDetalleIVA'    => $totalReservaDetalleIVA
                ];
    
                $operacionReserva = $model->insert($data);
    
                if ($operacionReserva) {
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'Reserva agregada correctamente',
                        'reservaDetalleId' => $model->insertID()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se pudo insertar la reserva'
                    ]);
                }
            }
        }
    
    }
    

public function eliminarReserva(){
        
    $eliminarReserva = new fel_reservas_detalle();
    
    $reservaDetalleId = $this->request->getPost('reservaDetalleId');
    $data = ['flgElimina' => 1];
    
    $eliminarReserva->update($reservaDetalleId, $data);

    if($eliminarReserva) {
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Reserva de producto eliminado correctamente'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo eliminar la reserva de producto'
        ]);
    }
}


public function tablaContinuarReserva()
{
    $reservaId = $this->request->getPost('reservaId');
    $mostrarReserva = new fel_reservas_detalle();
    $datos = $mostrarReserva
        ->select('fel_reservas_detalle.reservaDetalleId,fel_reservas_detalle.reservaId,fel_reservas_detalle.cantidadProducto,fel_reservas_detalle.productoId,fel_reservas_detalle.precioUnitario,fel_reservas_detalle.precioUnitarioIVA,fel_reservas_detalle.porcentajeDescuento,fel_reservas_detalle.precioUnitarioVenta,fel_reservas_detalle.precioUnitarioVentaIVA,fel_reservas_detalle.ivaUnitario,fel_reservas_detalle.ivaTotal,fel_reservas_detalle.totalReservaDetalle,fel_reservas_detalle.totalReservaDetalleIVA,inv_productos.productoId, inv_productos.producto,inv_productos.codigoProducto,cat_14_unidades_medida.unidadMedida')
        ->join('inv_productos', 'inv_productos.productoId = fel_reservas_detalle.productoId')
        ->join('cat_14_unidades_medida', 'cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->join('fel_reservas', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
        ->where('fel_reservas_detalle.flgElimina', 0)
        ->where('fel_reservas_detalle.reservaId', $reservaId)
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas

    // Variables para sumar los totales
    $subtotal = 0;
    $ivaTotal = 0;
    $totalAPagar = 0;
    $descuentos = 0;

    foreach ($datos as $columna) {
        // Construir columnas
        $columna1 = $n;
        $columna2 = "<b>Producto:</b> " . $columna['producto'] . "<br><b>Código :</b> " . $columna['codigoProducto'];
        $columna3 = "<b>Cantidad: </b> " . $columna['cantidadProducto'] . " (" . $columna['unidadMedida'] . ")";
        $columna4 = "<b>Precio sin IVA: </b> $" . number_format($columna['precioUnitarioVenta'], 2, '.', ',') . "<br><b>Precio con IVA: </b> $" . number_format($columna['precioUnitarioVentaIVA'], 2, '.', ',');

        $columna5 = "<b>Precio sin IVA: </b> $" . number_format($columna['totalReservaDetalle'], 2, '.', ',') . "<br><b>Precio con IVA: </b> $" . number_format($columna['totalReservaDetalleIVA'], 2, '.', ',');

        $columna6 = '
            <button class="btn btn-primary mb-1" onclick="modalProductoReserva(' . $columna['reservaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                <i class="fas fa-pen"></i>
            </button>
            <button class="btn btn-danger mb-1" onclick="eliminarReserva(' . $columna['reservaDetalleId'] . ');" data-toggle="tooltip" data-placement="top" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        ';

        // Agregar la fila al array de salida
        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3,
            $columna4,
            $columna5,
            $columna6
        );

        // Sumar los valores de cada columna
        $subtotal += $columna['totalReservaDetalle'];
        $ivaTotal += $columna['ivaTotal'];
        $totalAPagar += $columna['totalReservaDetalleIVA'];
        $descuentos += ($columna['precioUnitario'] - $columna['precioUnitarioVenta']) * $columna['cantidadProducto'];

        $n++;
    }

    // Obtener el número de pagos y la suma total de los pagos para la reservaId
    $reservaPago = new fel_reservas_pago();
    $pagosReserva = $reservaPago
        ->select('COUNT(*) as numeroPagos, SUM(montoPago) as totalPagado')
        ->where('reservaId', $reservaId)
        ->where('flgElimina', 0) // Suponiendo que 'flgElimina' marca los pagos eliminados
        ->first();

    $numeroPagos = $pagosReserva['numeroPagos'];
    $totalPagado = $pagosReserva['totalPagado'] ?? 0; // Si no hay pagos previos, se considera 0

    // Determinar la clase CSS según la condición
    $totalPagadoClass = ($totalPagado < $totalAPagar) ? 'text-danger' : 'text-success';

    // Determinar el texto, clase y estado del botón
    $botonTexto = ($totalPagado < $totalAPagar) ? 'Pagos (' . $numeroPagos . ')' : 'Pagado';
    $botonClase = ($totalPagado < $totalAPagar) ? 'btn-primary' : 'btn-success';
    $botonDisabled = ($totalPagado < $totalAPagar) ? '' : 'disabled';

    if ($n > 1) {
        $output['footer'] = array(
            '',
            '',
            ''
        );

        $output['footerTotales'] = '
            <div class="row text-right">
                <div class="col-8">
                    <b> Subtotal (=) :</b>
                </div>
                <div class="col-4">
                    $ ' . number_format($subtotal, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-8">
                    <b>Descuentos (-) :</b>
                </div>
                <div class="col-4">
                    $ ' . number_format($descuentos, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-8">
                    <b>IVA (+) :</b>
                </div>
                <div class="col-4">
                    $ ' . number_format($ivaTotal, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-8">
                    <b>Total a pagar (=) :</b>
                </div>
                <div class="col-4">
                    <b>$ ' . number_format($totalAPagar, 2, '.', ',') . ' </b>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-8">
                    <b>Total pagado:</b>
                </div>
                <div class="col-4">
                    <b class="' . $totalPagadoClass . '">$ ' . number_format($totalPagado, 2, '.', ',') . ' </b>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-12">
                    <button type="button" class="btn ' . $botonClase . ' mb-1" onclick="modalPagoReserva(`' . $reservaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Pagos" ' . $botonDisabled . '>
                        <i class="fas fa-hand-holding-usd"></i> ' . $botonTexto . '
                    </button>
                </div>
            </div>
        ';
        return $this->response->setJSON($output);
    } else {
        return $this->response->setJSON(array('data' => '', 'footer' => '')); // No hay datos, devuelve un array vacío
    }
}


    public function modalPagoReserva(){
        $data['reservaId'] = $this->request->getPost('reservaId');
        $formaPagoModel = new cat_17_forma_pago();
        // Obtener las formas de pago
        $data['formaPago'] = $formaPagoModel
            ->select("formaPagoMHId, formaPago")
            ->where("flgElimina", 0)
            ->findAll();
        return view('ventas/modals/modalPagosReservas', $data);
    }


    public function modalPagoReservaOperacion() {
        $reservaId = $this->request->getPost('reservaId');
        $montoPago = $this->request->getPost('montoPago');
        $numComprobantePago = $this->request->getPost('numComprobantePago');
    
        // Obtener la suma de totalReservaDetalle para la reservaId proporcionada
        $detalleReserva = new fel_reservas_detalle();
        $totalReserva = $detalleReserva
            ->select('SUM(totalReservaDetalleIVA) as total')
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->first();
    
        // Obtener la suma de todos los pagos realizados para la reservaId proporcionada
        $reservaPago = new fel_reservas_pago();
        $totalPagosRealizados = $reservaPago
            ->select('SUM(montoPago) as total')
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->first();
    
        // Verificar si el numComprobantePago ya existe
        $comprobanteExistente = $reservaPago
            ->where('numComprobantePago', $numComprobantePago)
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->first();
    
        // Mensajes de error
        $errores = [];
    
        // Verificar que el montoPago no exceda el total de la reserva menos los pagos realizados
        $totalPagosRealizados = $totalPagosRealizados['total'] ?? 0; // Si no hay pagos previos, se considera 0
        if ($totalReserva && ($montoPago + $totalPagosRealizados) > $totalReserva['total']) {
            $errores[] = 'El monto del pago excede el total de la reserva.';
        }
    
        if ($comprobanteExistente) {
            $errores[] = 'El número de comprobante ya existe.';
        }
    
        if (!empty($errores)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => implode(' ', $errores)
            ]);
        }
    
        // Datos a insertar
        $dataInsert = [
            'reservaId' => $reservaId,
            'fechaReservaPago' => $this->request->getPost('fechaReservaPago'),
            'formaPagoMHId' => $this->request->getPost('formaPagoMHId'),
            'numComprobantePago' => $numComprobantePago,
            'montoPago' => $montoPago,
            'comentarioPago' => $this->request->getPost('comentarioPago')
        ];
    
        // Insertar datos en la base de datos
        $operacionReservaPago = $reservaPago->insert($dataInsert);
        
        if ($operacionReservaPago) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Pago agregado correctamente',
                'reservaPagoId' => $reservaPago->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el pago'
            ]);
        }
    }
    


    public function tablePagoReserva(){
        $reservaId    = $this->request->getPost('reservaId');
        $contactoCliente = new fel_reservas_pago();

        $datos = $contactoCliente
          ->select('fel_reservas_pago.reservaPagoId,fel_reservas_pago.fechaReservaPago,cat_17_forma_pago.formaPago, fel_reservas_pago.numComprobantePago, fel_reservas_pago.montoPago, fel_reservas_pago.comentarioPago')
          ->join('cat_17_forma_pago' , 'cat_17_forma_pago.formaPagoMHId = fel_reservas_pago.formaPagoMHId')
          ->where('fel_reservas_pago.flgElimina', 0)
          ->where('fel_reservas_pago.reservaId',$reservaId)
          ->findAll();
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Forma pago:</b> " . $columna['formaPago'] . "<br><b>Comprobante :</b> " . $columna['numComprobantePago']. "<br><b>Comentario :</b> " . $columna['comentarioPago'];

            $columna3 = "<b>Fecha:</b> " . $columna['fechaReservaPago'] ;
            $columna4 = "<b>Monto:</b> " . number_format($columna['montoPago'], 2);


            $columna5 = '
                <button type="button" class="btn btn-danger mb-1" onclick="eliminarReservaPago(`'.$columna["reservaPagoId"].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
                </button>
            ';
            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5
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

    public function eliminarReservaPago(){
        
        $eliminarReservaPago = new fel_reservas_pago();
        
        $reservaPagoId = $this->request->getPost('reservaPagoId');
        $data = ['flgElimina' => 1];
        
        $eliminarReservaPago->update($reservaPagoId, $data);
    
        if($eliminarReservaPago) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Pago eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el pago de la reserva'
            ]);
        }
    }



    public function modalAnularReserva(){
        $fel_reservas = new fel_reservas();
        $reservaId = $this->request->getPost('reservaId');

        $data['campos'] = $fel_reservas
        ->select('reservaId')
        ->where('flgElimina', 0)
        ->where('reservaId', $reservaId)
        ->first();
       
        return view('ventas/modals/modalAnularReserva', $data);
    }

    
    public function operacionAnularReserva(){
        $anularReserva = new fel_reservas();
        
            $reservaId = $this->request->getPost('reservaId');
            $obsAnulacionReserva = $this->request->getPost('obsAnulacionReserva');

            $data = [
                'flgElimina'            => 0,
                'estadoReserva'         => "Anulado",
                'obsAnulacionReserva'   =>  $obsAnulacionReserva,
                'fechaAnulacionReserva' => date('Y-m-d')
            ];
            
            $anularReserva->update($reservaId, $data);

            if($anularReserva) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva Anulada correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo anular la reserva'
                ]);
            }
    }

        public function modalVerReserva(){
        $data["reservaId"] = $this->request->getPost('reservaId');
    
        return view('ventas/modals/modalVerReserva', $data);
    }
    
    public function tablaVerReserva(){
         $reservaId = $this->request->getPost('reservaId');
    $mostrarReserva = new fel_reservas_detalle();
    $datos = $mostrarReserva
        ->select('fel_reservas_detalle.reservaDetalleId,fel_reservas_detalle.reservaId,fel_reservas_detalle.cantidadProducto,fel_reservas_detalle.productoId,fel_reservas_detalle.precioUnitario,fel_reservas_detalle.precioUnitarioIVA,fel_reservas_detalle.porcentajeDescuento,fel_reservas_detalle.precioUnitarioVenta,fel_reservas_detalle.precioUnitarioVentaIVA,fel_reservas_detalle.ivaUnitario,fel_reservas_detalle.ivaTotal,fel_reservas_detalle.totalReservaDetalle,fel_reservas_detalle.totalReservaDetalleIVA,inv_productos.productoId, inv_productos.producto,inv_productos.codigoProducto,cat_14_unidades_medida.unidadMedida')
        ->join('inv_productos', 'inv_productos.productoId = fel_reservas_detalle.productoId')
        ->join('cat_14_unidades_medida', 'cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->join('fel_reservas', 'fel_reservas.reservaId = fel_reservas_detalle.reservaId')
        ->where('fel_reservas_detalle.flgElimina', 0)
        ->where('fel_reservas_detalle.reservaId', $reservaId)
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas

    // Variables para sumar los totales
    $subtotal = 0;
    $ivaTotal = 0;
    $totalAPagar = 0;
    $descuentos = 0;

    foreach ($datos as $columna) {
        // Construir columnas
        $columna1 = $n;
        $columna2 = "<b>Producto:</b> " . $columna['producto'] . "<br><b>Código :</b> " . $columna['codigoProducto'];
        $columna3 = "<b>Cantidad: </b> " . $columna['cantidadProducto'] . " (" . $columna['unidadMedida'] . ")";
        $columna4 = "<b>Precio sin IVA: </b> $" . number_format($columna['precioUnitarioVenta'], 2, '.', ',') . "<br><b>Precio con IVA: </b> $" . number_format($columna['precioUnitarioVentaIVA'], 2, '.', ',');

        $columna5 = "<b>Precio sin IVA: </b> $" . number_format($columna['totalReservaDetalle'], 2, '.', ',') . "<br><b>Precio con IVA: </b> $" . number_format($columna['totalReservaDetalleIVA'], 2, '.', ',');

        $columna6 = '
            <button class="btn btn-primary mb-1" onclick="modalProductoReserva(' . $columna['reservaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Editar" disabled>
                <i class="fas fa-pen"></i>
            </button>
            <button class="btn btn-danger mb-1" onclick="eliminarReserva(' . $columna['reservaDetalleId'] . ');" data-toggle="tooltip" data-placement="top" title="Eliminar" disabled>
                <i class="fas fa-trash"></i>
            </button>
        ';

        // Agregar la fila al array de salida
        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3,
            $columna4,
            $columna5,
            $columna6
        );

        // Sumar los valores de cada columna
        $subtotal += $columna['totalReservaDetalle'];
        $ivaTotal += $columna['ivaTotal'];
        $totalAPagar += $columna['totalReservaDetalleIVA'];
        $descuentos += ($columna['precioUnitario'] - $columna['precioUnitarioVenta']) * $columna['cantidadProducto'];

        $n++;
    }

    // Obtener el número de pagos y la suma total de los pagos para la reservaId
    $reservaPago = new fel_reservas_pago();
    $pagosReserva = $reservaPago
        ->select('COUNT(*) as numeroPagos, SUM(montoPago) as totalPagado')
        ->where('reservaId', $reservaId)
        ->where('flgElimina', 0) // Suponiendo que 'flgElimina' marca los pagos eliminados
        ->first();

    $numeroPagos = $pagosReserva['numeroPagos'];
    $totalPagado = $pagosReserva['totalPagado'] ?? 0; // Si no hay pagos previos, se considera 0

    // Determinar la clase CSS según la condición
    $totalPagadoClass = ($totalPagado < $totalAPagar) ? 'text-danger' : 'text-success';

    // Determinar el texto, clase y estado del botón
    $botonTexto = ($totalPagado < $totalAPagar) ? 'Pagos (' . $numeroPagos . ')' : 'Pagado';
    $botonClase = ($totalPagado < $totalAPagar) ? 'btn-primary' : 'btn-success';
    $botonDisabled = ($totalPagado < $totalAPagar) ? '' : 'disabled';

    if ($n > 1) {
        $output['footer'] = array(
            '',
            '',
            ''
        );

        $output['footerTotales'] = '
            <div class="row text-right">
                <div class="col-9">
                    <b> Subtotal (=) :</b>
                </div>
                <div class="col-3">
                    $ ' . number_format($subtotal, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-9">
                    <b>Descuentos (-) :</b>
                </div>
                <div class="col-3">
                    $ ' . number_format($descuentos, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-9">
                    <b>IVA (+) :</b>
                </div>
                <div class="col-3">
                    $ ' . number_format($ivaTotal, 2, '.', ',') . '
                </div>
            </div>
            <div class="row text-right">
                <div class="col-9">
                    <b>Total a pagar (=) :</b>
                </div>
                <div class="col-3">
                    <b>$ ' . number_format($totalAPagar, 2, '.', ',') . ' </b>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-9">
                    <b>Total pagado:</b>
                </div>
                <div class="col-3">
                    <b class="' . $totalPagadoClass . '">$ ' . number_format($totalPagado, 2, '.', ',') . ' </b>
                </div>
            </div>
            <div class="row text-right">
                <div class="col-12">
                    <button type="button" class="btn ' . $botonClase . ' mb-1" onclick="modalPagoReserva(`' . $reservaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Pagos" ' . $botonDisabled . ' disabled>
                        <i class="fas fa-hand-holding-usd"></i> ' . $botonTexto . '
                    </button>
                </div>
            </div>
        ';
        return $this->response->setJSON($output);
    } else {
        return $this->response->setJSON(array('data' => '', 'footer' => '')); // No hay datos, devuelve un array vacío
    }
    
    }

 /*     public function finalizarReserva(){
        $reservaId = $this->request->getPost('reservaId');
        $modelReserva = new fel_reservas();

         // Actualizar el estado del traslado
         $dataReservaEstado = [
            'estadoReserva' => "Finalizado"
        ];
        $modelReserva->update($reservaId, $dataReservaEstado);
        
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Reserva finalizada!.',
            'trasladosId' => $this->request->getPost('reservaId')
        ]);
      }

*/

    public function finalizarReserva()
    {
        $reservaId = $this->request->getPost('reservaId');
        $modelReserva = new fel_reservas();
        $modelReservaDetalle = new fel_reservas_detalle();
        $modelReservaPago = new fel_reservas_pago();

        // Obtener el total a pagar para la reserva
        $totalAPagar = $modelReservaDetalle
            ->select('SUM(totalReservaDetalleIVA) as totalAPagar')
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->first()['totalAPagar'];

        // Obtener el total pagado para la reserva
        $totalPagado = $modelReservaPago
            ->select('SUM(montoPago) as totalPagado')
            ->where('reservaId', $reservaId)
            ->where('flgElimina', 0)
            ->first()['totalPagado'];

        // Validar que el total pagado sea mayor o igual al total a pagar
        if ($totalPagado < $totalAPagar) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede finalizar la reserva. El total pagado es menor al total a pagar.'
            ]);
        }

        // Actualizar el estado de la reserva
        $dataReservaEstado = [
            'estadoReserva' => "Finalizado"
        ];
        $modelReserva->update($reservaId, $dataReservaEstado);
        
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Reserva finalizada!',
            'trasladosId' => $this->request->getPost('reservaId')
        ]);
    }


}
