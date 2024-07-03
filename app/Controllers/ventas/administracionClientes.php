<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;
use App\Models\fel_clientes;
use App\Models\fel_cliente_contacto;
use App\Models\cat_29_tipo_persona;
use App\Models\cat_19_actividad_economica;
use App\Models\cat_tipo_contacto;
use App\Models\cat_22_documentos_identificacion;
use App\Models\cat_tipo_contribuyente;
use App\Models\cat_20_paises;
use App\Models\cat_12_paises_ciudades;
use App\Models\cat_13_paises_estados;

class administracionClientes extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-clientes/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/clientes', $data);
    }

    public function obtenerContactoCliente(){
        $contacto = new cat_tipo_contacto();

        $tipoContacto = $contacto
            ->select('tipoContactoId, tipoContacto')
            ->where('flgElimina', 0)
            ->findAll();
        $opcionesSelect = array();

        $n = 0;
        foreach($tipoContacto as $contacto){
            $n += 1;
            $opcionesSelect[] = array("valor" => $contacto['tipoContactoId'], "texto" => $contacto['tipoContacto']);
        }
        
        if ($n > 0) {
            echo json_encode($opcionesSelect);
        }else{
            echo json_encode(array('data'=>''));
        }
    }

    public function modalNuevoCliente(){

        $operacion = $this->request->getPost('operacion');

        $catTipoPersona = new cat_29_tipo_persona();
        $data['tipoPersona'] = $catTipoPersona
            ->select('tipoPersonaId,tipoPersona')
            ->where('flgElimina', 0)
            ->findAll();

        $catDocumentoIdentificacion = new cat_22_documentos_identificacion();
        $data['documentoIdentificacion'] = $catDocumentoIdentificacion
            ->select('documentoIdentificacionId,documentoIdentificacion')
            ->where('flgElimina', 0)
            ->findAll();

        $catActividadEconomica = new cat_19_actividad_economica();
        $data['actividadEconomica'] = $catActividadEconomica
            ->select('actividadEconomicaId,actividadEconomica')
            ->where('flgElimina', 0)
            ->findAll();

        $catTipoContribuyente = new cat_tipo_contribuyente();
        $data['tipoContribuyente'] = $catTipoContribuyente
            ->select('tipoContribuyenteId,tipoContribuyente')
            ->where('flgElimina', 0)
            ->findAll();

        $catPaises = new cat_20_paises();
        $data['pais'] = $catPaises
            ->select('paisId,pais')
            ->where('flgElimina', 0)
            ->findAll();

        $catPaisCiudad = new cat_12_paises_ciudades();
        $data['paisCiudad'] = $catPaisCiudad
            ->select('paisCiudadId,paisCiudad')
            ->where('flgElimina', 0)
            ->findAll();

            $catPaisEstado = new cat_13_paises_estados();
            $data['paisEstado'] = $catPaisEstado
                ->select('paisEstadoId,paisEstado')
                ->where('flgElimina', 0)
                ->findAll();


/*
            if(isset($_POST["txtBuscar"])) {
                // Misma logica que el select dependiente, una consulta, foreach y un json con la diferencia que en vez de valor se pone "text"
 
                    $n = 0;
                   foreach($data as $consulta ) {
                       $n += 1;
               
                       $jsonSelect[] = array("id" => $consulta->paisEstadoId, "text" => $consulta->paisEstado);
                   }
                   if($n > 0) {
                       echo json_encode($jsonSelect);
                   }else{
                         $json[] = ['id'=>'', 'text'=>'Digite el valor a buscar'];
                         echo json_encode($json);
                   }
               } else {
                         $json[] = ['id'=>'', 'text'=>'Digite el valor a buscar'];
                         echo json_encode($json);
               }
*/
        if($operacion == 'editar') {
            $clienteId = $this->request->getPost('clienteId');

            $cliente = new fel_clientes();
            $data['campos'] = $cliente
            ->select('clienteId, tipoPersonaId, nrcCliente, documentoIdentificacionId, numDocumentoIdentificacion, cliente, clienteComercial, actividadEconomicaId, tipoContribuyenteId, direccionCliente, porcentajeDescuentoMaximo, paisId, paisCiudadId, paisEstadoId, estadoCliente')
            ->where('flgElimina', 0)
            ->where('clienteId', $clienteId)
            ->first();
        } else {
            $data['campos'] = [
                'clienteId'                 => 0,
                'tipoPersonaId'             => '',
                'nrcCliente'                => '',
                'documentoIdentificacionId' => '',
                'numDocumentoIdentificacion'=> '',
                'cliente'                   => '',
                'clienteComercial'          => '',
                'actividadEconomicaId'      => '',
                'tipoContribuyenteId'       => '',
                'paisId'                    => '',
                'paisCiudadId'              => '',
                'paisEstadoId'              => '',
                'direccionCliente'          => ''
            ];
        }
        $data['operacion'] = $operacion;
        return view('ventas/modals/modalNuevoCliente', $data);
    }

    public function modalClienteOperacion(){
        $operacion      = $this->request->getPost('operacion');
        $clienteId    = $this->request->getPost('clienteId');
        
        $cliente = new fel_clientes();

        $data = [
            'tipoPersonaId'                 => $this->request->getPost('selectTipoPersona'),
            'documentoIdentificacionId'     => $this->request->getPost('selectTipoDocumento'),
            'nrcCliente'                    => $this->request->getPost('nrcCliente'),
            'numDocumentoIdentificacion'    => $this->request->getPost('numeroDocumento'),
            'cliente'                       => $this->request->getPost('cliente'),
            'clienteComercial'              => $this->request->getPost('clienteComercial'),
            'actividadEconomicaId'          => $this->request->getPost('selectActividadEconomica'),
            'tipoContribuyenteId'           => $this->request->getPost('selectTipoContribuyente'),
            'paisId'                        => $this->request->getPost('selectPaisCliente'),
            'paisCiudadId'                  => $this->request->getPost('selectDepartamentoCliente'),
            'paisEstadoId'                  => $this->request->getPost('selectMunicipioCliente'),
            'direccionCliente'              => $this->request->getPost('direccionCliente'),
            'estadoCliente'                 => "Activo"
        ];

        if($operacion == 'editar') {
            $operacionCliente = $cliente->update($this->request->getPost('clienteId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionCliente = $cliente->insert($data);
        }
        if ($operacionCliente) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Cliente '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'clienteId' => ($operacion == 'editar' ? $this->request->getPost('clienteId') : $cliente->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el Cliente'
            ]);
        }
    }

    public function tablaClientes(){
        $mostrarClientes = new fel_clientes();

        $datos = $mostrarClientes
          ->select('fel_clientes.clienteId, fel_clientes.tipoPersonaId,fel_clientes.documentoIdentificacionId, fel_clientes.nrcCliente, fel_clientes.numDocumentoIdentificacion, fel_clientes.cliente, fel_clientes.clienteComercial, cat_19_actividad_economica.actividadEconomica,cat_29_tipo_persona.tipoPersona, fel_clientes.tipoContribuyenteId, fel_clientes.direccionCliente,cat_tipo_contribuyente.tipoContribuyente')
          ->join('cat_19_actividad_economica' , 'cat_19_actividad_economica.actividadEconomicaId = fel_clientes.actividadEconomicaId')
          ->join('cat_29_tipo_persona' , 'cat_29_tipo_persona.tipoPersonaId = fel_clientes.tipoPersonaId')
          ->join('cat_tipo_contribuyente' , 'cat_tipo_contribuyente.tipoContribuyenteId = fel_clientes.tipoContribuyenteId')
          ->where('fel_clientes.flgElimina', 0)
          ->orderBy('fel_clientes.clienteId', 'ASC')
          ->findAll();

        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Tipo de persona: </b>"  . $columna['tipoPersona'] . "<br>" . "<b>Cliente:</b>". $columna['cliente'] . "<br>" . "<b>Giro:</b>"  . $columna['actividadEconomica']. "<br>" . "<b>Dirección:</b> " . $columna['direccionCliente'];
            $columna3 = "<b>Categoría: </b>". $columna['tipoContribuyente']  . "<br>" . "<b>NRC:</b> ". $columna['nrcCliente']  . "<br>" . "<b>Documento:</b> ". $columna['numDocumentoIdentificacion'];
            
            $columna4 = '
                            <button type= "button" class="btn btn-primary mb-1" onclick="modalClientes(`'.$columna['clienteId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="fas fa-pencil-alt"></i>
                            </button>';
            $columna4 .= '
                            <button type= "button" class="btn btn-primary mb-1" onclick="modalContactoClientes(`'.$columna['clienteId'].'`, `editar`)" data-toggle="tooltip" data-placement="top" title="Contactos">
                                <i class="fas fa-address-book"></i>
                            </button>';

            $columna4 .= '
                            <button type= "button" class="btn btn-primary mb-1" onclick="modalHistorialVentas()" data-toggle="tooltip" data-placement="top" title="Historial de ventas">
                                <i class="fas fa-history"></i>
                            </button>
                        ';

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

    public function modalContactoCliente(){
        $data['clienteId'] = $this->request->getPost('clienteId');

        $data['cliente'] = $this->request->getPost('cliente');
        return view('ventas/modals/modalContactoCliente', $data);
    }

    public function agregarContacto(){
        $clienteId    = $this->request->getPost('clienteId');
        
        $clienteContacto = new fel_cliente_contacto();


        $data = [
            'clienteId'           => $clienteId,
            'tipoContactoId'       => $this->request->getPost('selectTipoContactoCliente'),
            'contactoCliente'     => $this->request->getPost('tipoContactoCliente')
        ];

        // Insertar datos en la base de datos
        $operacionCliente = $clienteContacto->insert($data);
        
        if ($operacionCliente) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success'               => true,
                'mensaje'               => 'Contacto agregado correctamente',
                'clienteContactoId'     =>  $clienteContacto->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el contacto'
            ]);
        }
    }
        
    public function tablaContactoClientes(){
        $clienteId    = $this->request->getPost('clienteId');
        $contactoCliente = new fel_cliente_contacto();

        $datos = $contactoCliente
          ->select('fel_cliente_contacto.clienteContactoId,cat_tipo_contacto.tipoContacto, fel_cliente_contacto.contactoCliente')
          ->join('cat_tipo_contacto' , 'cat_tipo_contacto.tipoContactoId = fel_cliente_contacto.tipoContactoId')
          ->where('fel_cliente_contacto.flgElimina', 0)
          ->where('fel_cliente_contacto.clienteId',$clienteId)
          ->findAll();
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>".$columna["tipoContacto"].":</b> ".$columna["contactoCliente"];

            $columna3 = '
                <button type="button" class="btn btn-danger mb-1" onclick="eliminarContactoCliente(`'.$columna["clienteContactoId"].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function modalHistorialVentas(){
        $data['variable'] = 0;
        return view('ventas/modals/modalHistorialVentas', $data);
    }
        
    public function tablaHistorialVentas(){
        $output['data'] = array();
        $n = 0;
        $n++;
        
        $columna1 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Tipo de DTE: </b> Factura ";
        $columna2 = "<b>Cód. Generación: </b> C6A9868C-028D-421B-A9A0-36274CECC2C7" . "<br>" . "<b>Núm. control: </b> DTE-03-12345678-000000000000001";
        $columna3 = "<b>Fecha: </b> 05/05/2024" . "<br>" . "<b>Hora: </b>05:25:01 ";
        $columna4 = "$ 200.00";
        $columna5 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Ver factura">
                            <i class="fas fa-eye"></i>
                        </button>';

        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3,
            $columna4,
            $columna5
        );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }       
    }
    public function eliminarContacto(){
        $eliminarContacto = new fel_cliente_contacto();
    
        $clienteContactoId = $this->request->getPost('clienteContactoId');
        $data = ['flgElimina' => 1];
        
        $eliminarContacto->update($clienteContactoId, $data);

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

