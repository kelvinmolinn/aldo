<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;
use App\Models\comp_proveedores_contacto;
use App\Models\cat_tipo_contacto;
use App\Models\cat_29_tipo_persona;
use App\Models\cat_22_documentos_identificacion;
use App\Models\cat_19_actividad_economica;
use App\Models\cat_tipo_contribuyente;
use App\Models\comp_compras;

class administracionCompras extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras/admin-compras/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('compras/vistas/compras', $data);
    }
    public function tablaCompras(){
        $contadorFiltros = 0;
        $numDocumento = $this->request->getPost('numDocumento');
        $fechaDocumento = $this->request->getPost('filtroFechaDocumento');
        $filtroProveedor = $this->request->getPost('filtroProveedor');
        

        if($numDocumento == "") {
            $whereDocumento = "";
        } else {
            $whereDocumento = $numDocumento;
            $contadorFiltros++;
        }

        if($fechaDocumento == "") {
            $whereFecha = "";
        } else {
            $whereFecha = $fechaDocumento;
            $contadorFiltros++;
        }

        if($filtroProveedor == "") {
            $whereProveedor = "";
        } else {
            $whereProveedor = $filtroProveedor;
            $contadorFiltros++;
        }
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        if($contadorFiltros > 0) {
            $com_compras = new comp_compras;

            $datos = $com_compras
                ->select('proveedorId,ObsCompra')
                ->where('flgElimina', 0)
                ->where('numDocumento', $whereDocumento)
                ->where('fechaDocumento', $fechaDocumento)
                ->where('proveedorId', $filtroProveedor)
                ->findAll();

            foreach ($datos as $columna) {
                // Aquí construye tus columnas
                $columna1 = $n;
                $columna2 = "<b>Nombre0</b>". $columna['ObsCompra'];
                $columna3 = "";
                $columna4 = "";
                $columna5 = "";
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
        } else {
            $n = 0;
        }
        // Verifica si hay datos
        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function vistaNuevaCompra(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras\administracionCompras::vistaNuevaCompra',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('compras/vistas/pageNuevaCompra', $data);
    }
}