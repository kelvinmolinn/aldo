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
use App\Models\inv_kardex;
use App\Models\conf_parametrizaciones;
use App\Models\conf_sucursales;
use App\Models\inv_productos_existencias;

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
                ->select('cat_tipo_contribuyente.tipoContribuyenteId,comp_compras.compraId,comp_proveedores.tipoProveedorOrigen, cat_02_tipo_dte.tipoDocumentoDTE, 
                          DATE_FORMAT(comp_compras.fechaDocumento, "%d-%m-%Y") as fechaDocumento, comp_compras.numFactura,
                          cat_20_paises.pais,comp_proveedores.proveedor,comp_proveedores.proveedorComercial')
                ->join('comp_proveedores','comp_proveedores.proveedorId = comp_compras.proveedorId')
                ->join('cat_tipo_contribuyente','cat_tipo_contribuyente.tipoContribuyenteId = comp_proveedores.tipoContribuyenteId')
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
                    "compraId"              => $columna['compraId'],
                    "tipoContribuyenteId"   => $columna['tipoContribuyenteId']
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
        $sucursal = new conf_sucursales;

        $data['tipoDTE'] = $tipoDte
                        ->select("tipoDTEId,tipoDocumentoDTE")
                        ->where("flgElimina", 0)
                        ->findAll();
        $data['selectProveedor'] = $proveedor
                        ->select("proveedorId,proveedor")
                        ->where("flgElimina", 0)
                        ->where("tipoProveedorOrigen", "Local")
                        ->findAll();
        $data['selectProveedorInternacionales'] = $proveedor
                        ->select("proveedorId,proveedor")
                        ->where("flgElimina", 0)
                        ->where("tipoProveedorOrigen", "Internacional")
                        ->findAll();

        $data['selectPais'] = $pais
                        ->select("paisId,pais")
                        ->where("flgElimina", 0)
                        ->findAll(); 
        $data['selectSucursal'] = $sucursal
                        ->select("sucursalId,sucursal")
                        ->where("flgElimina", 0)
                        ->findAll();
        return view('compras/modals/modalNuevaCompra', $data);
    }

    public function modalCompraOperacion(){
        // Consulta para traer el 13% de la parametrizacion
        $porcentajeIva = new conf_parametrizaciones;
        $compras = new comp_compras;

        $IVA = $porcentajeIva 
        ->select("valorParametrizacion")
        ->where("flgElimina", 0)
        ->where("parametrizacionId", 1)
        ->first(); 

        $IvaCalcular = $IVA['valorParametrizacion'];

        $numFactura = $this->request->getPost('numeroFactura');
        $proveedorId = $this->request->getPost('selectProveedor');
        $fecha = $this->request->getPost('fechaFactura');
        $proveedorInternacional = $this->request->getPost('selectProveedorInternacionales');
        $proveedorNacional = $this->request->getPost('selectProveedor');
        $tipoCompra = $this->request->getPost('selectTipoCompra');
        $sucursal = $this->request->getPost('selectSucursal');
        // Verificar si el numFactura ya existe
        $facturaExistente = $compras
            ->where('numFactura', $numFactura)
            ->where('proveedorId', $proveedorId)
            ->where('fechaDocumento', $fecha)
            ->first();
    
        if ($facturaExistente) {
            // Si el número de factura ya existe, devolver un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El número de factura ya está registrado'
            ]);
        } else {
            if($this->request->getPost('selectTipoCompra') == "Local"){
                $data = [
                    'proveedorId'       => $proveedorNacional,
                    'tipoCompra'        => $tipoCompra,
                    'sucursalId'        => $sucursal,
                    'tipoDTEId'         => $this->request->getPost('tipoDocumento'),
                    'fechaDocumento'    => $this->request->getPost('fechaFactura'),
                    'numFactura'        => $this->request->getPost('numeroFactura'),
                    'paisId'            => $this->request->getPost('selectPais'),
                    'flgRetaceo'        => $this->request->getPost('selectRetaceo'),
                    'estadoCompra'      => 'Pendiente',
                    'porcentajeIva'     => $IvaCalcular
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
            }else{
                $data = [
                    'proveedorId'       => $proveedorInternacional,
                    'tipoCompra'        => $tipoCompra,
                    'sucursalId'        => $sucursal,
                    'tipoDTEId'         => $this->request->getPost('tipoDocumento'),
                    'fechaDocumento'    => $this->request->getPost('fechaFactura'),
                    'numFactura'        => $this->request->getPost('numeroFactura'),
                    'paisId'            => $this->request->getPost('selectPais'),
                    'flgRetaceo'        => $this->request->getPost('selectRetaceo'),
                    'estadoCompra'      => 'Pendiente',
                    'porcentajeIva'     => $IvaCalcular
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

        }

    }

    public function vistaContinuarCompra(){
        $session = session();

        $compraId = $this->request->getPost('compraId');
        $tipoContribuyenteId = $this->request->getPost('tipoContribuyenteId');
        
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
        $data['tipoContribuyenteId'] = $tipoContribuyenteId;

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
        
        
        $porcentajeIva = new conf_parametrizaciones;

        $IVAPercibido = $porcentajeIva 
        ->select("valorParametrizacion")
        ->where("flgElimina", 0)
        ->where("parametrizacionId", 3)
        ->first(); 
        $percepcionIVA = ($IVAPercibido['valorParametrizacion'] / 100);
        $data['ivaPercibido'] = $percepcionIVA;

        $camposSession = [
            'renderVista' => 'No',
            'compraId'    => $compraId,
            'tipoContribuyenteId' => $tipoContribuyenteId,
            'ivaPercibido' => $percepcionIVA
        ];
        $session->set([
            'route'             => 'compras/admin-compras/vista/actualizar/compra',
            'camposSession'     => json_encode($camposSession)
        ]);

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
        $tipoContribuyenteId = $this->request->getPost('tipoContribuyenteId');
        $IVAPercibido = $this->request->getPost('ivaPercibido');

        $datos = $comprasDetalle
            ->select('comp_compras.paisId,inv_productos.codigoProducto,inv_productos.producto,cat_14_unidades_medida.abreviaturaUnidadMedida,comp_compras_detalle.compraDetalleId,comp_compras_detalle.compraId,comp_compras_detalle.productoId,comp_compras_detalle.cantidadProducto,comp_compras_detalle.precioUnitario,comp_compras_detalle.precioUnitarioIVA,comp_compras_detalle.ivaUnitario,comp_compras_detalle.ivaTotal,comp_compras_detalle.totalCompraDetalle,comp_compras_detalle.totalCompraDetalleIVA')
            ->join('comp_compras','comp_compras.compraId = comp_compras_detalle.compraId')
            ->join('inv_productos','inv_productos.productoId = comp_compras_detalle.productoId')
            ->join('cat_14_unidades_medida','cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
            ->where('comp_compras_detalle.flgElimina', 0)
            ->where('comp_compras_detalle.compraId', $compraId)
            ->findAll();

        $output['data'] = array();
        $n = 0; // Variable para contar las filas
        $totalCantidad = 0;
        $totalConIVA = 0;
        $totalSinIVA = 0;
        $totalIVA = 0;
           foreach ($datos as $columna) {
                $paisId = $columna['paisId'];
                $n++;
                // Aquí construye tus columnas
                if ($columna['paisId'] == 61) {
                    
                    $columna1 = $n;
                    $columna2 = '('.$columna['codigoProducto'].') ' . $columna['producto'];
                    $columna3 = '<b>Con IVA: </b>$ '. number_format($columna['precioUnitarioIVA'], 2, '.', ',') . '<br>' . '<b>Sin IVA: </b>$ ' .  number_format($columna['precioUnitario'], 2, '.', ',');
                    $columna4 = $columna['cantidadProducto'] . ' ('.$columna['abreviaturaUnidadMedida'].')';
                    $columna5 = '<b>Con IVA: </b>$ '. number_format($columna['totalCompraDetalleIVA'], 2, '.', ',') . '<br>' . '<b>Sin IVA: </b>$ ' . number_format($columna['totalCompraDetalle'], 2, '.', ',');
                    $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalAgregarProducto(`'.$columna['compraDetalleId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    ';

                    $columna6 .= '
                        <button type="button" class="btn btn-danger mb-1" onclick="eliminarProducto(`'.$columna['compraDetalleId'].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                    $columna2 = '('.$columna['codigoProducto'].') ' . $columna['producto'];;
                    $columna3 = '<b>Costo Unitario: </b>$ ' .  number_format($columna['precioUnitario'], 2, '.', ',');
                    $columna4 = $columna['cantidadProducto'] . ' ('.$columna['abreviaturaUnidadMedida'].')';
                    $columna5 = '<b>Costo total: </b>$ ' . number_format($columna['totalCompraDetalle'], 2, '.', ',');
                    $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalAgregarProducto(`'.$columna['compraDetalleId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                    ';

                    $columna6 .= '
                        <button type= "button" class="btn btn-danger mb-1" onclick="eliminarProducto(`'.$columna['compraDetalleId'].'`)" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

            $totalCantidad += $columna['cantidadProducto'];
            $UDM = $columna['abreviaturaUnidadMedida'];
            $totalConIVA += $columna['totalCompraDetalleIVA'];
            $totalSinIVA += $columna['totalCompraDetalle'];
            $totalIVA += $columna['ivaTotal'];
           }
           $Percibido = $totalSinIVA * $IVAPercibido;

           $totalPagar = $totalSinIVA + $totalIVA + $Percibido;
           $totalPagarSinPercepcion = $totalSinIVA + $totalIVA;
           $totalPagarInternacional = $totalSinIVA;
        // Verifica si hay datos
        if ($n > 0) {
            if($paisId == 61) {
                $output['footer'] = array(
                    '<b>Totales</b>',
                    $totalCantidad . ' ('. $UDM .')',
                    '<b>Total con IVA:</b> $ ' . number_format($totalConIVA, 2, '.', ',') . '<br>' . '<b>Total Sin IVA:</b> $ ' . number_format($totalSinIVA, 2, '.', ',')
                );
                if($tipoContribuyenteId == 1){
                    $output['footerTotales'] = '
                        <b>
                        <div class="row text-right">
                            <div class="col-8">
                                Subtotal
                            </div>
                            <div class="col-4">
                                $ '.number_format($totalSinIVA, 2, '.', ',').'
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                IVA 13%
                            </div>
                            <div class="col-4">
                                $ '.number_format($totalIVA, 2, '.', ',').'
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                (+) IVA Percibido
                            </div>
                            <div class="col-4">
                                $ '.number_format($Percibido, 2, '.', ',').'
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total a pagar
                            </div>
                            <div class="col-4">
                                $ '.number_format($totalPagar, 2, '.', ',').'
                            </div>
                        </div>                    
                        </b>
                    ';
                } else {
                    if($totalSinIVA >= 100.00){
                        $output['footerTotales'] = '
                            <b>
                            <div class="row text-right">
                                <div class="col-8">
                                    Subtotal
                                </div>
                                <div class="col-4">
                                    $ '.number_format($totalSinIVA, 2, '.', ',').'
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-8">
                                    IVA 13%
                                </div>
                                <div class="col-4">
                                    $ '.number_format($totalIVA, 2, '.', ',').'
                                </div>
                            </div>
                            <div class="row text-right">
                                <div class="col-8">
                                    Total a pagar
                                </div>
                                <div class="col-4">
                                    $ '.number_format($totalPagarSinPercepcion, 2, '.', ',').'
                                </div>
                            </div>                 
                            </b>
                            <div class="alert alert-warning " role="alert">
                                EL TOTAL A PAGAR NETO ES MAYOR O IGUAL A $100.00, PERO EL PROVEEDOR NO FUE REGISTRADO COMO GRAN CONTRIBUYENTE, POR FAVOR ACTUALICE LA INFORMACIÓN DEL PROVEEDOR PARA PODER APLICAR LA PERCEPCIÓN.
                            </div>  
                        ';

                    } else {

                    }
                    // IMPORTANTE: EL TOTAL A PAGAR NETO ES MAYOR O IGUAL A $100.00, PERO EL PROVEEDOR NO FUE REGISTRADO COMO GRAN CONTRIBUYENTE, POR FAVOR ACTUALICE LA INFORMACIÓN DEL PROVEEDOR PARA PODER APLICAR LA PERCEPCIÓN.


                }

            }  else {
                $output['footer'] = array(
                    '<b>Totales</b>',
                    $totalCantidad . ' ('. $UDM .')',
                    '<b>Total:</b> $ '. number_format($totalSinIVA, 2, '.', ',')
                );

                $output['footerTotales'] = '
                    <b>
                    <div class="row text-right">
                        <div class="col-8">
                            Subtotal
                        </div>
                        <div class="col-4">
                            $ '.number_format($totalPagarInternacional, 2, '.', ',').'
                        </div>
                    </div>
                    <div class="row text-right">
                        <div class="col-8">
                            Total a pagar
                        </div>
                        <div class="col-4">
                            $ '.number_format($totalPagarInternacional, 2, '.', ',').'
                        </div>
                    </div>
                    </b>

                ';
            }
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }
    }

    function modalAgregarProducto(){
        $compras = new comp_compras;
        $comprasDetalle = new comp_compras_detalle;
        $productos = new inv_productos;

        $operacion = $this->request->getPost('operacion');
        
        $compraId = $this->request->getPost('compraId');
        $compraDetalleId = $this->request->getPost('compraDetalleId');


        $data['producto'] = $productos
            ->select('productoId,producto')
            ->where('flgElimina', 0)
            ->findAll();
        
        $consultaCompra = $compras
            ->select("paisId, porcentajeIva")
            ->where("flgElimina", 0)
            ->where("compraId", $compraId)
            ->first(); 

        if($operacion == 'editar') {

            $data['campos'] = $comprasDetalle
            ->select('compraDetalleId,compraId,productoId,cantidadProducto,precioUnitario,precioUnitarioIVA,ivaUnitario,ivaTotal,totalCompraDetalle,totalCompraDetalleIVA')
            ->where('flgElimina', 0)
            ->where('compraDetalleId', $compraDetalleId)
            ->first();
        } else {
            if ($consultaCompra['paisId'] == 61 ) {
                $data['campos'] = [
                    'compraDetalleId'       => 0,
                    'compraId'              => '',
                    'productoId'            => '',
                    'cantidadProducto'      => '',
                    'precioUnitario'        => '0.00',
                    'precioUnitarioIVA'     => '',
                    'ivaUnitario'           => '0.00',
                    'ivaTotal'              => '0.00',
                    'totalCompraDetalle'    => '0.00',
                    'totalCompraDetalleIVA' => '0.00'
                ];
            }else{
                $data['campos'] = [
                    'compraDetalleId'       => 0,
                    'compraId'              => '',
                    'productoId'            => '',
                    'cantidadProducto'      => '',
                    'precioUnitario'        => '',
                    'precioUnitarioIVA'     => '',
                    'ivaUnitario'           => '0.00',
                    'ivaTotal'              => '0.00',
                    'totalCompraDetalle'    => '0.00',
                    'totalCompraDetalleIVA' => '0.00'
                ];
            }
        }
        $data['operacion'] = $operacion;



        $data['porcentajeIva'] = $consultaCompra['porcentajeIva'];
        $data['ivaMultiplicar'] = ($consultaCompra['porcentajeIva'] / 100) + 1;
        $data['compraDetalleId'] = $compraDetalleId;
        $data['compraId'] = $compraId;
        $data['paisId'] = $consultaCompra['paisId'];

        if ($consultaCompra['paisId'] == 61 ) {
            return view('compras/modals/modalAgregarProductoLocales', $data);
        }else{
            return view('compras/modals/modalAgregarProductoInternacionales', $data);

        }
    }

    function modalProductosOperacion(){
        $comprasDetalle = new comp_compras_detalle;

        $operacion = $this->request->getPost('operacion');
        $compraDetalleId = $this->request->getPost('compraDetalleId');

        $ivaMultiplicar =   $this->request->getPost('ivaMultiplicar');
        $cantidad =         $this->request->getPost('cantidadProducto');
        $precioUnitarioIva =   $this->request->getPost('costoUnitario');

        $precioUnitarioInternacional = $this->request->getPost('costoUnitario');


        $paisId =   $this->request->getPost('paisId');

        if($paisId == 61) {
            $precioUnitario         = $precioUnitarioIva / $ivaMultiplicar;
            $ivaUnitario            = $precioUnitarioIva - $precioUnitario;
            $ivaTotal               = $ivaUnitario * $cantidad;
            $totalCompraDetalle     = $precioUnitario * $cantidad;
            $totalCompraDetalleIVA  = $precioUnitarioIva * $cantidad;
        } else {
            $precioUnitario         = $precioUnitarioInternacional;
            $precioUnitarioiva      = 0;
            $ivaUnitario            = 0;
            $ivaTotal               = 0;
            $totalCompraDetalle     = $precioUnitario * $cantidad;
            $totalCompraDetalleIVA  = 0;
        }

        if($operacion == 'editar') {
            // Todo el camino de un "Editar", sin validar si existe o no, simplemente es un editar del registro
            if($paisId == 61){
                $data = [
                    "compraId"              => $this->request->getPost('compraId'),
                    "productoId"            => $this->request->getPost('selectProductos'),
                    "cantidadProducto"      => $this->request->getPost('cantidadProducto'),
                    "precioUnitario"        => $precioUnitario,
                    "precioUnitarioIVA"     => $precioUnitarioIva,
                    "ivaUnitario"           => $ivaUnitario,
                    "ivaTotal"              => $ivaTotal,
                    "totalCompraDetalle"    => $totalCompraDetalle,
                    "totalCompraDetalleIVA" => $totalCompraDetalleIVA
                ];
    
                    $productoCompras = $comprasDetalle->update($this->request->getPost('compraDetalleId'), $data);
    
                if ($productoCompras) {
                    // Si el insert fue exitoso, devuelve el último ID insertado
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'Producto de la compra actualizada correctamente',
                        'compraDetalleId' =>  $this->request->getPost('compraDetalleId')
                    ]);
                } else {
                    // Si el insert falló, devuelve un mensaje de error
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se pudo actualizar la compra'
                    ]);
                }
    
            } else {
    
                $data = [
                    "compraId"              => $this->request->getPost('compraId'),
                    "productoId"            => $this->request->getPost('selectProductos'),
                    "cantidadProducto"      => $this->request->getPost('cantidadProducto'),
                    "precioUnitario"        => $this->request->getPost('costoUnitario'),
                    "totalCompraDetalle"    => $totalCompraDetalle
                ];
    
                    $productoCompras = $comprasDetalle->update($this->request->getPost('compraDetalleId'), $data);

                if ($productoCompras) {
                    // Si el insert fue exitoso, devuelve el último ID insertado
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'Producto de la compra actualizada correctamente',
                        'compraDetalleId' =>  $this->request->getPost('compraDetalleId') 
                    ]);
                } else {
                    // Si el insert falló, devuelve un mensaje de error
                    return $this->response->setJSON([
                        'success' => false,
                        'mensaje' => 'No se pudo actualizar la compra'
                    ]);
                }
            }

        } else {
            // Todo el camino del "Agregar", evaluando y verificando si el producto ya fue agregado al mismo costo, recalculando, etc
            $ExisteProducto = $comprasDetalle
                ->select('compraDetalleId, cantidadProducto')
                ->where('compraId', $this->request->getPost('compraId'))
                ->where('productoId', $this->request->getPost('selectProductos'))
                ->where('precioUnitarioIVA', $precioUnitarioIva)
                ->first();
            if($ExisteProducto){
                $compraDetalleId = $ExisteProducto["compraDetalleId"];
                if($paisId == 61){
                    // Local pero ya existe un producto con ese costo, actualizar cantidades (sumar)
                    $cantidadProducto = $ExisteProducto["cantidadProducto"] + $this->request->getPost('cantidadProducto');
                    $ivaTotal = $cantidadProducto * $ivaUnitario;
                    $totalCompraDetalle = $cantidadProducto * $precioUnitario;
                    $totalCompraDetalleIVA = $cantidadProducto * $precioUnitarioIva;

                    $data = [
                        "cantidadProducto"      => $cantidadProducto,
                        "ivaTotal"              => $ivaTotal,
                        "totalCompraDetalle"    => $totalCompraDetalle,
                        "totalCompraDetalleIVA" => $totalCompraDetalleIVA
                    ];
        
                    $productoCompras = $comprasDetalle->update($compraDetalleId, $data);
        
                    if($productoCompras) {
                        // Si el insert fue exitoso, devuelve el último ID insertado
                        return $this->response->setJSON([
                            'success' => true,
                            'mensaje' => 'Producto de la compra agregado correctamente',
                            'compraDetalleId' => ($operacion == 'editar' ? $compraDetalleId : $comprasDetalle->insertID())
                        ]);
                    } else {
                        // Si el insert falló, devuelve un mensaje de error
                        return $this->response->setJSON([
                            'success' => false,
                            'mensaje' => 'No se pudo insertar el producto'
                        ]);
                    }
                } else {
                    // Internacional, pero ya existe un producto con ese costo, actualizar y usar de ejemplo el local para reasignar variables
                    $cantidadProducto = $ExisteProducto["cantidadProducto"] + $this->request->getPost('cantidadProducto');

                    $data = [
                        "cantidadProducto"      => $cantidadProducto,
                        "totalCompraDetalle"    => $totalCompraDetalle
                    ];
        
                        $productoCompras = $comprasDetalle->update($this->request->getPost('compraDetalleId'), $data);
    
                    if ($productoCompras) {
                        // Si el insert fue exitoso, devuelve el último ID insertado
                        return $this->response->setJSON([
                            'success' => true,
                            'mensaje' => 'Producto de la compra '.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
                            'compraDetalleId' => ($operacion == 'editar' ? $this->request->getPost('compraDetalleId') : $comprasDetalle->insertID())
                        ]);
                    } else {
                        // Si el insert falló, devuelve un mensaje de error
                        return $this->response->setJSON([
                            'success' => false,
                            'mensaje' => 'No se pudo insertar el producto'
                        ]);
                    }
                }  
            } else {
                // Insert normal, no existe el producto con ese costo
                if($paisId == 61){
                    $data = [
                        "compraId"              => $this->request->getPost('compraId'),
                        "productoId"            => $this->request->getPost('selectProductos'),
                        "cantidadProducto"      => $this->request->getPost('cantidadProducto'),
                        "precioUnitario"        => $precioUnitario,
                        "precioUnitarioIVA"     => $precioUnitarioIva,
                        "ivaUnitario"           => $ivaUnitario,
                        "ivaTotal"              => $ivaTotal,
                        "totalCompraDetalle"    => $totalCompraDetalle,
                        "totalCompraDetalleIVA" => $totalCompraDetalleIVA
                    ];
        
                    // Insertar datos en la base de datos
                    $productoCompras = $comprasDetalle->insert($data);

                    if ($productoCompras) {
                        // Si el insert fue exitoso, devuelve el último ID insertado
                        return $this->response->setJSON([
                            'success' => true,
                            'mensaje' => 'Producto de la compra agregado correctamente',
                            'compraDetalleId' =>  $this->request->getPost('compraDetalleId') 
                        ]);
                    } else {
                        // Si el insert falló, devuelve un mensaje de error
                        return $this->response->setJSON([
                            'success' => false,
                            'mensaje' => 'No se pudo insertar la compra'
                        ]);
                    }
                } else {
                    $data = [
                        "compraId"              => $this->request->getPost('compraId'),
                        "productoId"            => $this->request->getPost('selectProductos'),
                        "cantidadProducto"      => $this->request->getPost('cantidadProducto'),
                        "precioUnitario"        => $this->request->getPost('costoUnitario'),
                        "precioUnitarioIVA"     => '0.00',
                        "ivaUnitario"           => '0.00',
                        "ivaTotal"              => '0.00',
                        "totalCompraDetalle"    => $totalCompraDetalle,
                        "totalCompraDetalleIVA" => '0.00'
                    ];
        
                    // Insertar datos en la base de datos
                     $productoCompras = $comprasDetalle->insert($data);

                    if ($productoCompras) {
                        // Si el insert fue exitoso, devuelve el último ID insertado
                        return $this->response->setJSON([
                            'success' => true,
                            'mensaje' => 'Producto de la compra agregada correctamente',
                            'compraDetalleId' => $this->request->getPost('compraDetalleId') 
                        ]);
                    } else {
                        // Si el insert falló, devuelve un mensaje de error
                        return $this->response->setJSON([
                            'success' => false,
                            'mensaje' => 'No se pudo insertar la compra'
                        ]);
                    }
                }
            }
        }

    }
    function eliminarProductoCompra(){
        $eliminarProducto = new comp_compras_detalle();
        
            $compraDetalleId = $this->request->getPost('compraDetalleId');
            $data = ['flgElimina' => 1];
            
            $eliminar = $eliminarProducto->update($compraDetalleId, $data);

            if($eliminar) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Producto eliminado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo eliminar el producto'
                ]);
            }
    }

    function finalizarCompra(){
        $comp_compras = new comp_compras;
        $comp_compras_detalle = new comp_compras_detalle;
        $inv_kardex = new inv_kardex;
        $inv_productos_existencias = new inv_productos_existencias;
        $inv_productos = new inv_productos;
    
        $compraId = $this->request->getPost("compraId");

        $compras = $comp_compras 
            ->select("sucursalId,fechaDocumento,flgRetaceo")
            ->where("compraId",$compraId)
            ->first();

        if($compras['flgRetaceo'] == "Si"){
            //aplica a retaceo
        }else{
            $comprasDetalle = $comp_compras_detalle 
                ->select("productoId,cantidadProducto")
                ->where("compraId", $compraId)
                ->findAll()
            foreach($comprasDetalle AS $comprasDetalle){
                $consultaInventario = $inv_productos_existencias
                    ->select("productoExistenciaId,existenciaProducto")
                    ->where("productoId", $comprasDetalle['productoId'])
                    ->where("sucursalId", $compras['sucursalId'])
                    ->first();

                    $existenciaAntes = $consultaInventario["existenciaProducto"];
                    $cantidadMovimiento = $comprasDetalle["cantidadProducto"];
                    $existenciaDespues = $existenciaAntes + $cantidadMovimiento

                $consultaProveedor = $inv_productos
                    ->select("CostoPromedio,precioVenta")
                    ->where("productoId", $comprasDetalle['productoId'])
                    ->first();

                $data = [
                        
                    ];
        
                    // Insertar datos en la base de datos
                    $insertKardex = $inv_kardex->insert($data);

                    if ($insertKardex) {
                        // Si el insert fue exitoso, devuelve el último ID insertado
                        return $this->response->setJSON([
                            'success' => true,
                            'mensaje' => 'Productos agregados al kardex correctamente',
                            'compraDetalleId' =>  $this->request->getPost('compraDetalleId') 
                        ]);
                    } else {
                        // Si el insert falló, devuelve un mensaje de error
                        return $this->response->setJSON([
                            'success' => false,
                            'mensaje' => 'No se pudo insertar el producto al kardex'
                        ]);
                    }

            }
        }
    }
}
