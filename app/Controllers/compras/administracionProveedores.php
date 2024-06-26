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

class administracionProveedores extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

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

    public function tablaProveedores(){
        $mostrarProveedor = new comp_proveedores();

        $datos = $mostrarProveedor
          ->select('comp_proveedores.tipoProveedorOrigen,comp_proveedores.proveedorId, comp_proveedores.tipoPersonaId,comp_proveedores.documentoIdentificacionId, comp_proveedores.ncrProveedor, comp_proveedores.numDocumentoIdentificacion, comp_proveedores.proveedor, comp_proveedores.proveedorComercial, cat_19_actividad_economica.actividadEconomica, comp_proveedores.tipoContribuyenteId, comp_proveedores.direccionProveedor')
          ->join('cat_19_actividad_economica' , 'cat_19_actividad_economica.actividadEconomicaId = comp_proveedores.actividadEconomicaId')
          ->where('comp_proveedores.flgElimina', 0)
          ->orderBy('comp_proveedores.proveedorId', 'ASC')
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
                <button class="btn btn-primary mb-1" onclick="modalProveedor(`'.$columna['proveedorId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna4 .= '
                <button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Historial de compras">
                    <i class="fas fa-history"></i>
                </button>
            ';

            $columna4 .= '
                <button class="btn btn-primary mb-1" onclick="modalContactoProveedor(`'.$columna['proveedorId'].'`,`'.$columna['proveedor'].'`);" data-toggle="tooltip" data-placement="top" title="Contactos">
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
    public function obtenerContactoProveedor(){
        $contacto = new cat_tipo_contacto();

        $tipoContacto = $contacto
            ->select('tipoContactoId, tipoContacto')
            ->where('flgElimina', 0)
            ->findAll();
        $opcionesSelect = array();

        $n = 0;
        foreach($tipoContacto as $tipoContacto){
            $n += 1;
            $opcionesSelect[] = array("valor" => $tipoContacto['tipoContactoId'], "texto" => $tipoContacto['tipoContacto']);
        }
        
        if ($n > 0) {
            echo json_encode($opcionesSelect);
        }else{
            echo json_encode(array('data'=>''));
        }
    }
    public function modalProveedores(){
        
        $operacion = $this->request->getPost('operacion');

        $catTipoPersona = new cat_29_tipo_persona;
        $data['tipoPersona'] = $catTipoPersona
            ->select('tipoPersonaId,tipoPersona')
            ->where('flgElimina', 0)
            ->findAll();

        $catDocumentoIdentificacion = new cat_22_documentos_identificacion;
        $data['documentoIdentificacion'] = $catDocumentoIdentificacion
            ->select('documentoIdentificacionId,documentoIdentificacion')
            ->where('flgElimina', 0)
            ->findAll();

        $catActividadEconomica = new cat_19_actividad_economica;
        $data['actividadEconomica'] = $catActividadEconomica
            ->select('actividadEconomicaId,actividadEconomica')
            ->where('flgElimina', 0)
            ->findAll();

        $catTipoContribuyente = new cat_tipo_contribuyente;
        $data['tipoContribuyente'] = $catTipoContribuyente
            ->select('tipoContribuyenteId,tipoContribuyente')
            ->where('flgElimina', 0)
            ->findAll();

        if($operacion == 'editar') {
            $proveedorId = $this->request->getPost('proveedorId');

            $proveedor = new comp_proveedores();
            $data['campos'] = $proveedor
            ->select('proveedorId,tipoProveedorOrigen,tipoPersonaId,documentoIdentificacionId,ncrProveedor,numDocumentoIdentificacion,proveedor,proveedorComercial,actividadEconomicaId,tipoContribuyenteId,direccionProveedor')
            ->where('flgElimina', 0)
            ->where('proveedorId', $proveedorId)
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
                'direccionProveedor'        => ''
            ];
        }
        $data['operacion'] = $operacion;

        return view('compras/modals/modalProveedores', $data);
    }
    public function modalProveedorOperacion(){
        $operacion      = $this->request->getPost('operacion');
        $proveedorId    = $this->request->getPost('proveedorId');
        
        $proveedor = new comp_proveedores();


        $data = [
            'tipoProveedorOrigen'           => $this->request->getPost('selectTipoProveedor'),
            'tipoPersonaId'                 => $this->request->getPost('selectTipoPersona'),
            'documentoIdentificacionId'     => $this->request->getPost('selectTipoDocumento'),
            'ncrProveedor'                  => $this->request->getPost('nrc'),
            'numDocumentoIdentificacion'    => $this->request->getPost('numeroDocumento'),
            'proveedor'                     => $this->request->getPost('nombreProveedor'),
            'proveedorComercial'            => $this->request->getPost('nombreComercial'),
            'actividadEconomicaId'          => $this->request->getPost('selectActividadEconomica'),
            'tipoContribuyenteId'           => $this->request->getPost('selectTipoContribuyente'),
            'direccionProveedor'            => $this->request->getPost('direccionProveedor'),
            'estadoProveedor'               => "Activo"
        ];

        if($operacion == 'editar') {
            $operacionProveedor = $proveedor->update($this->request->getPost('proveedorId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionProveedor = $proveedor->insert($data);
        }
        if ($operacionProveedor) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Proveedor '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'proveedorId' => ($operacion == 'editar' ? $this->request->getPost('proveedorId') : $proveedor->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el Proveedor'
            ]);
        }
    }
    public function modalContactoProveedores(){
        $data['proveedorId'] = $this->request->getPost('proveedorId');

        $data['proveedor'] = $this->request->getPost('proveedor');

        return view('compras/modals/modalContactoProveedor', $data);
    }

    public function agregarContacto(){
        $proveedorId    = $this->request->getPost('proveedorId');
        
        $proveedorContacto = new comp_proveedores_contacto();


        $data = [
            'proveedorId'           => $proveedorId,
            'tipoContactoId'        => $this->request->getPost('selectTipoContacto'),
            'contactoProveedor'     => $this->request->getPost('tipoContacto')
        ];

        // Insertar datos en la base de datos
        $operacionProveedor = $proveedorContacto->insert($data);
        
        if ($operacionProveedor) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success'               => true,
                'mensaje'               => 'Contacto agregado correctamente',
                'proveedorContactoId'   =>  $proveedorContacto->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el contacto'
            ]);
        }
    }
    public function tablaContactoProveedores(){
        $proveedorId    = $this->request->getPost('proveedorId');
        $contactoProveedor = new comp_proveedores_contacto();

        $datos = $contactoProveedor
          ->select('comp_proveedores_contacto.proveedorContactoId,cat_tipo_contacto.tipoContacto, comp_proveedores_contacto.contactoProveedor')
          ->join('cat_tipo_contacto' , 'cat_tipo_contacto.tipoContactoId = comp_proveedores_contacto.tipoContactoId')
          ->where('comp_proveedores_contacto.flgElimina', 0)
          ->where('comp_proveedores_contacto.proveedorId',$proveedorId)
          ->findAll();
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>".$columna["tipoContacto"].":</b> ".$columna["contactoProveedor"];

            $columna3 = '
                <button type="button" class="btn btn-danger mb-1" onclick="eliminarContactoProveedor(`'.$columna["proveedorContactoId"].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function eliminarContacto(){
            $eliminarContacto = new comp_proveedores_contacto();
        
            $proveedorContactoId = $this->request->getPost('proveedorContactoId');
            $data = ['flgElimina' => 1];
            
            $eliminarContacto->update($proveedorContactoId, $data);

            if($eliminarContacto) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Contacto eliminado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo eliminar el contacto'
                ]);
            }
        }
}