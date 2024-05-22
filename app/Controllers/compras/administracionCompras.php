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
        

        if($numDocumento == "") {
            $whereDocumento = "";
        } else {
            $whereDocumento = "AND c.numDocumento = '" . $numDocumento . "'";
            $contadorFiltros++;
        }

        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        if($contadorFiltros > 0) {
            // Aqui va la consulta
            //foreach ($datos as $columna) {
                // Aquí construye tus columnas
                $columna1 = $n;
                $columna2 = "";
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
        //}
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
}