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

    public function modalNuevoCliente(){

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

        $catPaises = new cat_20_paises;
        $data['pais'] = $catPaises
            ->select('paisId,pais')
            ->where('flgElimina', 0)
            ->findAll();

        $catPaisCiudad = new cat_12_paises_ciudades;
        $data['paisCiudad'] = $catPaisCiudad
            ->select('paisCiudadId,paisCiudad')
            ->where('flgElimina', 0)
            ->findAll();

        $catPaisEstado = new cat_13_paises_estados;
        $data['paisEstado'] = $catPaisEstado
            ->select('paisEstadoId,paisEstado')
            ->where('flgElimina', 0)
            ->findAll();

        if($operacion == 'editar') {
            $clienteId = $this->request->getPost('clienteId');

            $cliente = new fel_clientes();
            $data['campos'] = $fel_clientes
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
                'mensaje' => 'Proveedor '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                'clienteId' => ($operacion == 'editar' ? $this->request->getPost('clienteId') : $cliente->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el Proveedor'
            ]);
        }
    }

   /* public function tablaClientes(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Tipo de persona: </b> Natural" . "<br>" . "<b>Cliente:</b> Test " . "<br>" . "<b>Giro:</b> otros servicios de información n.c.p" . "<br>" . "<b>Dirección:</b> Col. San salvador";

        $columna3 = "<b>Categoría: </b>" . "<br>" . "<b>NRC:</b> 55555 " . "<br>" . "<b>DUI:</b> 123456789";
        
        $columna4 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>';
        $columna4 .= '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalContactoClientes()" data-toggle="tooltip" data-placement="top" title="Contactos">
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

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }
        */



 /*   public function modalContactoCliente(){
        $data['variable'] = 0;
        return view('ventas/modals/modalContactoCliente', $data);
    }
        */

  /*  public function tablaContactoClientes(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Correo electronico: </b> prueba@gmail.com";
        
        $columna3 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-trash"></i>
                        </button> 
                    ';

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

    public function modalHistorialVentas(){

        $data['variable'] = 0;
        return view('ventas/modals/modalHistorialVentas', $data);
    }
        */

  /*  public function tablaHistorialVentas(){
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
            */
}
