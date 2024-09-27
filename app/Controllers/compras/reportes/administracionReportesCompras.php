<?php

namespace App\Controllers\compras\reportes;
use CodeIgniter\Controller;

use App\Models\comp_compras_detalle;

class administracionReportesCompras extends Controller{

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
                'route'             => 'compras/admin-reportes/index',
                'camposSession'     => json_encode($camposSession)
            ]);

         return view('compras/vistas/administracionReportes', $data);
        }
    }
    public function modalConsolidadoCompras(){
        $data['variable'] = 0;

        return view('compras/reportes/reporteConsolidadoCompras', $data);
    }
    
    public function reporteDetalleCompras(){
        $compComprasDetalle = new comp_compras_detalle();
        $data['variable'] = 0;

        $tipoCompra = $this->request->getPost('tipoCompra');

        $data['detalles'] = $compComprasDetalle 
            ->select('DATE_FORMAT(comp_compras.fechaDocumento, "%d-%m-%Y") as fechaDocumento,comp_compras.numFactura,cat_02_tipo_dte.tipoDocumentoDTE,comp_proveedores.proveedor,inv_productos.codigoProducto,inv_productos.producto,comp_compras_detalle.precioUnitario,comp_compras_detalle.precioUnitarioIVA,comp_compras_detalle.cantidadProducto,comp_compras_detalle.ivaUnitario,comp_compras_detalle.totalCompraDetalle,comp_compras_detalle.totalCompraDetalleIVA')
            ->join('comp_compras','comp_compras.compraId = comp_compras_detalle.compraId')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = comp_compras.tipoDTEId')
            ->join('comp_proveedores','comp_proveedores.proveedorId = comp_compras.proveedorId')
            ->join('inv_productos', 'inv_productos.productoId = comp_compras_detalle.productoId')
            ->where('comp_compras_detalle.flgElimina', 0)
            ->where('comp_compras.flgRetaceo', 'No')
            ->where('comp_compras.tipoCompra', $tipoCompra)
            ->findAll();

        return view('compras/reportes/reporteDetalleCompras', $data);
    }

    public function reporteDetalleComprasRetaceo(){
        $compComprasDetalle = new comp_compras_detalle();
        $data['variable'] = 0;

        $tipoCompra = $this->request->getPost('tipoCompra');

        $data['detalles'] = $compComprasDetalle 
            ->select('DATE_FORMAT(comp_compras.fechaDocumento, "%d-%m-%Y") as fechaDocumento,comp_compras.numFactura,cat_02_tipo_dte.tipoDocumentoDTE,comp_proveedores.proveedor,inv_productos.codigoProducto,inv_productos.producto,comp_compras_detalle.precioUnitario,comp_compras_detalle.precioUnitarioIVA,comp_compras_detalle.cantidadProducto,comp_compras_detalle.ivaUnitario,comp_compras_detalle.totalCompraDetalle,comp_compras_detalle.totalCompraDetalleIVA,comp_retaceo_detalle.importe,comp_retaceo_detalle.flete,comp_retaceo_detalle.gasto,comp_retaceo_detalle.DAI,comp_retaceo_detalle.costoUnitarioRetaceo,comp_retaceo_detalle.costoTotal')
            ->join('comp_compras','comp_compras.compraId = comp_compras_detalle.compraId')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = comp_compras.tipoDTEId')
            ->join('comp_proveedores','comp_proveedores.proveedorId = comp_compras.proveedorId')
            ->join('inv_productos', 'inv_productos.productoId = comp_compras_detalle.productoId')
            ->join('comp_retaceo_detalle','comp_retaceo_detalle.compraDetalleId = comp_compras_detalle.compraDetalleId')
            ->where('comp_compras_detalle.flgElimina', 0)
            ->where('comp_compras.flgRetaceo', 'Si')
            ->findAll();

        return view('compras/reportes/reporteDetalleComprasRetaceo', $data);
    }
    public function modalConsolidadoComprasRetaceo(){
        $data['variable'] = 0;

        return view('compras/reportes/reporteConsolidadoComprasRetaceo', $data);
    }
}