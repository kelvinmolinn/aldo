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
use App\Models\comp_compras_detalle;
use App\Models\inv_productos;

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
            $consultaCompras->like('comp_compras.numFactura', $numFactura);
            $contadorFiltros++;
        }

        if($fechaDocumento != "") {
            $consultaCompras->where('comp_compras.fechaDocumento', $fechaDocumento);
            $contadorFiltros++;
        }

        if($filtroProveedor != "") {
            $consultaCompras->like('comp_proveedores.proveedor', $filtroProveedor);
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
                
                $jsonActualizarCompra = [
                    "compraId"          => $columna['compraId']
                ];

                $columna5 = '
                    <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-compras/vista/actualizar/compra`, '.htmlspecialchars(json_encode($jsonActualizarCompra)).');" data-toggle="tooltip" data-placement="top" title="Continuar compra">
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

    public function vistaContinuarCompra(){
        $session = session();

        $compraId = $this->request->getPost('compraId');


        $camposSession = [
            'renderVista' => 'No',
            'compraId'    => $compraId
        ];
        $session->set([
            'route'             => 'compras/admin-compras/vista/actualizar/compra',
            'camposSession'     => json_encode($camposSession)
        ]);

        
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

    function vistaActualizarCompraOperacion(){
        $compras = new comp_compras;

        $data = [
            'proveedorId'       => $this->request->getPost('selectProveedor'),
            'tipoDTEId'         => $this->request->getPost('tipoDocumento'),
            'fechaDocumento'    => $this->request->getPost('fechaFactura'),
            'numFactura'        => $this->request->getPost('numeroFactura'),
            'paisId'            => $this->request->getPost('selectPais'),
            'flgRetaceo'        => $this->request->getPost('selectRetaceo')

        ];

            // Insertar datos en la base de datos
            $operacionCompra = $compras->update($this->request->getPost('compraId'), $data);

        if ($operacionCompra) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Compra actualizada correctamente',
                'compraId' => $this->request->getPost('compraId')
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo actualizar la compra'
            ]);
        }
    }

    function tablaContinuarCompras(){
        $comprasDetalle = new comp_compras_detalle;
        $compras = new comp_compras;
        $compraId = $this->request->getPost('compraId');

        $datos = $comprasDetalle
            ->select('comp_compras.paisId,comp_compras_detalle.compraDetalleId,comp_compras_detalle.compraId,comp_compras_detalle.productoId,comp_compras_detalle.cantidadProducto,comp_compras_detalle.precioUnitario,comp_compras_detalle.precioUnitarioIVA,comp_compras_detalle.ivaUnitario,comp_compras_detalle.ivaTotal,comp_compras_detalle.totalCompraDetalle,comp_compras_detalle.totalCompraDetalleIVA')
            ->join('comp_compras','comp_compras.compraId = comp_compras_detalle.compraId')
            ->where('comp_compras_detalle.flgElimina', 0)
            ->where('comp_compras_detalle.compraId', $compraId)
            ->findAll();

        $output['data'] = array();
        $n = 0; // Variable para contar las filas
           foreach ($datos as $columna) {
                $n++;
                // Aquí construye tus columnas
                if ($columna['paisId'] == 61) {
                    
                    $columna1 = $n;
                    $columna2 = '1';
                    $columna3 = '<b>Con IVA</b>' . '<br>' . '<b>Sin IVA</b>';
                    $columna4 = '5';
                    $columna5 = '<b>Con IVA</b>' . '<br>' . '<b>Sin IVA</b>';
                    $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalAgregarProducto(`'.$columna['compraDetalleId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    ';

                    $columna6 .= '
                        <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    // Agrega la fila al array de salida
                    $output['data'][] = array(
                        $columna1,
                        $columna2,
                        $columna3,
                        $columna4,
                        $columna5,
                        $columna6
                    );
                }else{
                    $columna1 = $n;
                    $columna2 = '1';
                    $columna3 = '12';
                    $columna4 = '5';
                    $columna5 = '20';
                    $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalAgregarProducto(`'.$columna['compraDetalleId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    ';

                    $columna6 .= '
                        <button class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                    // Agrega la fila al array de salida
                    $output['data'][] = array(
                        $columna1,
                        $columna2,
                        $columna3,
                        $columna4,
                        $columna5,
                        $columna6
                    );
                }

           }

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    function modalAgregarProducto(){

        
        $compras = new comp_compras;
        $comprasDetalle = new comp_compras_detalle;
        $productos = new inv_productos;

        $operacion = $this->request->getPost('operacion');
        
        $compraId = $this->request->getPost('compraId');


        $data['producto'] = $productos
            ->select('productoId,producto')
            ->where('flgElimina', 0)
            ->findAll();

        if($operacion == 'editar') {

            $data['campos'] = $comprasDetalle
            ->select('compraDetalleId,compraId,productoId,cantidadProducto,precioUnitario,precioUnitarioIVA,ivaUnitario,ivaTotal,totalCompraDetalle,totalCompraDetalleIVA')
            ->where('flgElimina', 0)
            ->where('compraId', $compraId)
            ->first();
        } else {
            $data['campos'] = [
                'compraDetalleId'       => 0,
                'compraId'              => '',
                'productoId'            => '',
                'cantidadProducto'      => '',
                'precioUnitario'        => '',
                'precioUnitarioIVA'     => '',
                'ivaUnitario'           => '',
                'ivaTotal'              => '',
                'totalCompraDetalle'    => '',
                'totalCompraDetalleIVA' => ''
            ];
        }
        $data['operacion'] = $operacion;

        $consultaCompra = $compras
        ->select("paisId")
        ->where("flgElimina", 0)
        ->where("compraId", $compraId)
        ->first(); 

        $data['variable'] = 0;

        if ($consultaCompra['paisId'] == 61 ) {
            return view('compras/modals/modalAgregarProductoLocales', $data);
        }else{
            return view('compras/modals/modalAgregarProductoInternacionales', $data);

        }
    }
}