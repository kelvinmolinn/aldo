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
use App\Models\comp_retaceo_detalle;
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
use App\Models\fel_cliente_contacto;
use App\Models\fel_factura_contingencia;
use App\Models\fel_factura_contingencia_detalle;
use App\Models\cat_05_tipo_contingencia;
use App\Models\cat_07_tipo_generacion_documento;
use App\Models\fel_factura_relacionada;



class administracionFacturacion extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $parametrizacion = new conf_parametrizaciones();

        $session = session();

        $data['variable'] = 0;

        $data['activarContingencia'] = $parametrizacion
                ->select('valorParametrizacion')
                ->where('flgElimina', 0)
                ->where('parametrizacionId', 6)
                ->first();

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
        ->select('fel_facturas.facturaId, DATE_FORMAT(fel_facturas.fechaEmision, "%d/%m/%Y") as fechaEmision, fel_facturas.obsAnulacion, fel_facturas.estadoFactura, 
                  conf_sucursales.sucursalId, conf_sucursales.sucursal, 
                  fel_clientes.clienteId, fel_clientes.cliente, fel_clientes.nrcCliente, fel_clientes.numDocumentoIdentificacion, fel_clientes.direccionCliente, 
                  conf_empleados.empleadoId, conf_empleados.primerNombre, conf_empleados.primerApellido, 
                  cat_02_tipo_dte.tipoDTEId, cat_02_tipo_dte.tipoDocumentoDTE,
                  fel_factura_certificacion.codigoGeneracion, fel_factura_certificacion.numeroControl')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
        ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
        ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
        ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
        ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaId = fel_facturas.facturaId AND fel_factura_certificacion.estadoCertificacion = \'Certificado\'', 'left')
        ->where('fel_facturas.flgElimina', 0)
        ->orderBy('fel_factura_certificacion.facturaCertificacionId', 'DESC')
        ->limit(1)  // Agrega el límite aquí
        ->findAll();

    $output['data'] = array();
    $n = 1;

    foreach ($datos as $columna) {
        $estadoClase = '';
        if ($columna['estadoFactura'] === 'Pendiente') {
            $estadoClase = 'badge badge-secondary';
        } elseif ($columna['estadoFactura'] === 'Certificado') {
            $estadoClase = 'badge badge-success';
        } elseif ($columna['estadoFactura'] === 'Anulado') {
            $estadoClase = 'badge badge-danger';
        } elseif ($columna['estadoFactura'] === 'Invalidado') {
            $estadoClase = 'badge badge-danger';
        }

        $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Vendedor:</b> " . $columna['primerNombre'] . " " . $columna['primerApellido'] . "<br><b>Código de generación:</b> " . $columna['codigoGeneracion'] . "<br><b>Número de control:</b> " . $columna['numeroControl'];

        $columna3 = "<b>Fecha:</b> " . $columna['fechaEmision'] . "<br><b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoFactura'] . "</span>";
        if ($columna['estadoFactura'] === 'Anulado') {
            $columna3 .= "<br><b>Obs Anulación:</b> " . $columna['obsAnulacion'];
        }

        $columna4 = "<b>Cliente:</b> " . $columna['cliente'] . "<br><b>NRC:</b> " . $columna['nrcCliente'] . "<br><b>Dirección:</b> " . $columna['direccionCliente']. "<br><b>Tipo DTE:</b> " . $columna['tipoDocumentoDTE'];

        // Inicializar variables para cálculos de totales
        $subtotal = 0;
        $ivaTotal = 0;
        $totalAPagar = 0;
        $descuentos = 0;

        // Obtener los detalles de la factura
        $detalleModel = new fel_facturas_detalle();
        $detallesFactura = $detalleModel
            ->where('facturaId', $columna['facturaId'])
            ->where('flgElimina', 0)
            ->findAll();
            $parametrizacion = new conf_parametrizaciones();
        
            $contingenciaActivada = $parametrizacion
                      ->select('valorParametrizacion')
                      ->where('flgElimina', 0)
                      ->where('parametrizacionId', 6)
                      ->first();
        // Calcular los totales
        foreach ($detallesFactura as $detalle) {
            $subtotal += $detalle['totalDetalle'];
            $ivaTotal += $detalle['ivaTotal'];
            $totalAPagar += $detalle['totalDetalleIVA'];
            $descuentos += ($detalle['precioUnitario'] - $detalle['precioUnitarioVenta']) * $detalle['cantidadProducto'];
        }

        // Añadir los totales en la columna 5
        $columna5 = "<b>(=)Subtotal:</b> $ " . number_format($subtotal, 2, '.', ',') . "<br>"
                  . "<b>(+)IVA:</b> $ " . number_format($ivaTotal, 2, '.', ',') . "<br>"
                  . "<b>(-)Descuentos:</b> $ " . number_format($descuentos, 2, '.', ',') . "<br>"
                  . "<b>(=)Total a Pagar:</b> $ " . number_format($totalAPagar, 2, '.', ',');
        


        if ($columna['estadoFactura'] === 'Pendiente') {


            $jsonActualizarReserva = [
                "facturaId"     => $columna['facturaId'],
                "contingencia"  => $contingenciaActivada['valorParametrizacion']
            ];

            $columna6 = '
                <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-facturacion/vista/continuar/dte`, ' . htmlspecialchars(json_encode($jsonActualizarReserva)) . ');" data-toggle="tooltip" data-placement="top" title="Continuar DTE">
                    <i class="fas fa-sync-alt"></i> <span> </span>
                </button>

                <button class="btn btn-danger mb-1" onclick="modalAnularDTE(' . $columna['facturaId'] . ')" data-toggle="tooltip" data-placement="top" title="Anular">
                    <i class="fas fa-ban"></i>
                </button>
            ';
        } elseif ($columna['estadoFactura'] === 'Certificado') {
            $columna6 = '

                <button class="btn btn-info mb-1" onclick="modalVerDTE(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver DTE">
                    <i class="fas fa-eye"></i><span> </span>
                </button>

                <button type="button" class="btn btn-primary mb-1" onclick="modalImprimirDTE('. $columna['facturaId'] .')" data-toggle="tooltip" data-placement="top" title="Imprimir DTE">
                    <i class="fas fa-print"></i>
                </button>

                <button type="button" class="btn btn-primary mb-1" onclick="window.open(`https://admin.factura.gob.sv/consultaPublica`,`_blank`)" data-toggle="tooltip" data-placement="top" title="Consultar DTE">
                    <i class="fas fa-file-alt"></i>
                </button>

                <button class="btn btn-info mb-1" onclick="modalVerJSON(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver JSON">
                    <i class="fas fa-file-code"></i><span> </span>
                </button>

              
                <button type="button" class="btn btn-danger mb-1" onclick="invalidarDTE(' . $columna['facturaId'] . ')" data-toggle="tooltip" data-placement="top" title="Invalidar DTE">
                    <i class="fas fa-ban"></i>
                </button>';
        }elseif ($columna['estadoFactura'] === 'Invalidado') {
            $columna6 = '

                <button class="btn btn-info mb-1" onclick="modalVerDTE(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver DTE">
                    <i class="fas fa-eye"></i><span> </span>
                </button>';
        }  else {
            $columna6 = '
                <button class="btn btn-info mb-1" onclick="modalVerDTE(`' . $columna['facturaId'] . '`);" data-toggle="tooltip" data-placement="top" title="Ver DTE">
                    <i class="fas fa-eye"></i><span> </span>
                </button>
            ';
        }

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
        $contingencia = $this->request->getPost('contingencia');
        $camposSession = [
            'renderVista' => 'No',
            'facturaId'    => $facturaId
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/vista/continuar/dte',
            'camposSession'     => json_encode($camposSession)
        ]);

        $data['facturaId'] = $facturaId;
        $data['contingencia'] = $contingencia;
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

    // Calcular el descuento total para la factura
    $descuentoTotal = $precioUnitario * ($porcentajeDescuento / 100) * $cantidadProducto;

    // Obtener sucursalId de fel_facturas 
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


public function tablaContinuarDTE() {
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
    $n = 1;

    $subtotal = 0;
    $ivaTotal = 0;
    $totalAPagar = 0;
    $descuentos = 0;

    foreach ($datos as $columna) {
        $columna1 = $n;
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

        $subtotal += $columna['totalDetalle'];
        $ivaTotal += $columna['ivaTotal'];
       $totalAPagar += number_format($columna['totalDetalleIVA'], 2, '.', ',');
        $descuentos += ($columna['precioUnitario'] - $columna['precioUnitarioVenta']) * $columna['cantidadProducto'];

        $n++;
    }

    $DTEPago = new fel_facturas_pago();
    $pagosReserva = $DTEPago
        ->select('COUNT(*) as numeroPagos, SUM(totalPago) as totalPagado')
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->first();

    $numeroPagos = $pagosReserva['numeroPagos'];
    $totalPagado = $pagosReserva['totalPagado'] ?? 0;

    $totalPagadoClass = ($totalPagado < $totalAPagar) ? 'text-danger' : 'text-success';
    $botonTexto = ($totalPagado < $totalAPagar) ? 'Pagos (' . $numeroPagos . ')' : 'Pagado';
    $botonClase = ($totalPagado < $totalAPagar) ? 'btn-primary' : 'btn-success';
    $botonDisabled = ($totalPagado < $totalAPagar) ? '' : 'disabled';

// Nueva consulta para contar los errores de certificación
$DTEErrores = new fel_facturas_certificacion_errores();
$erroresCertificacion = $DTEErrores
    ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaCertificacionId = fel_facturas_certificacion_errores.facturaCertificacionId')
    ->where('fel_factura_certificacion.facturaId', $facturaId)
    ->countAllResults();

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
                    <button type= "button" class="btn btn-danger mb-1" onclick="modalErrorDTE(`' . $facturaId . '`)" data-toggle="tooltip" data-placement="top" title="Error de certificación">
                        <i class="fas fa-ban"></i> Errores de certificación (' . $erroresCertificacion . ')
                    </button>
                </div>
            </div>
        ';
        return $this->response->setJSON($output);
    } else {
        return $this->response->setJSON(array('data' => '', 'footer' => ''));
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


public function certificarDTE()
{
    // Intentar obtener los valores desde la solicitud POST
    $facturaId = $this->request->getPost('facturaId');
    $facturaDetalleId = $this->request->getPost('facturaDetalleId');
    $retaceoDetalleId = $this->request->getPost('retaceoDetalleId'); 
    
    // Verificar si el ID de la factura está disponible
    if (!$facturaId) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo obtener el ID de la factura.',
        ]);
    }

    // Obtener sucursalId y fechaEmision desde fel_facturas
    $facturaModel = new fel_facturas();
    $factura = $facturaModel
        ->select('sucursalId, fechaEmision')
        ->where('facturaId', $facturaId)
        ->first();

    // Verificar si la factura existe
    if (!$factura) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Factura no encontrada.',
        ]);
    }

    // Extraer sucursalId y fechaEmision
    $sucursalId = $factura['sucursalId'];
    $fechaEmision = $factura['fechaEmision'];

    // Obtener el modelo para manejar los productos, existencias y costos
    $productosExistenciasModel = new inv_productos_existencias();
    $detalleModel = new fel_facturas_detalle();
    $modelKardex = new inv_kardex();
    $productosInfoModel = new inv_productos();
    $comprasDetalleModel = new comp_compras_detalle();
    $retaceoDetalleModel = new comp_retaceo_detalle();
    $modelFacturaPago = new fel_facturas_pago();

    // Obtener los detalles de los productos asociados a la factura
    $productosFactura = $detalleModel
        ->select('productoId, cantidadProducto, precioUnitarioVenta, porcentajeDescuento')
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->findAll();

    // Verificar si no se han agregado productos al detalle
    if (empty($productosFactura)) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se puede certificar el DTE. No se han agregado productos al detalle de la factura.'
        ]);
    }

    foreach ($productosFactura as $producto) {
        $productoId = $producto['productoId'];
        $cantidadProducto = $producto['cantidadProducto'];
   
        // Obtener la existencia actual del producto en la sucursal
        $productoExistencia = $productosExistenciasModel
            ->select('productoExistenciaId, existenciaProducto')
            ->where('sucursalId', $sucursalId)
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->first();

        if (!$productoExistencia || $cantidadProducto > $productoExistencia['existenciaProducto']) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => "No hay existencias suficientes para el producto ID $productoId en la sucursal $sucursalId.",
            ]);
        }

        // Obtener datos adicionales del producto
        $productoInfo = $productosInfoModel->find($productoId);

        // Obtener el costo FOB
        $costoFOBResult = $comprasDetalleModel
            ->select('precioUnitario')
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->orderBy('compraDetalleId', 'DESC')
            ->first();
        
        $costoFOB = $costoFOBResult ? $costoFOBResult['precioUnitario'] : 0;

        // Obtener el costo promedio y el precio de venta del producto
        $costoPromedio = $productoInfo ? $productoInfo['CostoPromedio'] : 0;
        $precioVentaUnitario = $productoInfo ? $productoInfo['precioVenta'] : 0;

        // Verificar si se obtuvo retaceoDetalleId y obtener costo unitario retaceo
        if ($retaceoDetalleId) {
            $retaceoInfo = $retaceoDetalleModel->find($retaceoDetalleId);
            $costoUnitarioRetaceo = $retaceoInfo ? $retaceoInfo['costoUnitarioRetaceo'] : 0;
        } else {
            $costoUnitarioRetaceo = 0;
        }

        // Calcular valores para la entrada del Kardex
        $existenciaAntes = $productoExistencia['existenciaProducto'];
        $existenciaDespues = $existenciaAntes - $cantidadProducto;
        $precioVentaUnitarioConDescuento = $producto['precioUnitarioVenta'] * (1 - $producto['porcentajeDescuento'] / 100);

            // Obtener el total a pagar para la reserva
            $totalAPagar = $detalleModel
                ->select('SUM(totalDetalleIVA) as totalAPagar')
                ->where('facturaId', $facturaId)
                ->where('flgElimina', 0)
                ->first()['totalAPagar'];

            // Obtener el total pagado 
            $totalPagado = $modelFacturaPago
                ->select('SUM(totalPago) as totalPagado')
                ->where('facturaId', $facturaId)
                ->where('flgElimina', 0)
                ->first()['totalPagado'];

            // Asegurarse de que ambos valores tengan dos decimales
            $totalAPagar = round($totalAPagar, 2);
            $totalPagado = round($totalPagado, 2);

            // Validar que el total pagado sea mayor o igual al total a pagar
            if ($totalPagado < $totalAPagar) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se puede certificar el DTE. El total pagado es menor al total a pagar.'
                ]);
            }

        // Insertar en Kardex
        $modelKardex->insert([
            'tipoMovimiento' => 'Salida',
            'descripcionMovimiento' => "Salida registrada por emisión de DTE: $facturaId",
            'productoExistenciaId' => $productoExistencia['productoExistenciaId'],
            'existenciaAntesMovimiento' => $existenciaAntes,
            'cantidadMovimiento' => $cantidadProducto,
            'existenciaDespuesMovimiento' => $existenciaDespues,
            'costoUnitarioFOB' => $costoFOB,
            'costoUnitarioRetaceo' => $costoUnitarioRetaceo,
            'costoPromedio' => $costoPromedio,
            'precioVentaUnitario' => $precioVentaUnitarioConDescuento,
            'fechaDocumento' => $fechaEmision, // Usar la fechaEmision obtenida
            'fechaMovimiento' => date('Y-m-d H:i:s'),
            'tablaMovimiento' => 'fel_factura_detalle',
            'tablaMovimientoId' => $facturaDetalleId
        ]);

        // Actualizar la existencia en inv_productos_existencias
        $productosExistenciasModel->update($productoExistencia['productoExistenciaId'], [
            'existenciaProducto' => $existenciaDespues
        ]);
    }

    // Obtener datos de la sucursal
    $confSucursalModel = new conf_sucursales();
    $sucursalData = $confSucursalModel->find($sucursalId);
    $codEstablecimientoMH = $sucursalData['codEstablecimientoMH'];
    $puntoVentaMH = $sucursalData['puntoVentaMH'];

    // Obtener el codigoMH del tipo de DTE (asumimos que tienes $tipoDTEId)
    $tipoDTEId = 1; // Asumimos un valor para $tipoDTEId, ajusta según tu necesidad
    $tipoDTEModel = new cat_02_tipo_dte();
    $tipoDTEData = $tipoDTEModel->find($tipoDTEId);
    $codigoMH = $tipoDTEData['codigoMH'];

    // Generar numeroControl
    $numeroControl = "DTE-{$codigoMH}-{$codEstablecimientoMH}-{$puntoVentaMH}" . str_pad($facturaId, 15, '0', STR_PAD_LEFT);

    // Generar codigoGeneracion usando la función codigo de generacion() y convertir a mayúsculas
    $codigoGeneracion = strtoupper($this->codigoGeneracion());

    // Simular selloRecibido y convertir a mayúsculas
    $selloRecibido = strtoupper("REC-" . bin2hex(random_bytes(10)) . "-MH");

    // Insertar en fel_factura_certificacion
    $certificacionModel = new fel_factura_certificacion();

    $certificacionModel->insert([
        'facturaId' => $facturaId,
        'numeroControl' => $numeroControl,
        'codigoGeneracion' => $codigoGeneracion,
        'tipoTransmisionMHId' => 1,  // Valor estático según lo especificado
        'selloRecibido' => $selloRecibido,
        'descripcionMensaje' => 'Recibido',
        'estadoCertificacion' => 'Certificado'
    ]);

    // Actualizar el estado de la reserva
    $dataReservaEstado = [
        'estadoFactura' => "Certificado"
    ];
    $facturaModel->update($facturaId, $dataReservaEstado);

    // Retornar la respuesta exitosa
    return $this->response->setJSON([
        'success' => true,
        'mensaje' => 'Certificación de DTE exitosa'
    ]);
}
private function codigoGeneracion() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

public function certificarDTEError()
{
    // Intentar obtener los valores desde la solicitud POST
    $facturaId = $this->request->getPost('facturaId');
    $facturaDetalleId = $this->request->getPost('facturaDetalleId');
    $retaceoDetalleId = $this->request->getPost('retaceoDetalleId'); 
    
    // Verificar si el ID de la factura está disponible
    if (!$facturaId) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo obtener el ID de la factura.',
        ]);
    }

    // Obtener sucursalId y fechaEmision desde fel_facturas
    $facturaModel = new fel_facturas();
    $factura = $facturaModel
        ->select('sucursalId, fechaEmision')
        ->where('facturaId', $facturaId)
        ->first();

    // Verificar si la factura existe
    if (!$factura) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Factura no encontrada.',
        ]);
    }

    // Extraer sucursalId y fechaEmision
    $sucursalId = $factura['sucursalId'];
    $fechaEmision = $factura['fechaEmision'];

    // Obtener el modelo para manejar los productos, existencias y costos
    $productosExistenciasModel = new inv_productos_existencias();
    $detalleModel = new fel_facturas_detalle();
    $modelKardex = new inv_kardex();
    $productosInfoModel = new inv_productos();
    $comprasDetalleModel = new comp_compras_detalle();
    $retaceoDetalleModel = new comp_retaceo_detalle();
    
    // Obtener los detalles de los productos asociados a la factura
    $productosFactura = $detalleModel
        ->select('productoId, cantidadProducto, precioUnitarioVenta, porcentajeDescuento')
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->findAll();

    foreach ($productosFactura as $producto) {
        $productoId = $producto['productoId'];
        $cantidadProducto = $producto['cantidadProducto'];
   
        // Obtener la existencia actual del producto en la sucursal
        $productoExistencia = $productosExistenciasModel
            ->select('productoExistenciaId, existenciaProducto')
            ->where('sucursalId', $sucursalId)
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->first();

        if (!$productoExistencia || $cantidadProducto > $productoExistencia['existenciaProducto']) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => "No hay existencias suficientes para el producto ID $productoId en la sucursal $sucursalId.",
            ]);
        }

        // Obtener datos adicionales del producto
        $productoInfo = $productosInfoModel->find($productoId);

        // Obtener el costo FOB
        $costoFOBResult = $comprasDetalleModel
            ->select('precioUnitario')
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->orderBy('compraDetalleId', 'DESC')
            ->first();
        
        $costoFOB = $costoFOBResult ? $costoFOBResult['precioUnitario'] : 0;

        // Obtener el costo promedio y el precio de venta del producto
        $costoPromedio = $productoInfo ? $productoInfo['CostoPromedio'] : 0;
        $precioVentaUnitario = $productoInfo ? $productoInfo['precioVenta'] : 0;

        // Verificar si se obtuvo retaceoDetalleId y obtener costo unitario retaceo
        if ($retaceoDetalleId) {
            $retaceoInfo = $retaceoDetalleModel->find($retaceoDetalleId);
            $costoUnitarioRetaceo = $retaceoInfo ? $retaceoInfo['costoUnitarioRetaceo'] : 0;
        } else {
            $costoUnitarioRetaceo = 0;
        }

        // Calcular valores para la entrada del Kardex
        $existenciaAntes = $productoExistencia['existenciaProducto'];
        $existenciaDespues = $existenciaAntes - $cantidadProducto;
        $precioVentaUnitarioConDescuento = $producto['precioUnitarioVenta'] * (1 - $producto['porcentajeDescuento'] / 100);

        /*
        // Insertar en Kardex
        $modelKardex->insert([
            'tipoMovimiento' => 'Salida',
            'descripcionMovimiento' => "Salida registrada por emisión de DTE: $facturaId",
            'productoExistenciaId' => $productoExistencia['productoExistenciaId'],
            'existenciaAntesMovimiento' => $existenciaAntes,
            'cantidadMovimiento' => $cantidadProducto,
            'existenciaDespuesMovimiento' => $existenciaDespues,
            'costoUnitarioFOB' => $costoFOB,
            'costoUnitarioRetaceo' => $costoUnitarioRetaceo,
            'costoPromedio' => $costoPromedio,
            'precioVentaUnitario' => $precioVentaUnitarioConDescuento,
            'fechaDocumento' => $fechaEmision, // Usar la fechaEmision obtenida
            'fechaMovimiento' => date('Y-m-d H:i:s'),
            'tablaMovimiento' => 'fel_factura_detalle',
            'tablaMovimientoId' => $facturaDetalleId
        ]);

        // Actualizar la existencia en inv_productos_existencias
        $productosExistenciasModel->update($productoExistencia['productoExistenciaId'], [
            'existenciaProducto' => $existenciaDespues
        ]);
        */
    }

    // Obtener datos de la sucursal
    $confSucursalModel = new conf_sucursales();
    $sucursalData = $confSucursalModel->find($sucursalId);
    $codEstablecimientoMH = $sucursalData['codEstablecimientoMH'];
    $puntoVentaMH = $sucursalData['puntoVentaMH'];

    // Obtener el codigoMH del tipo de DTE (asumimos que tienes $tipoDTEId)
    $tipoDTEId = 1; // Asumimos un valor para $tipoDTEId, ajusta según tu necesidad
    $tipoDTEModel = new cat_02_tipo_dte();
    $tipoDTEData = $tipoDTEModel->find($tipoDTEId);
    $codigoMH = $tipoDTEData['codigoMH'];

    // Generar numeroControl
    $numeroControl = "DTE-{$codigoMH}-{$codEstablecimientoMH}-{$puntoVentaMH}" . str_pad($facturaId, 15, '0', STR_PAD_LEFT);

    // Generar codigoGeneracion usando la función codigo de generacion() y convertir a mayúsculas
    $codigoGeneracion = strtoupper($this->codigoGeneracionError());

    // Simular selloRecibido y convertir a mayúsculas
    $selloRecibido = strtoupper("REC-" . bin2hex(random_bytes(10)) . "-MH");

    // Insertar en fel_factura_certificacion
    $certificacionModel = new fel_factura_certificacion();
    $certificacionModel->insert([
        'facturaId' => $facturaId,
        'numeroControl' => $numeroControl,
        'codigoGeneracion' => $codigoGeneracion,
        'tipoTransmisionMHId' => 1,  // Valor estático según lo especificado
        'selloRecibido' => $selloRecibido,
        'descripcionMensaje' => 'DOCUMENTO NO CUMPLE ESQUEMA JSON',
        'estadoCertificacion' => 'Rechazado'
    ]);


    // Obtener el ID del insert recién hecho en fel_factura_certificacion
    $facturaCertificacionId = $certificacionModel->getInsertID();

    // Insertar en fel_facturas_certificacion_errores
    $certificacionErroresModel = new fel_facturas_certificacion_errores();
    $certificacionErroresModel->insert([
        'facturaCertificacionId' => $facturaCertificacionId,
        'codigoError' => 96,  // Sin comillas porque es un valor numérico
        'descripcionError' => 'DOCUMENTO NO CUMPLE ESQUEMA JSON',
        'obsError' => 'Campo #/receptor contiene un valor inválido'
    ]);

    // Retornar la respuesta exitosa
    return $this->response->setJSON([
        'success' => true,
        'mensaje' => 'Certificación con error'
    ]);
}


private function codigoGeneracionError() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

    public function modalErrorDTE(){

        $data['facturaId'] = $this->request->getPost('facturaId');
        return view('ventas/modals/modalErrorDTE', $data);
    }


    public function tablaErrorDTE(){
        $facturaId    = $this->request->getPost('facturaId');
        $mostrarError = new fel_facturas_certificacion_errores();
        $datos = $mostrarError
            ->select('fel_facturas_certificacion_errores.facturaCertificacionErrorId,fel_facturas_certificacion_errores.obsError,fel_facturas_certificacion_errores.codigoError,fel_facturas_certificacion_errores.fhAgrega,fel_facturas_certificacion_errores.descripcionError,fel_factura_certificacion.facturaCertificacionId,fel_factura_certificacion.facturaId')
            ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaCertificacionId = fel_facturas_certificacion_errores.facturaCertificacionId')
            ->where('fel_facturas_certificacion_errores.flgElimina', 0)
            ->where('fel_factura_certificacion.facturaId',  $facturaId)
            ->findAll();
    
        $output['data'] = array();
        $countMinima = 0;
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            $columna1 = $n;
            $columna2 = $columna['descripcionError'];
            $columna3 = $columna['obsError'];
            $columna4 = $columna['codigoError'];
            $columna5 = $columna['fhAgrega'];
    
    
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
            );
    
            $n++;
        }
    
        $output['countMinima'] = $countMinima; // Añade el contador al output
    
        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'countMinima' => 0)); // No hay datos, devuelve un array vacío
        }
    }

public function invalidarDTE()
{
    // Obtener los valores desde la solicitud POST
    $facturaId = $this->request->getPost('facturaId');
    $facturaDetalleId = $this->request->getPost('facturaDetalleId');
    $retaceoDetalleId = $this->request->getPost('retaceoDetalleId'); 
    
    // Verificar si el ID de la factura está disponible
    if (!$facturaId) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo obtener el ID de la factura.',
        ]);
    }

    // Obtener datos de la factura
    $facturaModel = new fel_facturas();
    $factura = $facturaModel
        ->select('sucursalId, fechaEmision, tipoDTEId')
        ->where('facturaId', $facturaId)
        ->first();

    // Verificar si la factura existe
    if (!$factura) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Factura no encontrada.',
        ]);
    }

    // Extraer sucursalId, fechaEmision y tipoDTEId
    $sucursalId = $factura['sucursalId'];
    $fechaEmision = $factura['fechaEmision'];
    $tipoDTEId = $factura['tipoDTEId'];

    // Validar si se puede invalidar la factura
    $fechaActual = new \DateTime(); // Fecha actual
    $fechaEmisionDT = new \DateTime($fechaEmision); // Fecha de emisión

    if ($tipoDTEId == 2) { // Tipo DTE = 2 (Crédito fiscal)
        $intervalo = $fechaEmisionDT->diff($fechaActual);
        if ($intervalo->days > 1) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede invalidar un Crédito Fiscal después de un día.'
            ]);
        }
    } elseif ($tipoDTEId == 1) { // Tipo DTE = 1 (Factura)
        $intervalo = $fechaEmisionDT->diff($fechaActual);
        if ($intervalo->days > 90) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede invalidar una Factura después de 90 días.'
            ]);
        }
    }

    // Continuar con la invalidación como antes
    $productosExistenciasModel = new inv_productos_existencias();
    $detalleModel = new fel_facturas_detalle();
    $modelKardex = new inv_kardex();
    $productosInfoModel = new inv_productos();
    $comprasDetalleModel = new comp_compras_detalle();
    $retaceoDetalleModel = new comp_retaceo_detalle();
    $modelFacturaPago = new fel_facturas_pago();

    // Obtener los detalles de los productos asociados a la factura
    $productosFactura = $detalleModel
        ->select('productoId, cantidadProducto, precioUnitarioVenta, porcentajeDescuento')
        ->where('facturaId', $facturaId)
        ->where('flgElimina', 0)
        ->findAll();

    // Verificar si no se han agregado productos al detalle
    if (empty($productosFactura)) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se puede invalidar el DTE. No se han agregado productos al detalle de la factura.'
        ]);
    }

    foreach ($productosFactura as $producto) {
        $productoId = $producto['productoId'];
        $cantidadProducto = $producto['cantidadProducto'];
   
        // Obtener la existencia actual del producto en la sucursal
        $productoExistencia = $productosExistenciasModel
            ->select('productoExistenciaId, existenciaProducto')
            ->where('sucursalId', $sucursalId)
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->first();

        if (!$productoExistencia || $cantidadProducto > $productoExistencia['existenciaProducto']) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => "No hay existencias suficientes para el producto ID $productoId en la sucursal $sucursalId.",
            ]);
        }

        // Obtener datos adicionales del producto
        $productoInfo = $productosInfoModel->find($productoId);

        // Obtener el costo FOB
        $costoFOBResult = $comprasDetalleModel
            ->select('precioUnitario')
            ->where('productoId', $productoId)
            ->where('flgElimina', 0)
            ->orderBy('compraDetalleId', 'DESC')
            ->first();
        
        $costoFOB = $costoFOBResult ? $costoFOBResult['precioUnitario'] : 0;

        // Obtener el costo promedio y el precio de venta del producto
        $costoPromedio = $productoInfo ? $productoInfo['CostoPromedio'] : 0;
        $precioVentaUnitario = $productoInfo ? $productoInfo['precioVenta'] : 0;

        // Verificar si se obtuvo retaceoDetalleId y obtener costo unitario retaceo
        if ($retaceoDetalleId) {
            $retaceoInfo = $retaceoDetalleModel->find($retaceoDetalleId);
            $costoUnitarioRetaceo = $retaceoInfo ? $retaceoInfo['costoUnitarioRetaceo'] : 0;
        } else {
            $costoUnitarioRetaceo = 0;
        }

        // Calcular valores para la entrada del Kardex
        $existenciaAntes = $productoExistencia['existenciaProducto'];
        $existenciaDespues = $existenciaAntes + $cantidadProducto;
        $precioVentaUnitarioConDescuento = $producto['precioUnitarioVenta'] * (1 - $producto['porcentajeDescuento'] / 100);

        // Insertar en Kardex
        $modelKardex->insert([
            'tipoMovimiento' => 'Entrada',
            'descripcionMovimiento' => "Entrada registrada por invalidación de DTE: $facturaId",
            'productoExistenciaId' => $productoExistencia['productoExistenciaId'],
            'existenciaAntesMovimiento' => $existenciaAntes,
            'cantidadMovimiento' => $cantidadProducto,
            'existenciaDespuesMovimiento' => $existenciaDespues,
            'costoUnitarioFOB' => $costoFOB,
            'costoUnitarioRetaceo' => $costoUnitarioRetaceo,
            'costoPromedio' => $costoPromedio,
            'precioVentaUnitario' => $precioVentaUnitarioConDescuento,
            'fechaDocumento' => $fechaEmision, // Usar la fechaEmision obtenida
            'fechaMovimiento' => date('Y-m-d H:i:s'),
            'tablaMovimiento' => 'fel_factura',
            'tablaMovimientoId' => $facturaDetalleId
        ]);

        // Actualizar la existencia en inv_productos_existencias
        $productosExistenciasModel->update($productoExistencia['productoExistenciaId'], [
            'existenciaProducto' => $existenciaDespues
        ]);
    }

    // Obtener datos de la sucursal
    $confSucursalModel = new conf_sucursales();
    $sucursalData = $confSucursalModel->find($sucursalId);
    $codEstablecimientoMH = $sucursalData['codEstablecimientoMH'];
    $puntoVentaMH = $sucursalData['puntoVentaMH'];

    // Obtener el codigoMH del tipo de DTE (asumimos que tienes $tipoDTEId)
    $tipoDTEId = 1; // Asumimos un valor para $tipoDTEId, ajusta según tu necesidad
    $tipoDTEModel = new cat_02_tipo_dte();
    $tipoDTEData = $tipoDTEModel->find($tipoDTEId);
    $codigoMH = $tipoDTEData['codigoMH'];

    // Generar numeroControl
    $numeroControl = "DTE-{$codigoMH}-{$codEstablecimientoMH}-{$puntoVentaMH}" . str_pad($facturaId, 15, '0', STR_PAD_LEFT);

    // Generar codigoGeneracion usando la función codigo de generacion() y convertir a mayúsculas
    $codigoGeneracion = strtoupper($this->codigoInvalidarGeneracion());

    // Simular selloRecibido y convertir a mayúsculas
    $selloRecibido = strtoupper("REC-" . bin2hex(random_bytes(10)) . "-MH");

    // Insertar en fel_factura_certificacion
    $certificacionModel = new fel_factura_certificacion();
    $certificacionModel->insert([
        'facturaId' => $facturaId,
        'numeroControl' => $numeroControl,
        'codigoGeneracion' => $codigoGeneracion,
        'tipoTransmisionMHId' => 1,  // Valor estático según lo especificado
        'selloRecibido' => $selloRecibido,
        'descripcionMensaje' => 'Invalidado',
        'estadoCertificacion' => 'Invalidado'
    ]);

    // Actualizar el estado de la reserva
    $dataReservaEstado = [
        'estadoFactura' => "Invalidado"
    ];
    $facturaModel->update($facturaId, $dataReservaEstado);

    // Retornar la respuesta exitosa
    return $this->response->setJSON([
        'success' => true,
        'mensaje' => 'Invalidación de DTE exitosa'
    ]);
}

private function codigoInvalidarGeneracion() {
    if (function_exists('com_create_guid') === true) {
        return trim(com_create_guid(), '{}');
    }
    
    $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}



    public function imprimirDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalImprimirDTE', $data);
    }

    public function modalVerDTE(){
    $data["facturaId"] = $this->request->getPost('facturaId');

        return view('ventas/modals/modalVerDTE', $data);
    }

public function tablaVerDTE(){
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
    $n = 1;

    $subtotal = 0;
    $ivaTotal = 0;
    $totalAPagar = 0;
    $descuentos = 0;

    foreach ($datos as $columna) {
        $columna1 = $n;
        $conceptoTexto = !empty($columna['conceptoProducto']) ? "<br><b>Concepto :</b> " . $columna['producto'] . " ( " . $columna['conceptoProducto'] . " )" : "";
        $columna2 = "<b>Producto:</b> " . $columna['producto'] . "<br><b>Código :</b> " . $columna['codigoProducto'] . $conceptoTexto;
        $columna3 = "<b>sin IVA: </b> $" . number_format($columna['precioUnitario'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['precioUnitarioIVA'], 2, '.', ',');
        $columna4 = "<b>Procentaje: </b> " . number_format($columna['porcentajeDescuento'], 2, '.', ',') . "%" . "<br><b>Total :</b> $" . number_format($columna['descuentoTotal'], 2, '.', ',');
        $columna5 = "<b>Sin IVA: </b> $" . number_format($columna['precioUnitarioVenta'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['precioUnitarioVentaIVA'], 2, '.', ',');
        $columna6 = " <b>Cantidad: </b> " . $columna['cantidadProducto'] . " (" . $columna['unidadMedida'] . ")";
        $columna7 = "<b>unitario: </b> $" . number_format($columna['ivaUnitario'], 2, '.', ',') . "<br><b>total: </b> $" . number_format($columna['ivaTotal'], 2, '.', ',');
        $columna8 = "<b>Sin IVA: </b> $" . number_format($columna['totalDetalle'], 2, '.', ',') . "<br><b>Con IVA: </b> $" . number_format($columna['totalDetalleIVA'], 2, '.', ',');

        $columna9 = '
            <button class="btn btn-primary mb-1" onclick="modalProductoDTE(' . $columna['facturaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Editar" disabled>
                <i class="fas fa-pen"></i>
            </button>

            <button class="btn btn-info mb-1" onclick="modalConceptoDTE(' . $columna['facturaDetalleId'] . ', `editar`);" data-toggle="tooltip" data-placement="top" title="Concepto" disabled>
                <i class="fas fa-clipboard-list"></i>
            </button>

            <button class="btn btn-danger mb-1" onclick="eliminarDTE(' . $columna['facturaDetalleId'] . ');" data-toggle="tooltip" data-placement="top" title="Eliminar" disabled>
                <i class="fas fa-trash"></i>
            </button>
        ';

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

        $subtotal += $columna['totalDetalle'];
        $ivaTotal += $columna['ivaTotal'];
        $totalAPagar += $columna['totalDetalleIVA'];
        $descuentos += ($columna['precioUnitario'] - $columna['precioUnitarioVenta']) * $columna['cantidadProducto'];

        $n++;
    }

    $DTEPago = new fel_facturas_pago();
    $pagosReserva = $DTEPago
    ->select('COUNT(*) as numeroPagos, SUM(totalPago) as totalPagado')
    ->where('facturaId', $facturaId)
    ->where('flgElimina', 0)
    ->first();

    $numeroPagos = $pagosReserva['numeroPagos'];
    $totalPagado = $pagosReserva['totalPagado'] ?? 0;

    $totalPagadoClass = ($totalPagado < $totalAPagar) ? 'text-danger' : 'text-success';
    $botonTexto = ($totalPagado < $totalAPagar) ? 'Pagos (' . $numeroPagos . ')' : 'Pagado';
    $botonClase = ($totalPagado < $totalAPagar) ? 'btn-primary' : 'btn-success';

    // Nueva consulta para contar los errores de certificación
    $DTEErrores = new fel_facturas_certificacion_errores();
    $erroresCertificacion = $DTEErrores
    ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaCertificacionId = fel_facturas_certificacion_errores.facturaCertificacionId')
    ->where('fel_factura_certificacion.facturaId', $facturaId)
    ->countAllResults();

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
                    <button type="button" class="btn ' . $botonClase . ' mb-1" onclick="modalPagoDTE(`' . $facturaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Pagos" disabled>
                        <i class="fas fa-hand-holding-usd"></i> ' . $botonTexto . '
                    </button>
                </div>
            </div>

            <div class="row text-right">
                <div class="col-4">
                    <button type= "button" class="btn btn-primary mb-1" onclick="modalComplementoDTE(`' . $facturaId . '`, `editar`)" data-toggle="tooltip" data-placement="top" title="Complementos" disabled>
                        <i class="fas fa-clipboard-list"></i> Complementos
                    </button>
                </div>
                <div class="col-4">
                    <button type= "button" class="btn btn-danger mb-1" onclick="modalErrorDTE(`' . $facturaId . '`)" data-toggle="tooltip" data-placement="top" title="Error de certificación" disabled>
                        <i class="fas fa-ban"></i> Errores de certificación (' . $erroresCertificacion . ')
                    </button>
                </div>
            </div>
        ';
        return $this->response->setJSON($output);
    } else {
        return $this->response->setJSON(array('data' => '', 'footer' => ''));
    }
}

    public function modalverJSON(){
        $data["facturaId"] = $this->request->getPost('facturaId');

        return view('ventas/modals/modalVerJSON', $data);
    }



public function tablaVerJSON() {
    $facturaId = $this->request->getPost('facturaId');

    // Modelos para realizar las consultas
    $facturaModel = new fel_facturas(); 
    $certificacionModel = new fel_factura_certificacion();
    $clienteModel = new fel_clientes();
    $contactoModel = new fel_cliente_contacto();
    $detalleModel = new fel_facturas_detalle();
    $pagoModel = new fel_facturas_pago();

    // Obtener datos de la factura
    $factura = $facturaModel
        ->select('tipoDTEId, fechaEmision, horaEmision')
        ->where('facturaId', $facturaId)
        ->first();

    // Obtener datos de la certificación
    $certificacion = $certificacionModel
        ->select('numeroControl, codigoGeneracion, tipoTransmisionMHId, fhAgrega, selloRecibido')
        ->where('facturaId', $facturaId)
        ->first();

    // Verificar si se encontró la certificación
    if (!$certificacion) {
        return $this->response->setJSON(['error' => 'No se encontró la certificación'], 404);
    }

    // Obtener datos del cliente (receptor)
    $cliente = $clienteModel
        ->select('clienteId, tipoPersonaId, nrcCliente, documentoIdentificacionId, clienteComercial, numDocumentoIdentificacion, cliente, direccionCliente, actividadEconomicaId, tipoContribuyenteId, paisId, paisCiudadId, paisEstadoId')
        ->where('clienteId', function($query) use ($facturaId) {
            $query->select('clienteId')
                  ->from('fel_facturas')
                  ->where('facturaId', $facturaId)
                  ->limit(1);
        })
        ->first();

    // Obtener teléfono y correo electrónico
    $telefono = $contactoModel
        ->select('contactoCliente')
        ->where('clienteId', $cliente['clienteId'])
        ->where('tipoContactoId', 1)
        ->first();

    $correo = $contactoModel
        ->select('contactoCliente')
        ->where('clienteId', $cliente['clienteId'])
        ->where('tipoContactoId', 2)
        ->first();

    // Obtener detalles de la factura (cuerpo del documento)
    $detalles = $detalleModel
        ->select('fel_facturas_detalle.cantidadProducto, fel_facturas_detalle.codigoProducto, fel_facturas_detalle.porcentajeDescuento, fel_facturas_detalle.descuentoTotal, fel_facturas_detalle.tipoItemMHId, fel_facturas_detalle.precioUnitario, fel_facturas_detalle.precioUnitarioIVA, fel_facturas_detalle.ivaTotal, fel_facturas_detalle.ivaUnitario, fel_facturas_detalle.precioUnitarioVenta, fel_facturas_detalle.totalDetalleIVA, inv_productos.producto, inv_productos.descripcionProducto, inv_productos.unidadMedidaId')
        ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
        ->where('fel_facturas_detalle.facturaId', $facturaId)
        ->findAll();

    // Calcular los totales del resumen
    $totalGravada = 0;
    $totalIva = 0;
    $totalDescu = 0;
    $porcentajeDescuento = 0;

    foreach ($detalles as $detalle) {
        $totalGravada += $detalle['precioUnitario'] * $detalle['cantidadProducto'];
        $totalIva += $detalle['ivaTotal'];
        $totalDescu += $detalle['descuentoTotal'];
    }

    // Calcular porcentaje de descuento
    if ($totalGravada > 0) {
        $porcentajeDescuento = ($totalDescu / $totalGravada) * 100;
    }

    // Obtener pagos
    $pagos = $pagoModel
        ->select('formaPagoMHId as codigo, totalPago as montoPago')
        ->where('facturaId', $facturaId)
        ->findAll();

    $pagosData = array_map(function($pago) {
        return [
            "codigo" => $pago['codigo'],
            "montoPago" => number_format($pago['montoPago'], 2, '.', ','),
            "referencia" => null,
            "plazo" => "01",
            "periodo" => null
        ];
    }, $pagos);

    // Generar total en letras
    $totalEnLetras = $this->numeroALetras($totalGravada + $totalIva - $totalDescu);

    // Determinar el tipo de JSON según el tipoDTEId
    if ($factura['tipoDTEId'] == 1) {
        // JSON para tipoDTEId = 1
        $data = $this->generarJSONTipo1($factura, $certificacion, $cliente, $telefono, $correo, $detalles, $totalGravada, $totalIva, $totalDescu, $porcentajeDescuento, $totalEnLetras, $pagosData);
    } elseif ($factura['tipoDTEId'] == 2) {
        // JSON para tipoDTEId = 2
        $data = $this->generarJSONTipo2($factura, $certificacion, $cliente, $telefono, $correo, $detalles, $totalGravada, $totalIva, $totalDescu, $porcentajeDescuento, $totalEnLetras, $pagosData);
    }

    return $this->response->setJSON($data);
}

// Método para generar el JSON para tipoDTEId = 1
private function generarJSONTipo1($factura, $certificacion, $cliente, $telefono, $correo, $detalles, $totalGravada, $totalIva, $totalDescu, $porcentajeDescuento, $totalEnLetras, $pagosData) {
    return [
        "identificacion" => [
            "version" => 1,
            "ambiente" => "0",
            "tipoDte" => $factura['tipoDTEId'],
            "numeroControl" => $certificacion['numeroControl'],
            "codigoGeneracion" => strtoupper($certificacion['codigoGeneracion']),
            "tipoModelo" => 1,
            "tipoOperacion" => 1,
            "tipoContingencia" => null,
            "motivoContin" => null,
            "fecEmi" => date('Y-m-d', strtotime($factura['fechaEmision'])),
            "horEmi" => date('H:i:s', strtotime($factura['horaEmision'])),
            "tipoMoneda" => "USD"
        ],
        "emisor" => [
            "nit" => "03863624-1",
            "nrc" => "329956-5",
            "nombre" => "BELTRAN. ABIGAIL ELIZABETH",
            "codActividad" => 502,
            "descActividad" => "VENTA AL POR MENOR DE OTROS PRODUCTOS N.C.P",
            "nombreComercial" => "ALDO GAMES STORE",
            "direccion" => [
                "departamento" => 6,
                "municipio" => 214,
                "complemento" => "POLIG. B, RES. LOS ELISEOS #9, SAN SALVADOR, SAN SALVADOR"
            ],
            "telefono" => "79221469",
            "correo" => "aldogamesstore@gmail.com"
        ],
        "receptor" => [
            "tipoDocumento" => $cliente['documentoIdentificacionId'],
            "numDocumento" => $cliente['numDocumentoIdentificacion'],
            "nombre" => $cliente['cliente'],
            "codActividad" => $cliente['actividadEconomicaId'],
            "direccion" => [
                "departamento" => $cliente['paisCiudadId'],
                "municipio" => $cliente['paisEstadoId'],
                "complemento" => $cliente['direccionCliente']
            ],
            "telefono" => $telefono ? $telefono['contactoCliente'] : null,
            "correo" => $correo ? $correo['contactoCliente'] : null,
        ],
        "cuerpoDocumento" => array_map(function($detalle) {
            return [
                "cantidad" => $detalle['cantidadProducto'],
                "numeroDocumento" => null,
                "codigo" => $detalle['codigoProducto'],
                "codTributo" => null,
                "tipoItem" => $detalle['tipoItemMHId'],
                "uniMedida" => $detalle['unidadMedidaId'],
                "descripcion" => $detalle['producto'],
                "precioUni" => $detalle['precioUnitario'],
                "montoDescu" => $detalle['descuentoTotal'],
                "ventaNoSuj" => 0,
                "ventaExenta" => 0,
                "ventaGravada" => $detalle['totalDetalleIVA'],
                "tributo" => null,
                "ivaItem" => $detalle['ivaUnitario']
            ];
        }, $detalles),
        "resumen" => [
            "totalNoSuj" => 0,
            "totalExenta" => 0,
            "totalGravada" => number_format($totalGravada, 2, '.', ','),
            "subTotalVentas" => number_format($totalGravada, 2, '.', ','),
            "descuNoSuj" => 0,
            "descuExenta" => 0,
            "descuGravada" => number_format($totalDescu, 2, '.', ','),
            "porcentajeDescuento" => number_format($porcentajeDescuento, 2, '.', ','),
            "totalDescu" => number_format($totalDescu, 2, '.', ','),
            "tributos" => null,
            "subTotal" => number_format($totalGravada, 2, '.', ','),
            "ivaRete1" => 0,
            "reteRenta" => 0,
            "montoTotalOperacion" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
            "totalNoGravado" => 0,
            "totalPagar" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
            "totalLetras" => $totalEnLetras,
            "totalIva" => number_format($totalIva, 2, '.', ','),
            "saldoFavor" => 0,
            "condicionOperacion" => 1,
            "pagos" => $pagosData,
            "numPagoElectronico" => null
        ]
    ];
}

// Método para generar el JSON para tipoDTEId = 2
private function generarJSONTipo2($factura, $certificacion, $cliente, $telefono, $correo, $detalles, $totalGravada, $totalIva, $totalDescu, $porcentajeDescuento, $totalEnLetras, $pagosData) {
    return [
        "identificacion" => [
            "version" => 3,
            "ambiente" => "01",
            "tipoDte" => "02",
            "numeroControl" => $certificacion['numeroControl'],
            "codigoGeneracion" => strtoupper($certificacion['codigoGeneracion']),
            "tipoModelo" => 1,
            "tipoOperacion" => 1,
            "tipoContingencia" => null,
            "motivoContin" => null,
            "fecEmi" => date('Y-m-d', strtotime($factura['fechaEmision'])),
            "horEmi" => date('H:i:s', strtotime($factura['horaEmision'])),
            "tipoMoneda" => "USD"
        ],
        "emisor" => [
            "nit" => "03863624-1",
            "nrc" => "329956-5",
            "nombre" => "BELTRAN. ABIGAIL ELIZABETH",
            "codActividad" => 502,
            "descActividad" => "VENTA AL POR MENOR DE OTROS PRODUCTOS N.C.P",
            "nombreComercial" => "ALDO GAMES STORE",
            "direccion" => [
                "departamento" => 6,
                "municipio" => 214,
                "complemento" => "POLIG. B, RES. LOS ELISEOS #9, SAN SALVADOR, SAN SALVADOR"
            ],
            "telefono" => "79221469",
            "correo" => "aldogamesstore@gmail.com"
        ],
        "receptor" => [
            "nit" => $cliente['numDocumentoIdentificacion'],
            "nrc" => $cliente['nrcCliente'],
            "nombre" => $cliente['cliente'],
            "codActividad" => $cliente['actividadEconomicaId'],
            "descActividad" => "Cría de aves de corral y producción de huevos",
            "nombreComercial" => $cliente['clienteComercial'],
            "direccion" => [
                "departamento" => $cliente['paisCiudadId'],
                "municipio" => $cliente['paisEstadoId'],
                "complemento" => $cliente['direccionCliente']
            ],
            "telefono" => $telefono ? $telefono['contactoCliente'] : null,
            "correo" => $correo ? $correo['contactoCliente'] : null,
        ],
        "cuerpoDocumento" => array_map(function($detalle) {
            return [
                "numItem" => 1,
                "tipoItem" => $detalle['tipoItemMHId'],
                "numeroDocumento" => null,
                "cantidad" => $detalle['cantidadProducto'],
                "codigo" => $detalle['codigoProducto'],
                "uniMedida" => $detalle['unidadMedidaId'],
                "descripcion" => $detalle['producto'],
                "precioUni" => $detalle['precioUnitario'],
                "montoDescu" => $detalle['descuentoTotal'],
                "ventaGravada" => $detalle['totalDetalleIVA'],
                "tributos" => ["20"], // Tributos asumidos
                "psv" => 0,
                "noGravado" => 0
            ];
        }, $detalles),
        "resumen" => [
            "totalNoSuj" => 0,
            "totalExenta" => 0,
            "totalGravada" => number_format($totalGravada, 2, '.', ','),
            "subTotalVentas" => number_format($totalGravada, 2, '.', ','),
            "descuNoSuj" => 0,
            "descuExenta" => 0,
            "descuGravada" => number_format($totalDescu, 2, '.', ','),
            "porcentajeDescuento" => number_format($porcentajeDescuento, 2, '.', ','),
            "totalDescu" => number_format($totalDescu, 2, '.', ','),
            "tributos" => [
                [
                    "codigo" => "20",
                    "descripcion" => "Impuesto al Valor Agregado 13%",
                    "valor" => number_format($totalIva, 2, '.', ',')
                ]
            ],
            "subTotal" => number_format($totalGravada, 2, '.', ','),
            "ivaPerci1" => 17.5,
            "ivaRete1" => 0,
            "reteRenta" => 0,
            "montoTotalOperacion" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
            "totalNoGravado" => 0,
            "totalPagar" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
            "totalLetras" => $totalEnLetras,
            "saldoFavor" => 0,
            "condicionOperacion" => 1,
            "pagos" => $pagosData,
            "numPagoElectronico" => null
        ]
    ];
}


    // Función para convertir números a letras
    private function numeroALetras($numero) {
        $formatter = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
        $entero = floor($numero);
        $fraccion = round(($numero - $entero) * 100);

        $texto = $formatter->format($entero);
        $texto .= " dólares";

        if ($fraccion > 0) {
            $texto .= " con " . $formatter->format($fraccion) . " centavos";
        }

        return ucfirst($texto);
    }

    public function activarContingencia(){
        $parametrizacion = new conf_parametrizaciones();
        
        $data = [
            'valorParametrizacion'   => 2

        ];
            // Insertar datos en la base de datos
            $operacionContingencia = $parametrizacion->update(6, $data);

        if ($operacionContingencia) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Contingencia activada con éxito.',
                'parametrizacionId' => 6
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo activar la contingencia'
            ]);
        }

    }

    public function certificarContingenciaDTE(){
    // Intentar obtener los valores desde la solicitud POST
        $facturaId = $this->request->getPost('facturaId');
        $facturaDetalleId = $this->request->getPost('facturaDetalleId');
        $retaceoDetalleId = $this->request->getPost('retaceoDetalleId'); 
        
        // Verificar si el ID de la factura está disponible
        if (!$facturaId) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo obtener el ID de la factura.',
            ]);
        }

        // Obtener sucursalId y fechaEmision desde fel_facturas
        $facturaModel = new fel_facturas();
        $factura = $facturaModel
            ->select('sucursalId, fechaEmision')
            ->where('facturaId', $facturaId)
            ->first();

        // Verificar si la factura existe
        if (!$factura) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Factura no encontrada.',
            ]);
        }

        // Extraer sucursalId y fechaEmision
        $sucursalId = $factura['sucursalId'];
        $fechaEmision = $factura['fechaEmision'];

        // Obtener el modelo para manejar los productos, existencias y costos
        $productosExistenciasModel = new inv_productos_existencias();
        $detalleModel = new fel_facturas_detalle();
        $modelKardex = new inv_kardex();
        $productosInfoModel = new inv_productos();
        $comprasDetalleModel = new comp_compras_detalle();
        $retaceoDetalleModel = new comp_retaceo_detalle();
        $modelFacturaPago = new fel_facturas_pago();

        // Obtener los detalles de los productos asociados a la factura
        $productosFactura = $detalleModel
            ->select('productoId, cantidadProducto, precioUnitarioVenta, porcentajeDescuento')
            ->where('facturaId', $facturaId)
            ->where('flgElimina', 0)
            ->findAll();

        // Verificar si no se han agregado productos al detalle
        if (empty($productosFactura)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede certificar el DTE. No se han agregado productos al detalle de la factura.'
            ]);
        }

        foreach ($productosFactura as $producto) {
            $productoId = $producto['productoId'];
            $cantidadProducto = $producto['cantidadProducto'];
       
            // Obtener la existencia actual del producto en la sucursal
            $productoExistencia = $productosExistenciasModel
                ->select('productoExistenciaId, existenciaProducto')
                ->where('sucursalId', $sucursalId)
                ->where('productoId', $productoId)
                ->where('flgElimina', 0)
                ->first();

            if (!$productoExistencia || $cantidadProducto > $productoExistencia['existenciaProducto']) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => "No hay existencias suficientes para el producto ID $productoId en la sucursal $sucursalId.",
                ]);
            }

            // Obtener datos adicionales del producto
            $productoInfo = $productosInfoModel->find($productoId);

            // Obtener el costo FOB
            $costoFOBResult = $comprasDetalleModel
                ->select('precioUnitario')
                ->where('productoId', $productoId)
                ->where('flgElimina', 0)
                ->orderBy('compraDetalleId', 'DESC')
                ->first();
            
            $costoFOB = $costoFOBResult ? $costoFOBResult['precioUnitario'] : 0;

            // Obtener el costo promedio y el precio de venta del producto
            $costoPromedio = $productoInfo ? $productoInfo['CostoPromedio'] : 0;
            $precioVentaUnitario = $productoInfo ? $productoInfo['precioVenta'] : 0;

            // Verificar si se obtuvo retaceoDetalleId y obtener costo unitario retaceo
            if ($retaceoDetalleId) {
                $retaceoInfo = $retaceoDetalleModel->find($retaceoDetalleId);
                $costoUnitarioRetaceo = $retaceoInfo ? $retaceoInfo['costoUnitarioRetaceo'] : 0;
            } else {
                $costoUnitarioRetaceo = 0;
            }

            // Calcular valores para la entrada del Kardex
            $existenciaAntes = $productoExistencia['existenciaProducto'];
            $existenciaDespues = $existenciaAntes - $cantidadProducto;
            $precioVentaUnitarioConDescuento = $producto['precioUnitarioVenta'] * (1 - $producto['porcentajeDescuento'] / 100);

                // Obtener el total a pagar para la reserva
                $totalAPagar = $detalleModel
                    ->select('SUM(totalDetalleIVA) as totalAPagar')
                    ->where('facturaId', $facturaId)
                    ->where('flgElimina', 0)
                    ->first()['totalAPagar'];

                // Obtener el total pagado 
                $totalPagado = $modelFacturaPago
                    ->select('SUM(totalPago) as totalPagado')
                    ->where('facturaId', $facturaId)
                    ->where('flgElimina', 0)
                    ->first()['totalPagado'];

                // Asegurarse de que ambos valores tengan dos decimales
                $totalAPagar = round($totalAPagar, 2);
                $totalPagado = round($totalPagado, 2);

                // Validar que el total pagado sea mayor o igual al total a pagar
                if ($totalPagado < $totalAPagar) {
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se puede certificar el DTE. El total pagado es menor al total a pagar.'
                    ]);
                }

            // Insertar en Kardex
            $modelKardex->insert([
                'tipoMovimiento' => 'Salida',
                'descripcionMovimiento' => "Salida registrada por emisión de DTE: $facturaId",
                'productoExistenciaId' => $productoExistencia['productoExistenciaId'],
                'existenciaAntesMovimiento' => $existenciaAntes,
                'cantidadMovimiento' => $cantidadProducto,
                'existenciaDespuesMovimiento' => $existenciaDespues,
                'costoUnitarioFOB' => $costoFOB,
                'costoUnitarioRetaceo' => $costoUnitarioRetaceo,
                'costoPromedio' => $costoPromedio,
                'precioVentaUnitario' => $precioVentaUnitarioConDescuento,
                'fechaDocumento' => $fechaEmision, // Usar la fechaEmision obtenida
                'fechaMovimiento' => date('Y-m-d H:i:s'),
                'tablaMovimiento' => 'fel_factura_detalle',
                'tablaMovimientoId' => $facturaDetalleId
            ]);

            // Actualizar la existencia en inv_productos_existencias
            $productosExistenciasModel->update($productoExistencia['productoExistenciaId'], [
                'existenciaProducto' => $existenciaDespues
            ]);
        }

        // Obtener datos de la sucursal
        $confSucursalModel = new conf_sucursales();
        $sucursalData = $confSucursalModel->find($sucursalId);
        $codEstablecimientoMH = $sucursalData['codEstablecimientoMH'];
        $puntoVentaMH = $sucursalData['puntoVentaMH'];

        // Obtener el codigoMH del tipo de DTE (asumimos que tienes $tipoDTEId)
        $tipoDTEId = 1; // Asumimos un valor para $tipoDTEId, ajusta según tu necesidad
        $tipoDTEModel = new cat_02_tipo_dte();
        $tipoDTEData = $tipoDTEModel->find($tipoDTEId);
        $codigoMH = $tipoDTEData['codigoMH'];

        // Generar numeroControl
        $numeroControl = "DTE-{$codigoMH}-{$codEstablecimientoMH}-{$puntoVentaMH}" . str_pad($facturaId, 15, '0', STR_PAD_LEFT);

        // Generar codigoGeneracion usando la función codigo de generacion() y convertir a mayúsculas
        $codigoGeneracion = strtoupper($this->codigoGeneracion());

        // Simular selloRecibido y convertir a mayúsculas
        $selloRecibido = strtoupper("REC-" . bin2hex(random_bytes(10)) . "-MH");

        // Insertar en fel_factura_certificacion
        $certificacionModel = new fel_factura_certificacion();
        
        $certificacionModel->insert([
            'facturaId' => $facturaId,
            'numeroControl' => $numeroControl,
            'codigoGeneracion' => $codigoGeneracion,
            'tipoTransmisionMHId' => 2,  // Valor estático según lo especificado
            'selloRecibido' => $selloRecibido,
            'descripcionMensaje' => 'Recibido',
            'estadoCertificacion' => 'Contingencia'
        ]);

        // Actualizar el estado de la reserva
        $dataReservaEstado = [
            'estadoFactura' => "Certificado"
        ];
        $facturaModel->update($facturaId, $dataReservaEstado);

        // Retornar la respuesta exitosa
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Certificación de DTE exitosa'
        ]);
    }

    public function modalFinalizarContingencia(){
        $cat05TipoContingencia = new cat_05_tipo_contingencia();

        $data['variable'] = 0;
        
        $data['selectTipoContingencia'] = $cat05TipoContingencia
            ->select('tipoContingenciaId,tipoContingencia')
            ->where('flgElimina', 0)
            ->findAll();

        $facturaId = $this->request->getPost('facturaId');

        $data['facturaId'] = $facturaId;

        return view('ventas/modals/modalFinalizarContingencia', $data);
    }

    public function tablaContingenciaFacturacion(){
        $facturaId = $this->request->getPost('facturaId');
        $mostrarDTE = new fel_facturas();
        $datos = $mostrarDTE
            ->select('fel_facturas.facturaId, DATE_FORMAT(fel_facturas.fechaEmision, "%d/%m/%Y") as fechaEmision, fel_facturas.obsAnulacion, fel_facturas.estadoFactura, 
                      conf_sucursales.sucursalId, conf_sucursales.sucursal, 
                      fel_clientes.clienteId, fel_clientes.cliente, fel_clientes.nrcCliente, fel_clientes.numDocumentoIdentificacion, fel_clientes.direccionCliente, 
                      conf_empleados.empleadoId, conf_empleados.primerNombre, conf_empleados.primerApellido, 
                      cat_02_tipo_dte.tipoDTEId, cat_02_tipo_dte.tipoDocumentoDTE,
                      fel_factura_certificacion.codigoGeneracion, fel_factura_certificacion.numeroControl')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
            ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
            ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaId = fel_facturas.facturaId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_factura_certificacion.estadoCertificacion', 'Contingencia')
            ->orderBy('fel_factura_certificacion.facturaCertificacionId', 'DESC')
            ->findAll();

        $output['data'] = array();
        $n = 1;

        foreach ($datos as $columna) {
            $estadoClase = 'badge badge-warning';

            $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Vendedor:</b> " . $columna['primerNombre'] . " " . $columna['primerApellido'] . "<br><b>Código de generación:</b> " . $columna['codigoGeneracion'] . "<br><b>Número de control:</b> " . $columna['numeroControl'];

            $columna3 = "<b>Fecha:</b> " . $columna['fechaEmision'] . "<br><b>Estado:</b> <span class='" . $estadoClase . "'>Contingencia</span>";
            if ($columna['estadoFactura'] === 'Anulado') {
                $columna3 .= "<br><b>Obs Anulación:</b> " . $columna['obsAnulacion'];
            }

            $columna4 = "<b>Cliente:</b> " . $columna['cliente'] . "<br><b>NRC:</b> " . $columna['nrcCliente'] . "<br><b>Dirección:</b> " . $columna['direccionCliente']. "<br><b>Tipo DTE:</b> " . $columna['tipoDocumentoDTE'];

            // Inicializar variables para cálculos de totales
            $subtotal = 0;
            $ivaTotal = 0;
            $totalAPagar = 0;
            $descuentos = 0;

            // Obtener los detalles de la factura
            $detalleModel = new fel_facturas_detalle();
            $detallesFactura = $detalleModel
                ->where('facturaId', $columna['facturaId'])
                ->where('flgElimina', 0)
                ->findAll();
                $parametrizacion = new conf_parametrizaciones();
            
                $contingenciaActivada = $parametrizacion
                          ->select('valorParametrizacion')
                          ->where('flgElimina', 0)
                          ->where('parametrizacionId', 6)
                          ->first();
            // Calcular los totales
            foreach ($detallesFactura as $detalle) {
                $subtotal += $detalle['totalDetalle'];
                $ivaTotal += $detalle['ivaTotal'];
                $totalAPagar += $detalle['totalDetalleIVA'];
                $descuentos += ($detalle['precioUnitario'] - $detalle['precioUnitarioVenta']) * $detalle['cantidadProducto'];
            }

            // Añadir los totales en la columna 5
            $columna5 = "<b>(=)Subtotal:</b> $ " . number_format($subtotal, 2, '.', ',') . "<br>"
                      . "<b>(+)IVA:</b> $ " . number_format($ivaTotal, 2, '.', ',') . "<br>"
                      . "<b>(-)Descuentos:</b> $ " . number_format($descuentos, 2, '.', ',') . "<br>"
                      . "<b>(=)Total a Pagar:</b> $ " . number_format($totalAPagar, 2, '.', ',');
        

            $output['data'][] = array(
                $n,
                $columna2,
                $columna3,
                $columna4,
                $columna5
            );

            $n++;
        }

        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function operacionCertificarContingencia(){
        $mostrarDTE = new fel_facturas();
        $felFacturaCertificacion = new fel_factura_certificacion();
        $confParametrizaciones = new conf_parametrizaciones();

        $felFacturaContingencia = new fel_factura_contingencia();
        $felFacturaContingenciaDetalle = new fel_factura_contingencia_detalle();

        $facturaId          = $this->request->getPost('facturaId');
        $fechaInicio        = $this->request->getPost('fechaInicio');
        $horaInicio         = $this->request->getPost('horaInicio');
        $tipoContingenciaId = $this->request->getPost('tipoContingenciaId');
        $fechaFin           = $this->request->getPost('fechaFin');
        $horaFin            = $this->request->getPost('horaFin');
        $motivoContingencia = $this->request->getPost('motivoContingencia');

        $codigoGeneracion = strtoupper($this->codigoGeneracionContingencia());

        $data = [
            'codigoGeneracion'      => $codigoGeneracion,
            'fechaInicio'           => $fechaInicio,
            'horaInicio'            => $horaInicio,
            'fechaFin'              => $fechaFin,
            'horaFin'               => $horaFin,
            'tipoContingenciaId'    => $tipoContingenciaId,
            'motivoContingencia'    => $motivoContingencia
        
        ];

        $facturaContingenciaId = $felFacturaContingencia->insert($data);

        $dteContingencia = $felFacturaCertificacion
            ->select('facturaCertificacionId, facturaId')
            ->where('flgElimina', 0)
            ->where('estadoCertificacion', 'Contingencia')
            //->orderBy('fel_factura_certificacion.facturaCertificacionId', 'DESC')
            ->findAll();

        foreach ($dteContingencia as $contingencia) {
            $dataContingenciaDetalle = [
                'facturaContingenciaId' => $facturaContingenciaId,
                'facturaId'             => $contingencia['facturaId']
            ];

            $operacionContingenciaDetalle = $felFacturaContingenciaDetalle->insert($dataContingenciaDetalle);

            // Update a fel_factura_certificacion para cambiarle el estado
            $dataEstadoFactura = [
                'estadoCertificacion'  => 'Certificado'
            ];

            $operacionEstadofactura = $felFacturaCertificacion->update($contingencia['facturaCertificacionId'], $dataEstadoFactura);
        }

        $dataFinalizarContingencia = [
            'valorParametrizacion' => 1
        ];

        $operacionFinalizarContingencia = $confParametrizaciones->update(6,$dataFinalizarContingencia);
        // Update para cerrar la contingencia

        if ($operacionFinalizarContingencia) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Contingencia finalizada con éxito',
                'facturaContingenciaId' => $facturaContingenciaId
/*              'fechaEmision' => date('Y-m-d'),
                'horaEmision'  => date('H:i:s')*/
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo finalizar la contingencia'
            ]);
        }
        
    }

    private function codigoGeneracionContingencia() {
        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }
        
        $data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10
        
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
    /*
    public function modalNotaCredito(){
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
        return view('ventas/modals/modalNotaCredito', $data);
    }
    */
    public function modalNotaCredito() {
        // Cargar el modelos
        $sucursalesModel = new conf_sucursales();
        $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
    
        $clientesModel = new fel_clientes();
        $data['clientes'] = $clientesModel->where('flgElimina', 0)->findAll();
    
        $empleadosModel = new conf_empleados();
        $data['empleados'] = $empleadosModel->where('flgElimina', 0)->findAll();
    
        $tipoDTEModel = new cat_02_tipo_dte();
        $data['tipoDTE'] = $tipoDTEModel->where('flgElimina', 0)->findAll();
    
        // Obtener créditos fiscales (DTE tipo Crédito Fiscal y estado Certificado)
        $facturaModel = new fel_facturas();
        $data['creditosFiscales'] = $facturaModel
            ->select('fel_facturas.facturaId, fel_factura_certificacion.numeroControl')  // Seleccionar numeroControl desde fel_factura_certificacion
            ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaId = fel_facturas.facturaId')
            ->where('fel_facturas.tipoDTEId', '2')  // Crédito Fiscal
            ->where('fel_factura_certificacion.estadoCertificacion', 'Certificado')
            ->whereNotIn('fel_facturas.facturaId', function($query) {
                $query->select('facturaIdRelacionada')
                    ->from('fel_factura_relacionada');
            })
            ->findAll();

    
        $operacion = $this->request->getPost('operacion');
        $data['operacion'] = $operacion;
    
        if ($operacion == 'editar') {
            // Si es editar, cargar los datos de la factura
            $facturaId = $this->request->getPost('facturaId');
            $data['campos'] = $facturaModel
                ->select('fel_facturas.facturaId, fel_facturas.fechaEmision, fel_facturas.obsAnulacion, fel_facturas.estadoFactura, conf_sucursales.sucursalId, fel_clientes.clienteId, conf_empleados.empleadoId, cat_02_tipo_dte.tipoDTEId')
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
                ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
                ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
                ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
                ->where('fel_facturas.facturaId', $facturaId)
                ->first();
        } else {
            // Si es nuevo, inicializar los campos vacíos
            $data['campos'] = [
                'facturaId' => 0,
                'sucursalId' => '',
                'tipoDTEId' => '',
                'fechaEmision' => '',
                'clienteId' => '',
                'empleadoIdVendedor' => '',
            ];
        }
    
        return view('ventas/modals/modalNotaCredito', $data);
    }
    public function obtenerCreditoFiscal() {
        $facturaId = $this->request->getPost('facturaId');
    
        // Modelo de factura
        $facturaModel = new fel_facturas();
        
        // Obtener los datos de la factura junto con los datos necesarios
        $factura = $facturaModel
            ->select('fel_facturas.sucursalId, fel_facturas.tipoDTEId, fel_facturas.clienteId, fel_facturas.empleadoIdVendedor, conf_sucursales.sucursal, fel_clientes.cliente, conf_empleados.primerNombre, conf_empleados.primerApellido, cat_02_tipo_dte.tipoDocumentoDTE')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
            ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
            ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->where('fel_facturas.facturaId', $facturaId)
            ->first();
    
        // Verificar si existe la factura
        if (!$factura) {
            return $this->response->setJSON(['error' => 'Factura no encontrada'], 404);
        }
    
        // Enviar la respuesta con los datos
        return $this->response->setJSON([
            'sucursal'  => $factura['sucursal'],
            'tipoDTE'   => $factura['tipoDocumentoDTE'],
            'cliente'   => $factura['cliente'],
            'vendedor'  => $factura['primerNombre'] . ' ' . $factura['primerApellido']
        ]);
    }
    
        

}
