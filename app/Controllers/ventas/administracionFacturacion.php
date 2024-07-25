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
use App\Models\cat_04_tipo_transmision;
use App\Models\cat_09_establecimiento;
use App\Models\cat_11_tipo_item;
use App\Models\cat_16_condicion_pago;
use App\Models\cat_18_plazo_pago;
use App\Models\fel_facturas;
use App\Models\fel_facturas_total;
use App\Models\fel_facturas_pago;
use App\Models\fel_facturas_detalle;
use App\Models\fel_facturas_complemento;
use App\Models\fel_facturas_certificacion_errores;
use App\Models\fel_factura_certificacion;
use App\Models\conf_empleados;
use App\Models\cat_02_tipo_dte;



class administracionFacturacion extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/facturacion', $data);
    }

    public function modalEmitirDTE(){
        // Cargar el modelos
        $sucursalesModel = new conf_sucursales();
        $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();

        $clientesModel = new fel_clientes();
        $data['clientes'] = $clientesModel->where('flgElimina', 0)->findAll();

        $empleadosModel = new conf_empleados();
        $data['empleados'] = $empleadosModel->where('flgElimina', 0)->findAll();

        $tipoDTEModel = new cat_02_tipo_dte();
        $data['tipoDTE'] = $tipoDTEModel->where('flgElimina', 0)->findAll();

        $operacion = $this->request->getPost('operacion');
        $data['sucursalId'] = $this->request->getPost('sucursalId');
        $data['clienteId'] = $this->request->getPost('clienteId');
        $data['empleadoId'] = $this->request->getPost('empleadoId');
        $data['tipoDTEId'] = $this->request->getPost('tipoDTEId');

        if($operacion == 'editar') {
            $facturaId = $this->request->getPost('facturaId');
            $DTEProducto = new fel_facturas();

            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $producto->select('fel_facturas.facturaId,fel_facturas.fechaEmision,fel_facturas.obsAnulacion,fel_facturas.estadoFactura,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente,conf_empleados.empleadoId,conf_empleados.primerNombre,conf_empleados.primerApellido,cat_02_tipo_dte.tipoDTEId,cat_02_tipo_dte.tipoDocumentoDTE')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
            ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
            ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas.facturaId', $facturaId)
            ->first();
        } else {

            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'facturaId'              => 0,
                'sucursalId'             => '',
                'tipoDTEId'              => '',
                'fechaEmision'           => '',
                'clienteId'              => '',
                'empleadoIdVendedor'     => ''

            ];
        }
        $data['operacion'] = $operacion;
        return view('ventas/modals/modalEmitirDTE', $data);
    }

    public function modalDTEOperacion()
    {
        // Continuar con la operación de inserción o actualización en la base de datos
        $operacion = $this->request->getPost('operacion');
        $facturaId = $this->request->getPost('facturaId');
        $model = new fel_facturas();
        $modelParametrizaciones = new conf_parametrizaciones();
        $modelCondicion = new cat_16_condicion_pago();

        $porcentajeIVA = $modelParametrizaciones->select('valorParametrizacion')
        ->where('flgElimina', 0)
        ->where('parametrizacionId', 1)
        ->first();

        $condicionFacturaMHId = $modelCondicion->select('condicionFacturaMHId')
        ->where('flgElimina', 0)
        ->where('condicionFacturaMHId', 1)
        ->first();

        $data = [
            'sucursalId'           => $this->request->getPost('sucursalId'),
            'fechaEmision'         => $this->request->getPost('fechaEmision'),
            'horaEmision'          => date('H:i'),
            'clienteId'            => $this->request->getPost('clienteId'),
            'empleadoIdVendedor'   => $this->request->getPost('empleadoIdVendedor'),
            'tipoDTEId'            => $this->request->getPost('tipoDTEId'),
            'porcentajeIVA'        => $porcentajeIVA,
            'condicionFacturaMHId' => $condicionFacturaMHId,
            'estadoFactura'        => "Pendiente"
        ];
    
        if ($operacion == 'editar') {
            $operacionDTE = $model->update($this->request->getPost('facturaId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionDTE = $model->insert($data);
        }
    
        if ($operacionDTE) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'DTE ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                'facturaId' => ($operacion == 'editar' ? $this->request->getPost('facturaId') : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el DTE'
            ]);
        }
      
    }

public function tablaFacturacion()
{
    $facturaId = $this->request->getPost('facturaId');
    $mostrarDTE = new fel_facturas();
    $datos = $mostrarDTE
        ->select('fel_facturas.facturaId,fel_facturas.fechaEmision,fel_facturas.obsAnulacion,fel_facturas.estadoFactura,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente,fel_clientes.nrcCliente,fel_clientes.numDocumentoIdentificacion,fel_clientes.direccionCliente,conf_empleados.empleadoId,conf_empleados.primerNombre,conf_empleados.primerApellido,cat_02_tipo_dte.tipoDTEId,cat_02_tipo_dte.tipoDocumentoDTE')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
        ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
        ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
        ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
        ->where('fel_facturas.flgElimina', 0)
         ->orderBy('fel_facturas.facturaId', 'DESC') // Ordenar por ID en orden descendente
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas
    foreach ($datos as $columna) {
        // Determina la clase Bootstrap basada en el estado del descargo
        $estadoClase = '';
        if ($columna['estadoFactura'] === 'Pendiente') {
            $estadoClase = 'badge badge-secondary';
        } elseif ($columna['estadoFactura'] === 'Finalizado') {
            $estadoClase = 'badge badge-success';
        } elseif ($columna['estadoFactura'] === 'Anulado') {
            $estadoClase = 'badge badge-danger';
        }

        // Construir columna 2
        $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Vendedor:</b> " . $columna['primerNombre']." ". $columna['primerApellido']. "<br><b>Código de generación:</b> ". "<br><b>Número de control:</b> ";

        // Construir columna 3 con la lógica solicitada
        $columna3 = "<b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoFactura'] . "</span>";
        if ($columna['estadoFactura'] === 'Anulado') {
            $columna3 .= "<br><b>Obs Anulación:</b> " . $columna['obsAnulacion'];
        }
        $columna3 = "<b>Fecha:</b> " . $columna['fechaEmision'] . "<br>" . $columna3;

        // Construir columna 4
        $columna4 = "<b>Cliente:</b> " . $columna['cliente'] . "<br><b>NRC:</b> " . $columna['nrcCliente']." ". "<br><b>Dirección:</b> " . $columna['direccionCliente'];

        // Construir columna 5
        $columna5 = "<b>Montos:</b> ";

        // Construir botones basado en estadoFactura
        if ($columna['estadoFactura'] === 'Pendiente') {
            $jsonActualizarReserva = [
                "facturaId" => $columna['facturaId']
            ];
            $columna6 = '
                <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-facturacion/vista/continuar/dte`, ' . htmlspecialchars(json_encode($jsonActualizarReserva)) . ');" data-toggle="tooltip" data-placement="top" title="Continuar DTE">
                    <i class="fas fa-sync-alt"></i> <span> </span>
                </button>

                <button class="btn btn-danger mb-1" onclick="modalAnularDTE(' . $columna['facturaId'] . ')" data-toggle="tooltip" data-placement="top" title="Anular">
                    <i class="fas fa-ban"></i>
                </button>
            ';
        } elseif ($columna['estadoFactura'] === 'Finalizado') {
            $columna6 = '
                <button class="btn btn-primary mb-1" onclick="modalFacturar(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Facturar">
                    <i class="fas fa-hand-holding-usd"></i><span> </span>
                </button>

                <button class="btn btn-info mb-1" onclick="modalVerReserva(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver reserva">
                    <i class="fas fa-eye"></i><span> </span>
                </button>

                <button class="btn btn-danger mb-1" onclick="modalAnularReserva(' . $columna['facturaId'] . ')" data-toggle="tooltip" data-placement="top" title="Anular">
                    <i class="fas fa-ban"></i>
                </button>

                <button type="button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="ver DTE">
                    <i class="fas fa-eye"></i>
                </button>

                <button type="button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Complementos">
                    <i class="fas fa-clipboard-list"></i>
                </button>
      
                <button type="button" class="btn btn-primary mb-1" onclick="modalImprimirDTE()" data-toggle="tooltip" data-placement="top" title="Imprimir DTE">
                    <i class="fas fa-print"></i>
                </button>
           
                <button type="button" class="btn btn-primary mb-1" onclick="window.location.href=`https://admin.factura.gob.sv/consultaPublica`" data-toggle="tooltip" data-placement="top" title="Consultar DTE">
                    <i class="fas fa-file-alt"></i>
                </button>
                            
                <button type="button" class="btn btn-danger mb-1" onclick="modalInvalidarDTE()" data-toggle="tooltip" data-placement="top" title="Invalidar DTE">
                    <i class="fas fa-ban"></i>
                </button>';
        } else {
            $columna6 = '
                <button class="btn btn-info mb-1" onclick="modalVerDTE(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver reserva">
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
            $columna5,
            $columna6
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

    public function modalAnularDTE(){
        $fel_facturas = new fel_facturas();
        $facturaId = $this->request->getPost('facturaId');

        $data['campos'] = $fel_facturas
        ->select('facturaId')
        ->where('flgElimina', 0)
        ->where('facturaId', $facturaId)
        ->first();
        return view('ventas/modals/modalAnularDTE', $data);
    }

        public function operacionAnularDTE(){
        $anularDTE = new fel_facturas();
        
            $facturaId = $this->request->getPost('facturaId');
            $obsAnulacion = $this->request->getPost('obsAnulacion');

            $data = [
                'flgElimina'            => 0,
                'estadoFactura'         => "Anulado",
                'obsAnulacion'          =>  $obsAnulacion
            ];
            
            $anularDTE->update($facturaId, $data);

            if($anularDTE) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'DTE Anulado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo anular el DTE'
                ]);
            }
    }

    public function vistaContinuarDTE()
    {
        $session = session();
        $facturaId = $this->request->getPost('facturaId');

        $camposSession = [
            'renderVista' => 'No',
            'facturaId'    => $facturaId
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/vista/continuar/dte',
            'camposSession'     => json_encode($camposSession)
        ]);

        $data['facturaId'] = $facturaId;
        $mostrarSalida = new fel_facturas();
        
        $sucursales = new conf_sucursales();
        $clientes = new fel_clientes();
        $tipoDTE = new cat_02_tipo_dte();
        $empleados = new conf_empleados();

        $data['sucursales'] = $sucursales
            ->select("sucursalId,sucursal")
            ->where("flgElimina", 0)
            ->findAll();

        $data['clientes'] = $clientes
            ->select("clienteId,cliente")
            ->where("flgElimina", 0)
            ->findAll();

        $data['tipoDTE'] = $tipoDTE
            ->select("tipoDTEId,tipoDocumentoDTE")
            ->where("flgElimina", 0)
            ->findAll();

        $data['empleados'] = $empleados
            ->select("empleadoId,primerNombre,primerApellido")
            ->where("flgElimina", 0)
            ->findAll();

        // Consulta para traer los valores de los input que se pueden actualizar
        $consultaDTE = $mostrarSalida
            ->select('fel_facturas.facturaId,fel_facturas.fechaEmision,fel_facturas.obsAnulacion,fel_facturas.estadoFactura,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente,conf_empleados.empleadoId,conf_empleados.primerNombre,conf_empleados.primerApellido,cat_02_tipo_dte.tipoDTEId,cat_02_tipo_dte.tipoDocumentoDTE')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
            ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
            ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas.facturaId', $facturaId)
            ->first();

        $data['campos'] = [
            'sucursalId'    => $consultaDTE['sucursalId'],
            'clienteId'     => $consultaDTE['clienteId'],
            'fechaEmision'  => $consultaDTE['fechaEmision'],
            'empleadoId'    => $consultaDTE['empleadoId'],
            'tipoDTEId'     => $consultaDTE['tipoDTEId']
        ];

        return view('ventas/vistas/pageContinuarDTE', $data);
    }

    public function vistaActualizarDTEOperacion()
    {
        $facturas = new fel_facturas();
        $facturaId = $this->request->getPost('facturaId');
        $DTEDetalleModel = new fel_facturas_detalle();
        $productosAgregados = $DTEDetalleModel
            ->where('facturaId', $facturaId)
            ->where('flgElimina', 0)
            ->countAllResults();

        if ($productosAgregados > 0) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede actualizar porque ya se han agregado productos al DTE.'
            ]);
        }

        $data = [
            'sucursalId'    => $this->request->getPost('sucursalId'),
            'clienteId'     => $this->request->getPost('clienteId'),
            'fechaEmision'  => $this->request->getPost('fechaEmision'),
            'horaEmision'   => date('H:i:s'), // Agregar la hora del sistema
            'empleadoId'    => $this->request->getPost('empleadoId'),
            'tipoDTEId'     => $this->request->getPost('tipoDTEId')
        ];

        $operacionDTE = $facturas->update($facturaId, $data);

        if ($operacionDTE) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'DTE actualizado correctamente',
                'facturaId' => $facturaId
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo actualizar el DTE'
            ]);
        }
    }

        public function modalNuevoProductoDTE()
    {

        // Cargar el modelos
        $productosModel = new inv_productos();
        $data['producto'] = $productosModel->where('flgElimina', 0)->findAll();
        $operacion = $this->request->getPost('operacion');
        $data['productoId'] = $this->request->getPost('productoId');
        $facturaId = $this->request->getPost('facturaId');
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
            $facturaDetalleId = $this->request->getPost('facturaDetalleId');
            $salidaProducto = new fel_facturas_detalle();

            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $salidaProducto->select('fel_facturas_detalle.facturaDetalleId,fel_facturas_detalle.facturaId,fel_facturas_detalle.cantidadProducto,fel_facturas_detalle.precioUnitario,fel_facturas_detalle.porcentajeDescuento,inv_productos.productoId')
            ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->where('fel_facturas_detalle.facturaDetalleId', $facturaDetalleId)
            ->first();
        } else {

            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'facturaDetalleId'    => 0,
                'facturaId'           => $facturaId,
                'productoId'          => '',
                'cantidadProducto'    => '',
                'precioUnitario'      => '',
                'porcentajeDescuento' => ''

            ];
        }
        $data['operacion'] = $operacion;
        $data['precioUnitarioIVA'] = ($IVA['valorParametrizacion'] / 100) + 1;

        return view('ventas/modals/modalProductoDTE', $data);
 
    }

        public function modalNuevoDTEOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $facturaDetalleId = $this->request->getPost('facturaDetalleId');
        $model = new fel_facturas_detalle();
        $sucursalModel = new fel_facturas();  
        $facturaId = $this->request->getPost('facturaId');
        $productoId = $this->request->getPost('productoId');
        $precioUnitario = $this->request->getPost('hiddenPrecioUnitario');
        $cantidadProducto = $this->request->getPost('cantidadProducto');
        $porcentajeDescuento = $this->request->getPost('porcentajeDescuento');

        // Consulta para traer el 13% de la parametrización
        $porcentajeIva = new conf_parametrizaciones();
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
        $totalDetalle = $precioUnitarioVenta * $cantidadProducto;
        $totalDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

        // Obtener sucursalId de fel_reservas 
        $dteData = $sucursalModel->find($facturaId);
        $sucursalId = $dteData['sucursalId'];  
        $tipoItemMHId = 1; // Valor por defecto para tipoItemMHId

        // Obtener la existencia actual del producto en la sucursal
        $productosModel = new inv_productos_existencias();
        $productoExistencia = $productosModel->select('existenciaProducto')
                    ->where('flgElimina', 0)
                    ->where('sucursalId', $sucursalId)
                    ->where('productoId', $productoId)
                    ->first();

        if (!$productoExistencia) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Producto no encontrado'
            ]);
        }

        $existenciaActual = $productoExistencia['existenciaProducto'];

        // Obtener el codigoProducto desde inv_productos
        $productosModel = new inv_productos();
        $producto = $productosModel->select('codigoProducto')
                    ->where('productoId', $productoId)
                    ->first();

        if (!$producto) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Código de producto no encontrado'
            ]);
        }

        $codigoProducto = $producto['codigoProducto'];

        // Verificar si el producto ya está en la reserva
        $detalleActual = $model->select('cantidadProducto, precioUnitario, porcentajeDescuento')
                                ->where('flgElimina', 0)
                                ->where('facturaId', $facturaId)
                                ->where('productoId', $productoId)
                                ->first();

        if ($operacion == 'editar' && $facturaDetalleId) {
            // Es una operación de edición
            $detalleActualEditar = $model->select('cantidadProducto')
                                         ->where('flgElimina', 0)
                                         ->where('facturaId', $facturaId)
                                         ->where('productoId', $productoId)
                                         ->where('facturaDetalleId', $facturaDetalleId)
                                         ->first();

            if (($cantidadProducto - $detalleActualEditar['cantidadProducto']) > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar el DTE'
                ]);
            }

            // Recalcular los valores basados en la nueva cantidad
            $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
            $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
            $totalDetalle = $precioUnitarioVenta * $cantidadProducto;
            $totalDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

            $data = [
                'cantidadProducto'          => $cantidadProducto,
                'precioUnitario'            => $precioUnitario,
                'porcentajeDescuento'       => $porcentajeDescuento,
                'precioUnitarioIVA'         => $precioUnitarioIVA,
                'ivaUnitario'               => $IvaCalcular,
                'ivaTotal'                  => $ivaTotal,
                'precioUnitarioVenta'       => $precioUnitarioVenta,
                'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                'totalDetalle'              => $totalDetalle,
                'totalDetalleIVA'           => $totalDetalleIVA,
                'codigoProducto'            => $codigoProducto,
                'tipoItemMHId'              => $tipoItemMHId
            ];

            $operacionDTE = $model->update($facturaDetalleId, $data);
            
            if ($operacionDTE) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'DTE actualizado correctamente',
                    'facturaDetalleId' => $facturaDetalleId
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo actualizar el DTE'
                ]);
            }
        } else {
            // Es una operación de agregar o no se especificó facturaDetalleId
            if ($detalleActual && $detalleActual['cantidadProducto'] > 0) {
                // El producto ya está en la reserva, actualizar la cantidad
                $nuevaCantidad = $detalleActual['cantidadProducto'] + $cantidadProducto;

                if ($nuevaCantidad > $existenciaActual) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No hay existencias suficientes para realizar el DTE'
                    ]);
                }

                // Recalcular los valores basados en la nueva cantidad
                $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
                $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
                $totalDetalle = $precioUnitarioVenta * $nuevaCantidad;
                $totalDetalleIVA = $precioUnitarioVentaIVA * $nuevaCantidad;

                $data = [
                    'cantidadProducto'          => $nuevaCantidad,
                    'precioUnitario'            => $precioUnitario,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalDetalle'              => $totalDetalle,
                    'totalDetalleIVA'           => $totalDetalleIVA,
                    'codigoProducto'            => $codigoProducto,
                    'tipoItemMHId'              => $tipoItemMHId
                ];

                $model->set($data)
                      ->where('flgElimina', 0)
                      ->where('facturaId', $facturaId)
                      ->where('productoId', $productoId)
                      ->update();
                
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Reserva actualizada correctamente',
                    'facturaDetalleId' => $facturaDetalleId
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
                    'facturaId'                 => $facturaId,
                    'porcentajeDescuento'       => $porcentajeDescuento,
                    'precioUnitarioIVA'         => $precioUnitarioIVA,
                    'ivaUnitario'               => $IvaCalcular,
                    'ivaTotal'                  => $ivaTotal,
                    'precioUnitarioVenta'       => $precioUnitarioVenta,
                    'precioUnitarioVentaIVA'    => $precioUnitarioVentaIVA,
                    'totalDetalle'              => $totalDetalle,
                    'totalDetalleIVA'           => $totalDetalleIVA,
                    'codigoProducto'            => $codigoProducto,
                    'tipoItemMHId'              => $tipoItemMHId
                ];

                $operacionDTE = $model->insert($data);

                if ($operacionDTE) {
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'DTE agregado correctamente',
                        'facturaDetalleId' => $model->insertID()
                    ]);
                } else {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se pudo insertar el DTE'
                    ]);
                }
            }
        }
    }


    public function tablaContinuarDTE(){
        $output['data'] = array();
            $n = 0;

            $n++;
            $output['data'] = array();

            // Aquí construye tus columnas
            $columna1 = $n;

            $columna2 = "(PD-001) Mario Kart";

            $columna3 = "$ 53.10";

            $columna4 = "2";

            $columna5 = "$ 13.80";
           
            $columna6 = "$ 120.00";
           
            $columna7 = '
                            <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="fas fa-sync-alt"></i>
                            </button>';
            $columna7 .= '
                            <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>';
                            
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
                $columna6,
                $columna7
            );

            if ($n > 0) {
                $output['footer'] = array(
                    '',
                    '' ,
                    ''
                );

                $output['footerTotales'] = '
                    <b>
                        <div class="row text-right">
                            <div class="col-8">
                                Sub total:
                            </div>
                            <div class="col-4">
                                $ 106.2
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                IVA 13%:
                            </div>
                            <div class="col-4">
                                $ 13.80
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total a pagar:
                            </div>
                            <div class="col-4">
                                $ 120.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total pagado:
                            </div>
                            <div class="col-4">
                                $ 120.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-12">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalPagoDTE()" data-toggle="tooltip" data-placement="top" title="Pagos">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-4">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalComplementoDTE()" data-toggle="tooltip" data-placement="top" title="Complementos">
                                    <i class="fas fa-clipboard-list"></i> Complementos
                                </button>
                            </div>
                            <div class="col-4">
                                <button type= "button" class="btn btn-danger mb-1" onclick="modalErrorDTE()" data-toggle="tooltip" data-placement="top" title="Error de certificación">
                                    <i class="fas fa-ban"></i> Errores de certificación
                                </button>
                            </div>
                            <div class="col-4">
                                <button type= "button" class="btn btn-primary mb-1" onclick="CertificarDTE()" data-toggle="tooltip" data-placement="top" title="Certificar DTE">
                                    <i class="fas fa-save"></i> Certificar DTE
                                </button>
                            </div>
                        </div>
                    </b>

                ';
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }     
    }

    public function modalPagoDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalPagoDTE', $data);
    }

    public function tablaPagoDTE(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "<b>Forma de pago: </b> Billetes y monedas" . "<br>" . "<b>Descripción/Comprobanto: </b> Cancelado en efectivo";

        $columna3 = "$ 120.00";
        
        $columna4 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>';
                        
        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3,
            $columna4
        );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalComplementoDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalComplementoDTE', $data);
    }

    public function tablaComplementoDTE(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "Edición especial";
        
        $columna3 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>';
                        
        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3
        );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function tablaErrorDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalErrorDTE', $data);
    }

    public function imprimirDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalImprimirDTE', $data);
    }
}
