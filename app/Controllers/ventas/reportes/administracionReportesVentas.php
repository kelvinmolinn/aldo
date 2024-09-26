<?php

namespace App\Controllers\ventas\reportes;
use CodeIgniter\Controller;

use App\Models\fel_facturas_detalle;

class administracionReportesVentas extends Controller{

    public function indexReportes(){
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'ventas/admin-reportes/index',
                'camposSession'     => json_encode($camposSession)
            ]);

         return view('ventas/vistas/administracionReportes', $data);
        }
    }

    public function modalConsolidadoVentas(){

        $data['variable'] = 0;


        return view('ventas/reportes/modalConsolidadoVentas', $data);
    }

    public function reporteDetalleVentas(){
        $felFacturasDetalle = new fel_facturas_detalle();
        $data['variable'] = 0;

        $fechaInicio = $this->request->getPost('fechaInicio');
        $fechaFin = $this->request->getPost('fechaFin');

        $data['detalles'] = $felFacturasDetalle 
            ->select('cat_02_tipo_dte.tipoDocumentoDTE,fel_facturas.facturaId, DATE_FORMAT(fel_facturas.fechaEmision, "%d-%m-%Y") as fechaEmision,fel_clientes.cliente,inv_productos.codigoProducto,inv_productos.producto,fel_facturas_detalle.precioUnitario,fel_facturas_detalle.precioUnitarioIVA,fel_facturas_detalle.cantidadProducto,fel_facturas_detalle.ivaTotal,fel_facturas_detalle.totalDetalle,fel_facturas_detalle.totalDetalleIVA,fel_facturas_detalle.precioUnitarioVenta,fel_facturas_detalle.precioUnitarioVentaIVA,fel_facturas_detalle.descuentoTotal')
            ->join('fel_facturas','fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->join('fel_clientes','fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->where('fel_facturas.estadoFactura', 'Certificado')
            ->where('fel_facturas.fechaEmision >=', $fechaInicio)
            ->where('fel_facturas.fechaEmision <=', $fechaFin)
            ->findAll();

        return view('ventas/reportes/reporteDetalleVentas', $data);
    }
}