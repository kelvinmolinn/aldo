<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;

use App\Models\fel_factura_contingencia;
use App\Models\fel_factura_contingencia_detalle;
use App\Models\fel_facturas_detalle;
use App\Models\conf_parametrizaciones;

class administracionContingencia extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function indexContingencia(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-contingencia/contingencia',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('ventas/vistas/contingencia', $data);
    }

    public function tablaContingencias(){
        $felFacturaContingencia = new fel_factura_contingencia();

        $mostrarTablaContingencia = $felFacturaContingencia 
                            ->select('fel_factura_contingencia.facturaContingenciaId,DATE_FORMAT(fel_factura_contingencia.fechaInicio, "%d/%m/%Y") AS fechaInicio,fel_factura_contingencia.horaInicio,DATE_FORMAT(fel_factura_contingencia.fechaFin, "%d/%m/%Y") AS fechaFin,fel_factura_contingencia.horaFin,cat_05_tipo_contingencia.tipoContingencia,fel_factura_contingencia.motivoContingencia')
                            ->join('cat_05_tipo_contingencia', 'cat_05_tipo_contingencia.tipoContingenciaId = fel_factura_contingencia.tipoContingenciaId')
                            ->where('fel_factura_contingencia.flgElimina',0)
                            ->findAll();
        $n = 0;
        foreach ($mostrarTablaContingencia as $columna) {
            $n++;

            $columna1 = "<b>Fecha inicio:</b> " . $columna['fechaInicio'] . "<br><b>Hora inicio:</b> " . $columna['horaInicio'];

            $columna2 = "<b>Fecha fin:</b> " . $columna['fechaFin'] . "<br><b>Hora fin:</b> " . $columna['horaFin'];

            $columna3 = "<b>Tipo contingencia:</b> " . $columna['tipoContingencia'] . "<br><b>Motivo:</b> " . $columna['motivoContingencia'];
            
            $jsonDatosContingencia = [
                "facturaContingenciaId" => $columna['facturaContingenciaId'],
                "fechaInicio"           => $columna['fechaInicio'],
                "horaInicio"            => $columna['horaInicio'],
                "fechaFin"              => $columna['fechaFin'],
                "horaFin"               => $columna['horaFin'],
                "tipoContingencia"      => $columna['tipoContingencia'],
                "motivoContingencia"    => $columna['motivoContingencia']
            ];

            $columna4 = '
                    <button class="btn btn-primary mb-1" onclick="modalVerDTEContingencia('.htmlspecialchars(json_encode($jsonDatosContingencia)).')" data-toggle="tooltip" data-placement="top" title="Ver DTE contingencia">
                        <i class="fas fa-eye"></i><span> </span>
                    </button>';

            $columna4 .= '
                    <button class="btn btn-info mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Ver json contingencia">
                        <i class="fas fa-file-code"></i><span> </span>
                    </button>';

            
            $output['data'][] = array(
                $n,
                $columna1,
                $columna2,
                $columna3,
                $columna4
            );
        }



        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalDTEContingencia(){
        $data['variable'] = 0;

        $data['facturaContingenciaId'] = $this->request->getPost('facturaContingenciaId');
        $data['fechaInicio'] = $this->request->getPost('fechaInicio');
        $data['horaInicio'] = $this->request->getPost('horaInicio');
        $data['fechaFin'] = $this->request->getPost('fechaFin');
        $data['horaFin'] = $this->request->getPost('horaFin');
        $data['tipoContingencia'] = $this->request->getPost('tipoContingencia');
        $data['motivoContingencia'] = $this->request->getPost('motivoContingencia');

        return view('ventas/modals/modalContingenciaDTE', $data);
    }

    public function tablaDTEContingencia(){
        $facturaContingenciaId = $this->request->getPost('facturaContingenciaId');

        $felFacturaContingenciaDetalle = new fel_factura_contingencia_detalle();

        $datos = $felFacturaContingenciaDetalle
            ->select('fel_facturas.facturaId, DATE_FORMAT(fel_facturas.fechaEmision, "%d/%m/%Y") as fechaEmision, fel_facturas.obsAnulacion, fel_facturas.estadoFactura, 
                      conf_sucursales.sucursalId, conf_sucursales.sucursal, 
                      fel_clientes.clienteId, fel_clientes.cliente, fel_clientes.nrcCliente, fel_clientes.numDocumentoIdentificacion, fel_clientes.direccionCliente, 
                      conf_empleados.empleadoId, conf_empleados.primerNombre, conf_empleados.primerApellido, 
                      cat_02_tipo_dte.tipoDTEId, cat_02_tipo_dte.tipoDocumentoDTE,
                      fel_factura_certificacion.codigoGeneracion, fel_factura_certificacion.numeroControl')
            ->join('fel_facturas', 'fel_facturas.facturaId = fel_factura_contingencia_detalle.facturaId')          
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = fel_facturas.sucursalId')
            ->join('fel_clientes', 'fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = fel_facturas.empleadoIdVendedor')
            ->join('cat_02_tipo_dte', 'cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->join('fel_factura_certificacion', 'fel_factura_certificacion.facturaId = fel_facturas.facturaId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_factura_contingencia_detalle.facturaContingenciaId', $facturaContingenciaId)
            ->orderBy('fel_factura_certificacion.facturaCertificacionId', 'DESC')
            ->findAll();

        $output['data'] = array();
        $n = 1;

        foreach ($datos as $columna) {
            $estadoClase = 'badge badge-success';

            $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'] . "<br><b>Vendedor:</b> " . $columna['primerNombre'] . " " . $columna['primerApellido'] . "<br><b>Código de generación:</b> " . $columna['codigoGeneracion'] . "<br><b>Número de control:</b> " . $columna['numeroControl'];

            $columna3 = "<b>Fecha:</b> " . $columna['fechaEmision'] . "<br><b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoFactura'] . "</span>" ;

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

    public function jsonContingencia(){

    }
}
