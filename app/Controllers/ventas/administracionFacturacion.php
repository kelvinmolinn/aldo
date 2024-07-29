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


    /*public function modalNuevoDTEOperacion()
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
    $productosExistenciasModel = new inv_productos_existencias();
    $productoExistencia = $productosExistenciasModel->select('existenciaProducto')
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
    $detalleActual = $model->select('cantidadProducto, precioUnitario, porcentajeDescuento, productoId')
                            ->where('flgElimina', 0)
                            ->where('facturaId', $facturaId)
                            ->where('facturaDetalleId', $facturaDetalleId)
                            ->first();

    if ($operacion == 'editar' && $facturaDetalleId) {
        // Es una operación de edición
        $detalleActualEditar = $model->select('cantidadProducto, productoId')
                                     ->where('flgElimina', 0)
                                     ->where('facturaId', $facturaId)
                                     ->where('facturaDetalleId', $facturaDetalleId)
                                     ->first();

        if (!$detalleActualEditar) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Detalle de la factura no encontrado'
            ]);
        }

        $cantidadProductoAnterior = $detalleActualEditar['cantidadProducto'];
        $productoIdAnterior = $detalleActualEditar['productoId'];
        
        if ($productoId != $productoIdAnterior || ($cantidadProducto - $cantidadProductoAnterior) > $existenciaActual) {
            if ($cantidadProducto > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar el DTE'
                ]);
            }
        }

        // Recalcular los valores basados en la nueva cantidad y producto
        $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
        $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
        $totalDetalle = $precioUnitarioVenta * $cantidadProducto;
        $totalDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

        $data = [
            'productoId'                => $productoId,
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
    }*/

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

    // Calcular el descuento total para la factura
    $descuentoTotal = $precioUnitario * ($porcentajeDescuento / 100) * $cantidadProducto;

    // Obtener sucursalId de fel_reservas 
    $dteData = $sucursalModel->find($facturaId);
    $sucursalId = $dteData['sucursalId'];  
    $tipoItemMHId = 1; // Valor por defecto para tipoItemMHId

    // Obtener la existencia actual del producto en la sucursal
    $productosExistenciasModel = new inv_productos_existencias();
    $productoExistencia = $productosExistenciasModel->select('existenciaProducto')
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
    $detalleActual = $model->select('cantidadProducto, precioUnitario, porcentajeDescuento, productoId')
                            ->where('flgElimina', 0)
                            ->where('facturaId', $facturaId)
                            ->where('facturaDetalleId', $facturaDetalleId)
                            ->first();

    if ($operacion == 'editar' && $facturaDetalleId) {
        // Es una operación de edición
        $detalleActualEditar = $model->select('cantidadProducto, productoId')
                                     ->where('flgElimina', 0)
                                     ->where('facturaId', $facturaId)
                                     ->where('facturaDetalleId', $facturaDetalleId)
                                     ->first();

        if (!$detalleActualEditar) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Detalle de la factura no encontrado'
            ]);
        }

        $cantidadProductoAnterior = $detalleActualEditar['cantidadProducto'];
        $productoIdAnterior = $detalleActualEditar['productoId'];
        
        if ($productoId != $productoIdAnterior || ($cantidadProducto - $cantidadProductoAnterior) > $existenciaActual) {
            if ($cantidadProducto > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar el DTE'
                ]);
            }
        }

        // Recalcular los valores basados en la nueva cantidad y producto
        $precioUnitarioVenta = $precioUnitario * (1 - ($porcentajeDescuento / 100));
        $precioUnitarioVentaIVA = $precioUnitarioVenta + $IvaVentaCalcular;
        $totalDetalle = $precioUnitarioVenta * $cantidadProducto;
        $totalDetalleIVA = $precioUnitarioVentaIVA * $cantidadProducto;

        $data = [
            'productoId'                => $productoId,
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
            'tipoItemMHId'              => $tipoItemMHId,
            'descuentoTotal'            => $descuentoTotal
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

            $descuentoTotal += $precioUnitario * ($porcentajeDescuento / 100) * $nuevaCantidad;

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
                'tipoItemMHId'              => $tipoItemMHId,
                'descuentoTotal'            => $descuentoTotal
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
                'tipoItemMHId'              => $tipoItemMHId,
                'descuentoTotal'            => $descuentoTotal
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

    $facturaId = $this->request->getPost('facturaId');
    $mostrarDTE = new fel_facturas_detalle();
    $datos = $mostrarDTE
        ->select('fel_facturas_detalle.facturaDetalleId,fel_facturas_detalle.facturaId,fel_facturas_detalle.cantidadProducto,fel_facturas_detalle.productoId,fel_facturas_detalle.conceptoProducto,fel_facturas_detalle.precioUnitario,fel_facturas_detalle.precioUnitarioIVA,fel_facturas_detalle.porcentajeDescuento,fel_facturas_detalle.descuentoTotal,fel_facturas_detalle.precioUnitarioVenta,fel_facturas_detalle.precioUnitarioVentaIVA,fel_facturas_detalle.ivaUnitario,fel_facturas_detalle.ivaTotal,fel_facturas_detalle.totalDetalle,fel_facturas_detalle.totalDetalleIVA,inv_productos.productoId, inv_productos.producto,inv_productos.codigoProducto,cat_14_unidades_medida.unidadMedida')
        ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
        ->join('cat_14_unidades_medida', 'cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->join('fel_facturas', 'fel_facturas.facturaId = fel_facturas_detalle.facturaId')
        ->where('fel_facturas_detalle.flgElimina', 0)
        ->where('fel_facturas_detalle.facturaId', $facturaId)
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

        // Verificar si conceptoProducto tiene datos
        $conceptoTexto = !empty($columna['conceptoProducto']) ? "<br><b>Concepto :</b> " . $columna['producto'] . " ( " . $columna['conceptoProducto'] . " )" : "";

        $columna2 = "<b>Producto:</b> " . $columna['producto'] . "<br><b>Código :</b> " . $columna['codigoProducto'] . $conceptoTexto;

        $columna3 = "<b>sin IVA: </b> $" . number_format($columna['precioUnitario'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['precioUnitarioIVA'], 2, '.', ',');

        $columna4 = "<b>Procentaje: </b> " . number_format($columna['porcentajeDescuento'], 2, '.', ',') . "%" . "<br><b>Total :</b> $" . number_format($columna['descuentoTotal'], 2, '.', ',');

        $columna5 = "<b>Sin IVA: </b> $" . number_format($columna['precioUnitarioVenta'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['precioUnitarioVentaIVA'], 2, '.', ',');

        $columna6 = " <b>Cantidad: </b> " . $columna['cantidadProducto'] . " (" . $columna['unidadMedida'] . ")";

        $columna7 = "<b>unitario: </b> $" . number_format($columna['ivaUnitario'], 2, '.', ',') . "<br><b>total: </b> $" . number_format($columna['ivaTotal'], 2, '.', ',');

        $columna8 = "<b>Sin IVA: </b> $" . number_format($columna['totalDetalle'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['totalDetalleIVA'], 2, '.', ',');

        $columna9 = '
            <button class="btn btn-primary mb-1" onclick="modalProductoDTE(' . $columna['facturaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                <i class="fas fa-pen"></i>
            </button>

            <button class="btn btn-info mb-1" onclick="modalConceptoDTE(' . $columna['facturaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Concepto">
                <i class="fas fa-clipboard-list"></i>
            </button>

            <button class="btn btn-danger mb-1" onclick="eliminarDTE(' . $columna['facturaDetalleId'] . ');" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                $columna6,
                $columna7,
                $columna8,
                $columna9
            );

            // Sumar los valores de cada columna
            $subtotal += $columna['totalDetalle'];
            $ivaTotal += $columna['ivaTotal'];
            $totalAPagar += $columna['totalDetalleIVA'];
            $descuentos += ($columna['precioUnitario'] - $columna['precioUnitarioVenta']) * $columna['cantidadProducto'];

            $n++;
        }

        // Obtener el número de pagos y la suma total de los pagos para la facturaId
        $DTEPago = new fel_facturas_pago();
        $pagosReserva = $DTEPago
            ->select('COUNT(*) as numeroPagos, SUM(totalPago) as totalPagado')
            ->where('facturaId', $facturaId)
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
                        <button type="button" class="btn ' . $botonClase . ' mb-1" onclick="modalPagoDTE(`' . $facturaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Pagos" >
                            <i class="fas fa-hand-holding-usd"></i> ' . $botonTexto . '
                        </button>
                    </div>
                </div>

                        <div class="row text-right">
                            <div class="col-4">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalComplementoDTE(`' . $facturaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Complementos">
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
                return $this->response->setJSON(array('data' => '', 'footer' => '')); // No hay datos, devuelve un array vacío
            }

            
}

       public function eliminarDTE(){
        
    $eliminarDTE = new fel_facturas_detalle();
    
    $facturaDetalleId = $this->request->getPost('facturaDetalleId');
    $data = ['flgElimina' => 1];
    
    $eliminarDTE->update($facturaDetalleId, $data);

    if($eliminarDTE) {
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Producto de dte eliminado correctamente'
        ]);
    } else {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo eliminar el producto del dte'
        ]);
    }
}
  

    public function modalPagoDTE(){

        $data['facturaId'] = $this->request->getPost('facturaId');
        $formaPagoModel = new cat_17_forma_pago();
        // Obtener las formas de pago
        $data['formaPago'] = $formaPagoModel
            ->select("formaPagoMHId, formaPago")
            ->where("flgElimina", 0)
            ->findAll();
        return view('ventas/modals/modalPagoDTE', $data);
    }

public function modalPagoDTEOperacion() {
    $facturaId = $this->request->getPost('facturaId');
    $totalPago = round($this->request->getPost('totalPago'), 2); // Aproximar a dos decimales

    // Obtener la suma de totalReservaDetalle para la facturaId proporcionada
    $detalleReserva = new fel_facturas_detalle();
    $totalReserva = $detalleReserva
        ->select('SUM(ROUND(totalDetalleIVA, 2)) as total') // Aproximar a dos decimales
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->first();

    // Obtener la suma de todos los pagos realizados para la facturaId proporcionada
    $reservaPago = new fel_facturas_pago();
    $totalPagosRealizados = $reservaPago
        ->select('SUM(ROUND(totalPago, 2)) as total') // Aproximar a dos decimales
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->first();

    // Mensajes de error
    $errores = [];

    // Verificar que el totalPago no exceda el total de la reserva menos los pagos realizados
    $totalPagosRealizados = $totalPagosRealizados['total'] ?? 0; // Si no hay pagos previos, se considera 0
    if ($totalReserva && ($totalPago + $totalPagosRealizados) > $totalReserva['total']) {
        $errores[] = 'El monto del pago excede el total de la reserva.';
    }

    if (!empty($errores)) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => implode(' ', $errores)
        ]);
    }

    // Datos a insertar
    $dataInsert = [
        'facturaId' => $facturaId,
        'formaPagoMHId' => $this->request->getPost('formaPagoMHId'),
        'totalPago' => $totalPago,
        'descripcionPago' => $this->request->getPost('descripcionPago')
    ];

    // Insertar datos en la base de datos
    $operacionReservaPago = $reservaPago->insert($dataInsert);
    
    if ($operacionReservaPago) {
        // Si el insert fue exitoso, devuelve el último ID insertado
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Pago agregado correctamente',
            'facturaPagoId' => $reservaPago->insertID()
        ]);
    } else {
        // Si el insert falló, devuelve un mensaje de error
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo insertar el pago'
        ]);
    }
}

    public function tablaPagoDTE(){
        $facturaId    = $this->request->getPost('facturaId');
        $tablaPagoDTE = new fel_facturas_pago();

        $datos = $tablaPagoDTE
          ->select('fel_facturas_pago.facturaPagoId,cat_17_forma_pago.formaPago, fel_facturas_pago.totalPago, fel_facturas_pago.descripcionPago')
          ->join('cat_17_forma_pago' , 'cat_17_forma_pago.formaPagoMHId = fel_facturas_pago.formaPagoMHId')
          ->where('fel_facturas_pago.flgElimina', 0)
          ->where('fel_facturas_pago.facturaId',$facturaId)
          ->findAll();
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Forma pago:</b> " . $columna['formaPago'] . "<br><b>Comentario :</b> " . $columna['descripcionPago'];

            $columna3 = "<b>Monto:</b> " . number_format($columna['totalPago'], 2);


            $columna4 = '
                <button type="button" class="btn btn-danger mb-1" onclick="eliminarDTEPago(`'.$columna["facturaPagoId"].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
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

        public function eliminarDTEPago(){
        
        $eliminarDTEPago = new fel_facturas_pago();
        
        $facturaPagoId = $this->request->getPost('facturaPagoId');
        $data = ['flgElimina' => 1];
        
        $eliminarDTEPago->update($facturaPagoId, $data);
    
        if($eliminarDTEPago) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Pago eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el pago del dte'
            ]);
        }
    }

        public function modalConceptoDTE(){
        $fel_facturas_detalle = new fel_facturas_detalle();
        $facturaDetalleId = $this->request->getPost('facturaDetalleId');

        $data['campos'] = $fel_facturas_detalle
        ->select('facturaDetalleId')
        ->where('flgElimina', 0)
        ->where('facturaDetalleId', $facturaDetalleId)
        ->first();
        return view('ventas/modals/modalConceptoDTE', $data);
    }

        public function operacionConceptoDTE(){
        $conceptoDTE = new fel_facturas_detalle();
        
            $facturaDetalleId = $this->request->getPost('facturaDetalleId');
            $conceptoProducto = $this->request->getPost('conceptoProducto');

            $data = [
                'conceptoProducto'          =>  $conceptoProducto
            ];
            
            $conceptoDTE->update($facturaDetalleId, $data);

            if($conceptoDTE) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Concepto agregado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo agregar el concepto'
                ]);
            }
    }


    public function modalComplementoDTE(){

        $data['facturaId'] = $this->request->getPost('facturaId');
        $complementoModel = new fel_facturas_complemento();
        // Obtener las formas de pago
        $data['complementoFactura'] = $complementoModel
            ->select("facturaComplementoId, tipoComplemento, complementoFactura")
            ->where("flgElimina", 0)
            ->findAll();
        return view('ventas/modals/modalComplementoDTE', $data);
    }

public function modalComplementoDTEOperacion() {
    $complementoDTE = new fel_facturas_complemento();
    $facturaId = $this->request->getPost('facturaId');
    $tipoComplemento = $this->request->getPost('tipoComplemento'); 
    $complementoFactura = $this->request->getPost('complementoFactura'); 


    // Datos a insertar
    $dataInsert = [
        'facturaId' => $facturaId,
        'tipoComplemento' => $this->request->getPost('tipoComplemento'),
        'complementoFactura' => $this->request->getPost('complementoFactura')
    ];

    // Insertar datos en la base de datos
    $operacionComplemento = $complementoDTE->insert($dataInsert);
    
    if ($operacionComplemento) {
        // Si el insert fue exitoso, devuelve el último ID insertado
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Complemento agregado correctamente',
            'facturaComplementoId' => $complementoDTE->insertID()
        ]);
    } else {
        // Si el insert falló, devuelve un mensaje de error
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo insertar el complemento'
        ]);
    }
}

    public function tablaComplementoDTE(){
        $facturaId    = $this->request->getPost('facturaId');
        $tablaComplementoDTE = new fel_facturas_complemento();

        $datos = $tablaComplementoDTE
          ->select('fel_facturas_complemento.facturaComplementoId, fel_facturas_complemento.tipoComplemento, fel_facturas_complemento.complementoFactura')
          ->where('fel_facturas_complemento.flgElimina', 0)
          ->where('fel_facturas_complemento.facturaId',$facturaId)
          ->findAll();
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Tipo:</b> " . $columna['tipoComplemento'] . "<br><b>Descripción :</b> " . $columna['complementoFactura'];

            $columna3 = '
                <button type="button" class="btn btn-danger mb-1" onclick="eliminarDTEComplemento(`'.$columna["facturaComplementoId"].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash-alt"></i>
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

        public function eliminarDTEComplemento(){
        
        $eliminarDTEComplemento = new fel_facturas_complemento();
        
        $facturaComplementoId = $this->request->getPost('facturaComplementoId');
        $data = ['flgElimina' => 1];
        
        $eliminarDTEComplemento->update($facturaComplementoId, $data);
    
        if($eliminarDTEComplemento) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Complemento eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el complemento del dte'
            ]);
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
