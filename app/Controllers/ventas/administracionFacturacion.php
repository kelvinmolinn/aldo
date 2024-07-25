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
            ->where('fel_facturas.facturaId', $facturaId)->first();
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

    public function tablaFacturacion(){
        $facturaId = $this->request->getPost('facturaId');
        $mostrarDTE = new fel_facturas();
        $datos = $mostrarDTE
        ->select('fel_facturas.facturaId,fel_facturas.fechaEmision,fel_facturas.obsAnulacion,fel_facturas.estadoFactura,conf_sucursales.sucursalId,conf_sucursales.sucursal,fel_clientes.clienteId,fel_clientes.cliente,fel_clientes.nrcCliente,fel_clientes.numDocumentoIdentificacion,fel_clientes.direccionCliente,conf_empleados.empleadoId,conf_empleados.primerNombre,conf_empleados.primerApellido,cat_02_tipo_dte.tipoDTEId,cat_02_tipo_dte.tipoDocumentoDTE')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
        ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
        ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
        ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
        ->where('fel_facturas.flgElimina', 0)
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
        $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Vendedor:</b> " . $columna['primerNombre']." ". $columna['primerApellido'];

        // Construir columna 3
        $columna3 = "<b>Fecha:</b> " . $columna['fechaEmision'];

         // Construir columna 4
         $columna4 = "<b>Cliente:</b> " . $columna['cliente'] . "<br><b>NRC:</b> " . $columna['nrcCliente']." ". "<br><b>Dirección:</b> " . $columna['direccionCliente'];

         // Construir columna 5
         $columna5 = "<b>Montos:</b> " ;
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


                <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="ver DTE">
                            <i class="fas fa-eye"></i>
                </button>;

                <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Complementos">
                    <i class="fas fa-clipboard-list"></i>
                </button>;
      
                <button type= "button" class="btn btn-primary mb-1" onclick="modalImprimirDTE()" data-toggle="tooltip" data-placement="top" title="Imprimir DTE">
                    <i class="fas fa-print"></i>
                </button>;
           
                <button type= "button" class="btn btn-primary mb-1" onclick="window.location.href=`https://admin.factura.gob.sv/consultaPublica`" data-toggle="tooltip" data-placement="top" title="Consultar DTE">
                    <i class="fas fa-file-alt"></i>
                </button>;
                            
         
                <button type= "button" class="btn btn-danger mb-1" onclick="modalInvalidarDTE()" data-toggle="tooltip" data-placement="top" title="Invalidar DTE">
                    <i class="fas fa-ban"></i>
                </button>';

            ;

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
        $data['variable'] = 0;
        return view('ventas/modals/modalAnularDTE', $data);
    }

    public function vistaContinuarDTE(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/pageContinuarDTE',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/pageContinuarDTE', $data);
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
