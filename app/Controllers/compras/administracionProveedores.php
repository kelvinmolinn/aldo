<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;
use App\Models\comp_proveedores_contacto;
use App\Models\cat_tipo_contacto;

class administracionProveedores extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){

        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'compras/admin-proveedores/index',
                'camposSession'     => json_encode($camposSession)
            ]);
            return view('compras/vistas/proveedores', $data);
        }
    }

    public function tablaProveedores(){
        $mostrarProveedor = new comp_proveedores();

        $datos = $mostrarProveedor
          ->select('comp_proveedores.tipoProveedorOrigen, comp_proveedores.tipoPersonaId,comp_proveedores.documentoIdentificacionId, comp_proveedores.ncrProveedor, comp_proveedores.numDocumentoIdentificacion, comp_proveedores.proveedor, comp_proveedores.proveedorComercial, cat_actividad_economica.actividadEconomica, comp_proveedores.tipoContribuyenteId, comp_proveedores.direccionProveedor')
          ->join('cat_actividad_economica' , 'cat_actividad_economica.actividadEconomicaId = comp_proveedores.actividadEconomicaId')
          ->where('comp_proveedores.flgElimina', 0)
          ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Origen: </b>" . $columna['tipoProveedorOrigen'] . "<br>" . "<b>Nombre: </b>" . $columna['proveedor']. "<br>" . "<b>Nombre Comercial: </b>" . $columna['proveedorComercial'];

            $columna3 = "<b>NRC: </b>" . $columna['ncrProveedor'] . "<br>" . "<b>Numero de identificación: </b>" . $columna['numDocumentoIdentificacion']. "<br>" . "<b>Actividad económica: </b>" . $columna['actividadEconomica'];


            $columna4 = '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna4 .= '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Historial de compras">
                    <i class="fas fa-history"></i>
                </button>
            ';
            $columna4 .= '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Contactos">
                    <i class="fas fa-address-book"></i>
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
    public function modalProveedores(){
        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $proveedorId = $this->request->getPost('proveedorId');

            $proveedor = new comp_proveedores();
            $data['campos'] = $proveedor
            ->select('')
            ->where('', 0)
            ->where('',)
            ->first();
        } else {
            $data['campos'] = [
                'proveedorId'               => 0,
                'tipoProveedorOrigen'       => '',
                'tipoPersonaId'             => '',
                'documentoIdentificacionId' => '',
                'ncrProveedor'              => '',
                'numDocumentoIdentificacion'=> '',
                'proveedor'                 => '',
                'proveedorComercial'        => '',
                'actividadEconomicaId'      => '',
                'tipoContribuyenteId'       => '',
                'direccionProveedor'        => '',
                'estadoProveedor'           => 'Activo'
            ];
        }
        $data['operacion'] = $operacion;

        return view('compras/modals/modalProveedores', $data);
    } 
}