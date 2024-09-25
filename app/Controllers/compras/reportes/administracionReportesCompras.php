<?php

namespace App\Controllers\compras\reportes;
use CodeIgniter\Controller;

use App\Models\comp_compras;

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
        $compCompras = new comp_compras();
        $data['variable'] = 0;

        $tipoCompra = $this->request->getPost('tipoCompra');

        $data['detalles'] = $compCompras 
            ->select('DATE_FORMAT(comp_compras.fechaDocumento, "%d-%m-%Y") as fechaDocumento,comp_compras.numFactura,cat_02_tipo_dte.tipoDocumentoDTE')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = comp_compras.tipoDTEId')
            ->where('comp_compras.flgElimina', 0)
            ->where('comp_compras.tipoCompra', $tipoCompra)
            ->findAll();



        return view('compras/reportes/reporteDetalleCompras', $data);
    }    
}