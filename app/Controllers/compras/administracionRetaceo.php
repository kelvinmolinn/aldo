<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;
use App\Models\comp_retaceo;

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

        $consultaRetaceo = $comp_retaceo
                ->select('retaceoId,numRetaceo,totalFlete,totalGastos,estadoRetaceo')
                ->where('flgElimina', 0)
                ->findAll();


        $output['data'] = array();
        $n = 0;
        foreach($consultaRetaceo as $consultaRetaceo){
            $n++;
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>N° de retaceo: </b> ".$consultaRetaceo['numRetaceo'] . "<br>" . "<b>Factura(s): </b> " . "<br>" . "<b>Estado: </b> ".$consultaRetaceo ['estadoRetaceo'] . "<br>" . "<b>Total de productos: </b> ";
    
            $columna3 = "<b>Flete: </b> ".$consultaRetaceo['totalFlete'] . "<br>" . "<b>Gastos: </b> ".$consultaRetaceo['totalGastos'] . "<br>" . "<b>Costo total: </b> ";
    
            $columna4 = '
                            <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-retaceo/vista/continuar/retaceo`);" data-toggle="tooltip" data-placement="top" title="Continuar retaceo">
                                <i class="fas fa-sync-alt"></i>
                            </button>';
        
    
            $columna4 .= '
                             <button type= "button" class="btn btn-danger mb-1" onclick="modalAnularRetaceo('.$consultaRetaceo["retaceoId"].')" data-toggle="tooltip" data-placement="top" title="Anular">
                                <i class="fas fa-ban"></i>
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
            "obsRetaceo"       => $this->request->getPost('observacionRetaceo'),
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

        $retaceoId = $this->request->getPost('retaceoId');

        $data['campos'] = $comp_retaceo
        ->select('retaceoId,numRetaceo,totalFlete,totalGastos,estadoRetaceo')
        ->where('flgElimina', 0)
        ->where('retaceoId', $retaceoId)
        ->first();
        $data['variable'] = 0;
        return view('compras/modals/modalAnularRetaceo', $data);
    }

    public function operacionAnularRetaceo(){
        $anularRetaceo = new comp_retaceo();
        
            $retaceoId = $this->request->getPost('retaceoId');
            $observacionAnulacion = $this->request->getPost('observacionAnulacion');

            $data = [
                'flgElimina'    => 1,
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

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'compras/admin-retaceo/vista/continuar/retaceo',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('compras/vistas/pageContinuarRetaceo', $data);
    }

    public function tablaContinuarRetaceo(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "(PD-001) MARIO KART" . "<br>" . "(PD-002) MANDO GENERICO";

        $columna3 = "2" . "<br><br>" . "33";

        $columna4 = "$ 25.00" . "<br><br>" . "$ 25.50";

        $columna5 = "$ 50.00" . "<br><br>" . "$ 841.50";

        $columna6 = "$ 0.5350" . "<br><br>" . "$ 9.0040";

        $columna7 = "$ 0.4458" . "<br><br>" . "$ 7.5033";

        $columna8 = "10". "<br><br>" . "0";

        $columna9 = "$ 30.46" . "<br><br>" . "$ 26.00";

        $columna10 = "$ 60.98" . "<br><br>" . "$ 858.01";

        $columna11 = "$ 35.00" . "<br><br>" . "$ 32.00";

        $columna12 = '  
                        <button class="btn btn-primary mb-1" onclick="modalAgregarDAI()" data-toggle="tooltip" data-placement="top" title="DAI">
                            <i class="fas fa-address-book"></i> DAI
                        </button>' . '<br>' . '<button class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="DAI">
                            <i class="fas fa-address-book"></i> DAI
                        </button>';

        /*$columna4 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-retaceo/vista/continuar/retaceo`);" data-toggle="tooltip" data-placement="top" title="Continuar retaceo">
                            <i class="fas fa-sync-alt"></i>
                        </button>';

        $columna4 .= '
                         <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Anular">
                            <i class="fas fa-ban"></i>
                        </button>
                    ';*/

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

        // Verifica si hay datos
        if ($n > 0) {
                $output['footer'] = array(
                    '<div class="text-right"><b>Total</b></div>'
                 );
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalnuevaCompraRetaceo(){
        $data['variable'] = 0;
    return view('compras/modals/modalAgregarCompraRetaceo', $data);
    }

    public function modalAgregarDAI(){
        $data['variable'] = 0;
        return view('compras/modals/modalAgregarDAI', $data);
    }
}
