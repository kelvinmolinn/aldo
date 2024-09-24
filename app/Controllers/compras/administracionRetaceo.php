<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;
use App\Models\comp_retaceo;
use App\Models\comp_retaceo_detalle;
use App\Models\comp_compras;
use App\Models\comp_compras_detalle;
use App\Models\inv_kardex;
use App\Models\inv_productos_existencias;
use App\Models\inv_productos;

class administracionRetaceo extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras/admin-retaceo/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('compras/vistas/retaceo', $data);
    }

    public function tablaRetaceo(){
        $comp_retaceo = new comp_retaceo;
        $compRetaceoDetalle = new comp_retaceo_detalle();

        $consultaRetaceo = $comp_retaceo
                ->select('retaceoId,numRetaceo,totalFlete,totalGastos,estadoRetaceo')
                ->where('flgElimina', 0)
                ->findAll();


        $output['data'] = array();
        $n = 0;
        foreach($consultaRetaceo as $consultaRetaceo){
            $n++;
            $numeroFacturas = [];
            $consultaFacturas = $compRetaceoDetalle
                ->select('comp_compras.numFactura,comp_retaceo_detalle.costoTotal')
                ->join('comp_compras_detalle','comp_compras_detalle.compraDetalleId = comp_retaceo_detalle.compraDetalleId')
                ->join('comp_compras','comp_compras.compraId = comp_compras_detalle.compraId')
                ->where('comp_retaceo_detalle.retaceoId',$consultaRetaceo['retaceoId'])
                ->where('comp_retaceo_detalle.flgElimina', 0)
                ->findAll();

            $totalCosto = 0;
            foreach($consultaFacturas AS $facturas){
                $numeroFacturas[] = $facturas['numFactura'];
                $totalCosto += $facturas['costoTotal'];
            }

            $numeroFacturasString = implode(', ', $numeroFacturas);
            // Aquí construye tus columnas
            if ($consultaRetaceo['estadoRetaceo'] == "Finalizado") {
                $estadoRetaceo = "<span class='font-weight-bold text-success'>".$consultaRetaceo['estadoRetaceo']."</span>";

            }else{
                $estadoRetaceo = "<span class='font-weight-bold text-warning'>".$consultaRetaceo['estadoRetaceo']."</span>";
            }

            $ExisteProducto = $compRetaceoDetalle
                ->where("flgElimina",0)
                ->where('retaceoId', $consultaRetaceo['retaceoId'])
                ->countAllResults();

            $columna1 = $n;
            $columna2 = "<b>N° de retaceo: </b> ".$consultaRetaceo['numRetaceo'] . "<br>" . "<b>Factura(s): </b> ".$numeroFacturasString . "<br>" . "<b>Estado: </b> ".$estadoRetaceo . "<br>" . "<b>Total de productos: </b> ".$ExisteProducto;
        
            $columna3 = "<b>Flete: </b> ". number_format($consultaRetaceo['totalFlete'], 2, ".", ",") . "<br>" . "<b>Gastos: </b> ". number_format($consultaRetaceo['totalGastos'], 2, ".", ",") . "<br>" . "<b>Costo total: </b>$ ".number_format($totalCosto, 2, ".", ",");


            
            if ($consultaRetaceo['estadoRetaceo'] == "Finalizado") {
                $jsonContinuarRetaceo = [
                    "retaceoId"      => $consultaRetaceo['retaceoId']
                ];

                $columna4 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-retaceo/vista/ver/retaceo`,'.htmlspecialchars(json_encode($jsonContinuarRetaceo)).');" data-toggle="tooltip" data-placement="top" title="Ver retaceo">
                            <i class="fas fa-eye"></i>
                        </button>';
            }else{                
                $jsonContinuarRetaceo = [
                    "retaceoId"      => $consultaRetaceo['retaceoId']
                ];

                $columna4 = '
                                <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-retaceo/vista/continuar/retaceo`,'.htmlspecialchars(json_encode($jsonContinuarRetaceo)).');" data-toggle="tooltip" data-placement="top" title="Continuar retaceo">
                                    <i class="fas fa-sync-alt"></i>
                                </button>';
        
                $columna4 .= '
                                 <button type= "button" class="btn btn-danger mb-1" onclick="modalAnularRetaceo('.$consultaRetaceo["retaceoId"].')" data-toggle="tooltip" data-placement="top" title="Anular">
                                    <i class="fas fa-ban"></i>
                                </button>
                            ';
            }

                $output['data'][] = array(
                    $columna1,
                    $columna2,
                    $columna3,
                    $columna4
                );

        }
        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalNuevoRetaceo(){
        
        $data['variable'] = 0;
    return view('compras/modals/modalNuevoRetaceo', $data);
    }

    public function modalRetaceoOperacion(){
        $comp_retaceo = new comp_retaceo;
        
        $data = [
            "numRetaceo"       => $this->request->getPost('numeroFactura'),
            "fechaRetaceo"     => $this->request->getPost('fechaRetaceo'),
            "totalFlete"       => $this->request->getPost('fleteRetaceo'),
            "totalGastos"      => $this->request->getPost('GastosRetaceo'),
            //"obsRetaceo"       => $this->request->getPost('observacionRetaceo'),
            "estadoRetaceo"    => "Pendiente"
        ];

        // Insertar datos en la base de datos
        $nuevoRetaceo = $comp_retaceo->insert($data);

        if ($nuevoRetaceo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'El retaceo de la compra agregado correctamente',
                'retaceoId' =>  $comp_retaceo->insertID() 
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el retaceo'
            ]);
        }
    }

    public function modalAnularRetaceo(){
        $comp_retaceo = new comp_retaceo();
        $compRetaceoDetalle = new comp_retaceo_detalle();

        $retaceoId = $this->request->getPost('retaceoId');

        $data['campos'] = $comp_retaceo
        ->select('retaceoId,numRetaceo,totalFlete,totalGastos,estadoRetaceo')
        ->where('flgElimina', 0)
        ->where('retaceoId', $retaceoId)
        ->first();

        $costoTotal = 0;
        $datos = $compRetaceoDetalle 
                ->select('costoTotal')
                ->where('flgElimina', 0)
                ->where('retaceoId', $retaceoId)
                ->findAll();

        foreach($datos AS $costo){
            $costoTotal += $costo['costoTotal'];
        }

        $data['costoTotalRetaceo'] = $costoTotal;

        $data['variable'] = 0;
        return view('compras/modals/modalAnularRetaceo', $data);
    }

    public function operacionAnularRetaceo(){
        $anularRetaceo = new comp_retaceo();
        
            $retaceoId = $this->request->getPost('retaceoId');
            $observacionAnulacion = $this->request->getPost('observacionAnulacion');

            $data = [
                'flgElimina'    => 0,
                'estadoRetaceo' => "Anulado",
                'obsAnulacion'  =>  $observacionAnulacion
            ];
            
            $anularRetaceo->update($retaceoId, $data);

            if($anularRetaceo) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Retaceo Anulado correctamente'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo anular el retaceo'
                ]);
            }
    }

    public function vistaContinuarRetaceo(){
        $session = session();

        $comp_retaceo = new comp_retaceo();

        $retaceoId = $this->request->getPost('retaceoId');

        $consultaRetaceo = $comp_retaceo
                ->select("numRetaceo,fechaRetaceo,totalFlete,totalGastos")
                ->where("flgElimina", 0)
                ->where("retaceoId", $retaceoId)
                ->first();   

        $data['camposEncabezado'] = [
            'numRetaceo'         => $consultaRetaceo['numRetaceo'],
            'fechaRetaceo'       => $consultaRetaceo['fechaRetaceo'],
            'totalFlete'         => $consultaRetaceo['totalFlete'],
            'totalGastos'        => $consultaRetaceo['totalGastos']
            // Sacar estos valores de la consulta
        ];

        $data['retaceoId'] = $retaceoId;

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No',
            'retaceoId'   => $retaceoId
        ];
        $session->set([
            'route'             => 'compras/admin-retaceo/vista/continuar/retaceo',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('compras/vistas/pageContinuarRetaceo', $data);
    }

    public function vistaActualizarRetaceo(){
        $comp_retaceo = new comp_retaceo();

        $data = [
            'numRetaceo'        => $this->request->getPost('numeroRetaceo'),
            'fechaRetaceo'      => $this->request->getPost('fechaRetaceo'),
            'totalFlete'        => $this->request->getPost('fleteContinuarRetaceo'),
            'totalGastos'       => $this->request->getPost('GastosContinuarRetaceo')

        ];

            // Insertar datos en la base de datos
            $operacionRetaceo = $comp_retaceo->update($this->request->getPost('retaceoId'), $data);

        if ($operacionRetaceo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Retaceo actualizado correctamente',
                'retaceoId' => $this->request->getPost('retaceoId')
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo actualizar el retaceo'
            ]);
        }
    }
    
    public function tablaContinuarRetaceo(){
        $compRetaceoDetalle = new comp_retaceo_detalle();
        $output['data'] = array();
        $n = 0;

        $retaceoId = $this->request->getPost('retaceoId');
        $numDocumento = $this->request->getPost('numDocumento');

        $consultaRetaceoDetalle = $compRetaceoDetalle
                ->select("comp_retaceo_detalle.retaceoDetalleId,comp_retaceo_detalle.retaceoId,comp_retaceo_detalle.compraDetalleId,comp_retaceo_detalle.cantidadProducto,comp_retaceo_detalle.precioFOBUnitario,comp_retaceo_detalle.importe,comp_retaceo_detalle.flete,comp_retaceo_detalle.gasto,comp_retaceo_detalle.DAI,comp_retaceo_detalle.costoUnitarioRetaceo,comp_retaceo_detalle.costoTotal,inv_productos.codigoProducto,inv_productos.producto,inv_productos.precioVenta")
                ->join("comp_compras_detalle", "comp_compras_detalle.compraDetalleId = comp_retaceo_detalle.compraDetalleId")
                ->join("inv_productos", "inv_productos.productoId = comp_compras_detalle.productoId")
                ->where("comp_retaceo_detalle.flgElimina", 0)
                ->where("comp_retaceo_detalle.retaceoId", $retaceoId)
                ->findAll();

        $totalCantidad = 0;
        $totalPrecioUnitarioFOB = 0;
        $totalFlete = 0;
        $totalGastos = 0;
        $totalCosto = 0;

        foreach($consultaRetaceoDetalle AS $tableRetaceoDetalle){
            $n++;
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "(".$tableRetaceoDetalle['codigoProducto'].")". $tableRetaceoDetalle['producto'];

            $columna3 = number_format($tableRetaceoDetalle['cantidadProducto'], 0, ".", ",");

            $columna4 = "$ " . number_format($tableRetaceoDetalle['precioFOBUnitario'], 2, ".", ",");

            $columna5 = "$ " . number_format($tableRetaceoDetalle['importe'], 2, ".", ",");

            $columna6 = "$ " . number_format($tableRetaceoDetalle['flete'], 2, ".", ",");

            $columna7 = "$ " . number_format($tableRetaceoDetalle['gasto'], 2, ".", ",");

            $columna8 = "$ " . number_format($tableRetaceoDetalle['DAI'], 2, ".", ",");

            $columna9 = "$ " . number_format($tableRetaceoDetalle['costoUnitarioRetaceo'], 2, ".", ",");

            $columna10 = "$ " . number_format($tableRetaceoDetalle['costoTotal'], 2, ".", ",");

            $columna11 = "$ " . number_format($tableRetaceoDetalle['precioVenta'], 2, ".", ",");

            $jsonDAI = [
                "codigoProducto"        => $tableRetaceoDetalle['codigoProducto'],
                "producto"              => $tableRetaceoDetalle['producto'],
                "retaceoDetalleId"      => $tableRetaceoDetalle['retaceoDetalleId']
            ];
            
            $columna12 = '  
                <button class="btn btn-primary mb-1" onclick="modalAgregarDAI('.htmlspecialchars(json_encode($jsonDAI)).')" data-toggle="tooltip" data-placement="top" title="DAI">
                    <i class="fas fa-address-book"></i> DAI
                </button>';

            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
                $columna6,
                $columna7,
                $columna8,
                $columna9,
                $columna10,
                $columna11,
                $columna12
            );

            $totalCantidad += $tableRetaceoDetalle['cantidadProducto'];
            $totalPrecioUnitarioFOB += $tableRetaceoDetalle['precioFOBUnitario'];
            $totalFlete += $tableRetaceoDetalle['flete'];
            $totalGastos += $tableRetaceoDetalle['gasto'];
            $totalCosto += $tableRetaceoDetalle['costoTotal'];
        } 


        // Verifica si hay datos
        if ($n > 0) {
                $output['footer'] = array(
                    '<div class="text-left"><b>Total</b></div>',
                    '<div class="text-left"><b>'.number_format($totalCantidad, 0, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalPrecioUnitarioFOB, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalFlete, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalGastos, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalCosto, 2, ".", ",").'</b></div>'
                 );
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalNuevaCompraRetaceo(){
        $comp_compras = new comp_compras();
        $comp_compras_detalle = new comp_compras_detalle();

        // Este select debe ser solo a comp_compras y de compras_detalle debe ser un SUM del totalcompradetalle
        // SELECT c.compraiD, c.numfactura, SUM(SELECT cd.totalcompradetalle FROM comp_compras_detalle WHERE cd.compraId = c.compraId AND cd.flgELimina = 0) FROM comp_compras c WHERE flgElimina, flgRetaceo, estadocompra
        
            $data['campos'] = $comp_compras
            ->select('(SELECT SUM(comp_compras_detalle.totalCompraDetalle) FROM comp_compras_detalle WHERE comp_compras_detalle.compraId = comp_compras.compraId) AS totalComprasDetalleRetaceo', false)
            ->select('comp_compras.compraId,comp_compras.numFactura')
            ->where('comp_compras.flgElimina', 0)
            ->where('comp_compras.flgRetaceo', 'Si')
            ->where('comp_compras.estadoCompra', 'Finalizada')
            ->findAll();

        $retaceoId = $this->request->getPost('retaceoId');

        $data['retaceoId'] = $retaceoId;
        
    $data['variable'] = 0;
    return view('compras/modals/modalAgregarCompraRetaceo', $data);
    
    }

    public function modalAgregarDAI(){
        $data['variable'] = 0;

        $data['numDocumento']           = $this->request->getPost('numDocumento');
        $data['codigoProducto']         = $this->request->getPost('codigoProducto');
        $data['producto']               = $this->request->getPost('producto');
        $data['retaceoDetalleId']       = $this->request->getPost('retaceoDetalleId');

        return view('compras/modals/modalAgregarDAI', $data);
    }

    public function modalCompraRetaceoOperacion(){
        
        $compraDetalle = new comp_compras_detalle();
        $comp_compras = new comp_compras();

        $compraRetaceo = $this->request->getPost('selectCompraRetaceo');
        $retaceoId = $this->request->getPost('retaceoId');


        $compDetalle = $compraDetalle
        ->select("compraDetalleId,compraId,cantidadProducto,precioUnitario")
        ->where("flgElimina",0)
        ->where("compraId",$compraRetaceo)
        ->findAll();

        foreach($compDetalle as $compDetalle){
           $retaceoDetalle = new comp_retaceo_detalle();
           $compraId = $compDetalle['compraId'];

            $totalCompraDetalle =  ($compDetalle['precioUnitario'] * $compDetalle['cantidadProducto']);

            $data = [
                "retaceoId"            => $retaceoId,
                "compraDetalleId"      => $compDetalle['compraDetalleId'],
                "cantidadProducto"     => $compDetalle['cantidadProducto'],
                "precioFOBUnitario"    => $compDetalle['precioUnitario'],
                "importe"              => $totalCompraDetalle,
                'DAI'                  => 0
            ];

            // Insertar datos en la base de datos
            $nuevoRetaceoCompra = $retaceoDetalle->insert($data);
        }

        $data = [
            'estadoCompra'   => "Aplicado"

        ];
            // Insertar datos en la base de datos
            $operacionEstadoCompra = $comp_compras->update($compraId, $data);

        if ($operacionEstadoCompra) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Compra agregada al retaceo con exito correctamente',
                'compraId' => $compraId
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo agregar la compra al retaceo'
            ]);
        }
    }

    public function calcularRetaceo(){
        $compRetaceo = new comp_retaceo();
        $retaceoDetalle = new comp_retaceo_detalle();

        $retaceoId = $this->request->getPost('retaceoId');

        $totales = $compRetaceo
            ->select('totalFlete,totalGastos')
            ->where('flgElimina', 0)
            ->where('retaceoId', $retaceoId)
            ->first();

        $totalFlete = $totales['totalFlete'];
        $totalGastos = $totales['totalGastos'];

        $sumRetaceo = $compRetaceo
            ->select('(SELECT SUM(comp_retaceo_detalle.importe) FROM comp_retaceo_detalle WHERE comp_retaceo_detalle.retaceoId = comp_retaceo.retaceoId) AS totalImporte', false)
            ->where('flgElimina', 0)
            ->where('retaceoId', $retaceoId)
            ->first();
        $totalImporte = $sumRetaceo['totalImporte'];
        $calcularDetalle = $retaceoDetalle 
        ->select('retaceoDetalleId, retaceoId, compraDetalleId, cantidadProducto, precioFOBUnitario, importe, flete, gasto, DAI, costoUnitarioRetaceo, costoTotal')
        ->where('flgElimina', 0)
        ->where('retaceoId', $retaceoId)
        ->findAll();

        foreach($calcularDetalle as $calcular){
            $dai = $calcular['DAI'];

            $importeDetalle = $calcular['importe'];

            $flete = ($importeDetalle / $totalImporte) * $totalFlete;
        
            if($totalGastos > 0 || $totalGastos != "") {
                $gasto = ($importeDetalle / $totalImporte) * $totalGastos;
            } else {
                $gasto = 0;
            }

            $costoTotal = $importeDetalle + $flete + $gasto + $dai;
            $costoUnitario = $costoTotal / $calcular['cantidadProducto'];


            $data = [
                'flete'                 => $flete,
                'gasto'                 => $gasto,
                'DAI'                   => $dai,
                'costoUnitarioRetaceo'  => $costoUnitario,
                'costoTotal'            => $costoTotal

            ];

                // Insertar datos en la base de datos
                $operacionCalculoRetaceo = $retaceoDetalle->update($calcular['retaceoDetalleId'], $data);

        }
        if ($operacionCalculoRetaceo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Retaceo calculado correctamente',
                'retaceoDetalleId' => $calcular['retaceoDetalleId']
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo calcular el retaceo'
            ]);
        }
    }
    public function modalOperacionDai(){
        $retaceoDetalle = new comp_retaceo_detalle();
        
        $retaceoDetalleId = $this->request->getPost('retaceoDetalleId');

        $data = [
            'DAI'       => $this->request->getPost('DAI')
        ];

            // Insertar datos en la base de datos
            $operacionCalculoDAI = $retaceoDetalle->update($retaceoDetalleId, $data);

        if ($operacionCalculoDAI) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Retaceo calculado correctamente',
                'retaceoDetalleId' => $retaceoDetalleId
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo calcular el DAI'
            ]);
        }
    }

    public function finalizarRetaceo(){
        $inv_kardex = new inv_kardex();
        $comp_retaceo = new comp_retaceo();
        $retaceoDetalle = new comp_retaceo_detalle();
        $inv_productos_existencias = new inv_productos_existencias();
        $comprasDetalle = new comp_compras_detalle();
        $invProductos = new inv_productos();
        $comp_compras = new comp_compras();

        $retaceoId = $this->request->getPost('retaceoId');
        $observacionFinalizarRetaceo = $this->request->getPost('observacionFinalizarCompra');

        $datosRetaceoDetalle = $retaceoDetalle
                            ->select('comp_retaceo_detalle.retaceoDetalleId,comp_compras.sucursalId , comp_compras.fechaDocumento, comp_compras.compraId,comp_retaceo_detalle.cantidadProducto, comp_retaceo_detalle.compraDetalleId,comp_retaceo_detalle.costoUnitarioRetaceo,comp_compras_detalle.productoId,comp_compras_detalle.precioUnitario,inv_productos.precioVenta,inv_productos.CostoPromedio')
                            ->join('comp_compras_detalle','comp_compras_detalle.compraDetalleId = comp_retaceo_detalle.compraDetalleId')
                            ->join('comp_compras', 'comp_compras.compraId = comp_compras_detalle.compraId')
                            ->join('inv_productos','inv_productos.productoId = comp_compras_detalle.productoId')
                            ->where('comp_retaceo_detalle.flgElimina', 0)
                            ->where('comp_retaceo_detalle.retaceoId', $retaceoId)
                            ->findAll();


        foreach($datosRetaceoDetalle AS $detalle){

            $sucursal = $detalle['sucursalId'];
            $productoId = $detalle['productoId'];

            $productosExis = $inv_productos_existencias 
                             ->select('productoExistenciaId,existenciaProducto')
                             ->where('flgElimina', 0)
                             ->where('sucursalId', $sucursal)
                             ->where('productoId', $productoId)
                             ->first();

            /*$precios = $comprasDetalle
                                ->select('comp_compras_detalle.precioUnitario,inv_productos.precioVenta')
                                ->join('inv_productos','inv_productos.productoId = comp_compras_detalle.productoId')
                                ->where('comp_compras_detalle.flgElimina', 0)
                                ->where('comp_compras_detalle.compraDetalleId',$detalle['compraDetalleId'])
                                ->where('inv_productos.productoId', $productoId)
                                ->first();*/

            if (!$productosExis) {
                $dataInsert = [
                    'sucursalId'         => $sucursal,
                    'productoId'         => $productoId,
                    'existenciaProducto' => 0
                ];

                $productosExisId = $inv_productos_existencias->insert($dataInsert);
                // Recuperar el ID del nuevo registro insertado
                $productosExis = [
                    'productoExistenciaId' => $productosExisId,
                    'existenciaProducto'   => 0
                ];
            }

            // Aplicar formula de costo promedio y asignar esa variable a costopromedio de kardex
            // Y luego, hacer update a inv_productos y actualizar el CostoPromedio de esa tabla al nuevo que se calculo
            // NOTA: Aplicar esta formula y update en compras locales donde no se hace retaceo

            $existenciaDespues = $productosExis['existenciaProducto'] + $detalle['cantidadProducto'];

            if($productosExis['existenciaProducto'] <= 0){

                $costoPromedio = $detalle['costoUnitarioRetaceo'];
            
            }else{
                $costoTotalExistente = $productosExis['existenciaProducto'] * $detalle['CostoPromedio'];

                $costoTotalNuevas = $detalle['cantidadProducto'] * $detalle['costoUnitarioRetaceo'];

                $cantidadTotal = $productosExis['existenciaProducto'] + $detalle['costoUnitarioRetaceo'];

                $costoPromedio = ($costoTotalExistente + $costoTotalNuevas) / $cantidadTotal;
            }

            $data = [
                "tipoMovimiento"                => "Entrada",
                "descripcionMovimiento"         => "Entrada registrada desde el retaceo",
                "productoExistenciaId"          => $productosExis['productoExistenciaId'],
                "existenciaAntesMovimiento"     => $productosExis['existenciaProducto'],
                "cantidadMovimiento"            => $detalle['cantidadProducto'],
                "existenciaDespuesMovimiento"   => $existenciaDespues,
                "costoUnitarioFOB"              => $detalle['precioUnitario'],
                "costoUnitarioRetaceo"          => $detalle['costoUnitarioRetaceo'],
                "costoPromedio"                 => $costoPromedio,
                "precioVentaUnitario"           => $detalle['precioVenta'],
                "fechaDocumento"                => $detalle['fechaDocumento'],
                "fechaMovimiento"               => date("Y-m-d H:i:s"),
                "tablaMovimiento"               => "comp_retaceo_detalle",
                "tablaMovimientoId"             => $detalle['retaceoDetalleId'],
    
            ];
            // Insertar datos en la base de datos
            $finRetaceo = $inv_kardex->insert($data);

            $data = [
                'existenciaProducto'   => $existenciaDespues

            ];
            // Insertar datos en la base de datos
            $operacionExistencia = $inv_productos_existencias->update($productosExis['productoExistenciaId'], $data);
    
            $dataCostoPromedio = [
                "CostoPromedio"  => $costoPromedio
                ];
    
                // Insertar datos en la base de datos
                $invProductos->update($productoId,$dataCostoPromedio);
        }


            $data = [
                'estadoRetaceo'     => "Finalizado",
                'obsRetaceo'        => $observacionFinalizarRetaceo
                
            ];
            // Insertar datos en la base de datos
            $operacionEstadoRetaceo = $comp_retaceo->update($retaceoId, $data);


        if ($operacionEstadoRetaceo) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Se agrego al kardex desde el retaceo correctamente',
                'retaceoId' =>  $retaceoId 
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar al kardex'
            ]);
        }

    }
    public function vistaVerRetaceo(){
        $session = session();

        $comp_retaceo = new comp_retaceo();

        $retaceoId = $this->request->getPost('retaceoId');

        $consultaRetaceo = $comp_retaceo
                ->select("numRetaceo,fechaRetaceo,totalFlete,totalGastos")
                ->where("flgElimina", 0)
                ->where("retaceoId", $retaceoId)
                ->first();   

        $data['camposEncabezado'] = [
            'numRetaceo'         => $consultaRetaceo['numRetaceo'],
            'fechaRetaceo'       => $consultaRetaceo['fechaRetaceo'],
            'totalFlete'         => $consultaRetaceo['totalFlete'],
            'totalGastos'        => $consultaRetaceo['totalGastos']
            // Sacar estos valores de la consulta
        ];

        $data['retaceoId'] = $retaceoId;

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No',
            'retaceoId'   => $retaceoId
        ];
        $session->set([
            'route'             => 'compras/admin-retaceo/vista/ver/retaceo',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('compras/vistas/pageVerRetaceo', $data);
    }
    public function tablaVerRetaceo(){
        $compRetaceoDetalle = new comp_retaceo_detalle();
        $output['data'] = array();
        $n = 0;

        $retaceoId = $this->request->getPost('retaceoId');
        $numDocumento = $this->request->getPost('numDocumento');

        $consultaRetaceoDetalle = $compRetaceoDetalle
            ->select("comp_retaceo_detalle.retaceoDetalleId,comp_retaceo_detalle.retaceoId,comp_retaceo_detalle.compraDetalleId,comp_retaceo_detalle.cantidadProducto,comp_retaceo_detalle.precioFOBUnitario,comp_retaceo_detalle.importe,comp_retaceo_detalle.flete,comp_retaceo_detalle.gasto,comp_retaceo_detalle.DAI,comp_retaceo_detalle.costoUnitarioRetaceo,comp_retaceo_detalle.costoTotal,inv_productos.codigoProducto,inv_productos.producto,inv_productos.precioVenta")
            ->join("comp_compras_detalle", "comp_compras_detalle.compraDetalleId = comp_retaceo_detalle.compraDetalleId")
            ->join("inv_productos", "inv_productos.productoId = comp_compras_detalle.productoId")
            ->where("comp_retaceo_detalle.flgElimina", 0)
            ->where("comp_retaceo_detalle.retaceoId", $retaceoId)
            ->findAll();

        $totalCantidad = 0;
        $totalPrecioUnitarioFOB = 0;
        $totalFlete = 0;
        $totalGastos = 0;
        $totalCosto = 0;

        foreach($consultaRetaceoDetalle AS $tableRetaceoDetalle){
            $n++;
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "(".$tableRetaceoDetalle['codigoProducto'].")". $tableRetaceoDetalle['producto'];

            $columna3 = number_format($tableRetaceoDetalle['cantidadProducto'], 0, ".", ",");

            $columna4 = "$ " . number_format($tableRetaceoDetalle['precioFOBUnitario'], 2, ".", ",");

            $columna5 = "$ " . number_format($tableRetaceoDetalle['importe'], 2, ".", ",");

            $columna6 = "$ " . number_format($tableRetaceoDetalle['flete'], 2, ".", ",");

            $columna7 = "$ " . number_format($tableRetaceoDetalle['gasto'], 2, ".", ",");

            $columna8 = "$ " . number_format($tableRetaceoDetalle['DAI'], 2, ".", ",");

            $columna9 = "$ " . number_format($tableRetaceoDetalle['costoUnitarioRetaceo'], 2, ".", ",");

            $columna10 = "$ " . number_format($tableRetaceoDetalle['costoTotal'], 2, ".", ",");

            $columna11 = "$ " . number_format($tableRetaceoDetalle['precioVenta'], 2,".", ",");


            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
                $columna6,
                $columna7,
                $columna8,
                $columna9,
                $columna10,
                $columna11
            );

            $totalCantidad += $tableRetaceoDetalle['cantidadProducto'];
            $totalPrecioUnitarioFOB += $tableRetaceoDetalle['precioFOBUnitario'];
            $totalFlete += $tableRetaceoDetalle['flete'];
            $totalGastos += $tableRetaceoDetalle['gasto'];
            $totalCosto += $tableRetaceoDetalle['costoTotal'];
        } 


        // Verifica si hay datos
        if ($n > 0) {
                $output['footer'] = array(
                    '<div class="text-left"><b>Total</b></div>',
                    '<div class="text-left"><b>'.number_format($totalCantidad, 0, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalPrecioUnitarioFOB, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalFlete, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalGastos, 2, ".", ",").'</b></div>',
                    '<div class="text-left"><b>$ '.number_format($totalCosto, 2, ".", ",").'</b></div>'
                 );
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }
    }
}
