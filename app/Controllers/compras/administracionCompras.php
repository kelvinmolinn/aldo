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
use App\Models\cat_02_tipo_dte;
use App\Models\cat_20_paises;

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
        $com_compras = new comp_compras;

        $contadorFiltros = 0;
        $numFactura = $this->request->getPost('numFactura');
        $fechaDocumento = $this->request->getPost('fechaFactura');
        $filtroProveedor = $this->request->getPost('nombreProveedor');
        
        $consultaCompras = $com_compras
                ->select('comp_compras.compraId,comp_proveedores.tipoProveedorOrigen, cat_02_tipo_dte.tipoDocumentoDTE, 
                          comp_compras.fechaDocumento, comp_compras.numFactura,
                          cat_20_paises.pais,comp_proveedores.proveedor,comp_proveedores.proveedorComercial')
                ->join('comp_proveedores','comp_proveedores.proveedorId = comp_compras.proveedorId')
                ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = comp_compras.tipoDTEId')
                ->join('cat_20_paises','cat_20_paises.paisId = comp_compras.paisId')
                ->where('comp_compras.flgElimina', 0);

        if($numFactura != "") {
            $consultaCompras->like('numFactura', $numFactura);
            $contadorFiltros++;
        }

        if($fechaDocumento != "") {
            $consultaCompras->where('fechaDocumento', $fechaDocumento);
            $contadorFiltros++;
        }

        if($filtroProveedor != "") {
            $consultaCompras->like('proveedorId', $filtroProveedor);
            $contadorFiltros++;
        }

        // Construye el array de salida
        $output['data'] = array();
        $n = 0; // Variable para contar las filas
        if($contadorFiltros > 0) {
            $datos = $consultaCompras->findAll();

            foreach ($datos as $columna) {
                $n++;
                // Aquí construye tus columnas
                $columna1 = $n;
                $columna2 = "<b>Numero de factura: </b>" . $columna['numFactura'] ."<br>" . "<b>País: </b>" . $columna['pais'] ."<br>" . "<b>Fecha de la compra: </b>" . $columna['fechaDocumento'];

                $columna3 = "<b>proveedor: </b>". $columna['proveedor'] ."<br>" . "<b>Nombre comercial: </b>". $columna['proveedorComercial'] ."<br>" ."<b>Tipo proveedor: </b>" . $columna['tipoProveedorOrigen'] ."<br>" . "<b>Tipo factura: </b>" . $columna['tipoDocumentoDTE'];
                $columna4 = "<b>Monto: </b>";
                
                $jsonActualiarCompra = [
                    "compraId"          => $columna['compraId']
                ];

                $columna5 = '
                    <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-compras/vista/actualizar/compra`, '.htmlspecialchars(json_encode($jsonActualiarCompra)).');" data-toggle="tooltip" data-placement="top" title="Continuar compra">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                ';

                $columna5 .= '
                    <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Anular compra">
                        <i class="fas fa-ban"></i>
                    </button>
                ';
                // Agrega la fila al array de salida
                $output['data'][] = array(
                    $columna1,
                    $columna2,
                    $columna3,
                    $columna4,
                    $columna5
                );
            }
        } else {
            $n = 0;
        }
        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalNuevaCompra(){
        $tipoDte = new cat_02_tipo_dte;
        $proveedor = new comp_proveedores;
        $pais = new cat_20_paises;

        $data['tipoDTE'] = $tipoDte
                        ->select("tipoDTEId,tipoDocumentoDTE")
                        ->where("flgElimina", 0)
                        ->findAll();
        $data['selectProveedor'] = $proveedor
                        ->select("proveedorId,proveedor")
                        ->where("flgElimina", 0)
                        ->findAll();
        $data['selectPais'] = $pais
                        ->select("paisId,pais")
                        ->where("flgElimina", 0)
                        ->findAll();                               

        return view('compras/modals/modalNuevaCompra', $data);
    }

    public function modalCompraOperacion(){
        $compras = new comp_compras;

        $data = [
            'proveedorId'       => $this->request->getPost('selectProveedor'),
            'tipoDTEId'         => $this->request->getPost('tipoDocumento'),
            'fechaDocumento'    => $this->request->getPost('fechaFactura'),
            'numFactura'        => $this->request->getPost('numeroFactura'),
            'paisId'            => $this->request->getPost('selectPais'),
            'flgRetaceo'        => $this->request->getPost('selectRetaceo'),
            'estadoCompra'      => 'Pendiente'

        ];

            // Insertar datos en la base de datos
            $operacionCompra = $compras->insert($data);

        if ($operacionCompra) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Compra agregada correctamente',
                'compraId' =>  $compras->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar la compra'
            ]);
        }
    }

    public function vistaActualizarCompra(){
        $session = session();

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras/admin-compras/vista/actualizar/compra',
            'camposSession'     => json_encode($camposSession)
        ]);

        $compraId = $this->request->getPost('compraId');

        $tipoDte = new cat_02_tipo_dte;
        $proveedor = new comp_proveedores;
        $pais = new cat_20_paises;
        $compras = new comp_compras;

        $data['tipoDTE'] = $tipoDte
                        ->select("tipoDTEId,tipoDocumentoDTE")
                        ->where("flgElimina", 0)
                        ->findAll();
        $data['selectProveedor'] = $proveedor
                        ->select("proveedorId,proveedor")
                        ->where("flgElimina", 0)
                        ->findAll();
        $data['selectPais'] = $pais
                        ->select("paisId,pais")
                        ->where("flgElimina", 0)
                        ->findAll();     

        $data['compraId'] = $compraId;

        // Consulta para traer los valores de los input que se pueden actualizar
        $consultaCompra = $compras
                ->select("proveedorId,tipoDTEId,numFactura,fechaDocumento,paisId,flgRetaceo")
                ->where("flgElimina", 0)
                ->where("compraId", $compraId)
                ->first();   

        $data['camposEncabezado'] = [
            'proveedorId'       => $consultaCompra['proveedorId'],
            'tipoDTEId'         => $consultaCompra['tipoDTEId'],
            'fechaDocumento'    => $consultaCompra['fechaDocumento'],
            'numFactura'        => $consultaCompra['numFactura'],
            'paisId'            => $consultaCompra['paisId'],
            'flgRetaceo'        => $consultaCompra['flgRetaceo']
            // Sacar estos valores de la consulta
        ];                      

        return view('compras/vistas/pageActualizarCompra', $data);
    }
}