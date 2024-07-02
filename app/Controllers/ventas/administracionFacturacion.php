<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionFacturacion extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/facturacion', $data);
    }

    public function tablaFacturacion(){
        $output['data'] = array();
            $n = 0;

            $n++;
            // Aquí construye tus columnas

            
            $v = 2;
           switch($v){
            case 1:
                $columna1 = $n;

                $columna2 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Núm. Control:</b> " . "<br>" . "<b>Cód. Generación:</b>" . "<br>" . "<b>Cliente:</b> ";
    
                $columna3 = "<b>fecha y hora de emisión: </b> 25/06/2024";
    
                $columna4 = "<b>Pendiente </b>";
    
                $columna5 = "<b>Total del DTE: </b>";

            $columna6 = '
                <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-facturacion/vista/continuar/dte`, {renderVista: `No`});" data-toggle="tooltip" data-placement="top" title="Continuar DTE">
                    <i class="fas fa-sync-alt"></i>
                </button>';
            $columna6 .= '
                <button type= "button" class="btn btn-danger mb-1" onclick="modalAnularDTE()" data-toggle="tooltip" data-placement="top" title="Anular DTE">
                    <i class="fas fa-ban"></i>
                </button>';
            break;

            case 2:
                $columna1 = $n;

                $columna2 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Núm. Control:</b> DTE-03-12345678-000000000000001
" . "<br>" . "<b>Cód. Generación:</b> C6A9868C-028D-421B-A9A0-36274CECC2C7
" . "<br>" . "<b>Cliente: Cliente prueba</b> ";
    
                $columna3 = "<b>fecha y hora de emisión: </b> 25/06/2024";
    
                $columna4 = "<b>Certificado </b>";
    
                $columna5 = "<b>Total del DTE: </b> $120.00";

            $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="ver DTE">
                            <i class="fas fa-eye"></i>
                        </button>';
            
            $columna6 .= '
                            <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Complementos">
                                <i class="fas fa-clipboard-list"></i>
                            </button>';
            $columna6 .= '
                            <button type= "button" class="btn btn-primary mb-1" onclick="modalImprimirDTE()" data-toggle="tooltip" data-placement="top" title="Imprimir DTE">
                                <i class="fas fa-print"></i>
                            </button>';
            $columna6 .= '
                            <button type= "button" class="btn btn-primary mb-1" onclick="window.location.href=`https://admin.factura.gob.sv/consultaPublica`" data-toggle="tooltip" data-placement="top" title="Consultar DTE">
                                <i class="fas fa-file-alt"></i>
                            </button>';
                            
            $columna6 .= '
                            <button type= "button" class="btn btn-danger mb-1" onclick="modalAnularDTE()" data-toggle="tooltip" data-placement="top" title="Anular DTE">
                                <i class="fas fa-ban"></i>
                            </button>';
             break;
    
            case 3:
                $columna1 = $n;

                $columna2 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Núm. Control:</b> DTE-03-12345678-000000000000001
" . "<br>" . "<b>Cód. Generación:</b> C6A9868C-028D-421B-A9A0-36274CECC2C7
" . "<br>" . "<b>Cliente: </b> Cliente prueba";
    
                $columna3 = "<b>fecha y hora de emisión: </b> 25/06/2024";
    
                $columna4 = "<b>Anulado </b>";
    
                $columna5 = "<b>Total del DTE: </b> $120.00";

                $columna6 = '
                                <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="ver DTE">
                                    <i class="fas fa-eye"></i>
                                </button>';
                $columna6 .= '
                                <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Complementos">
                                    <i class="fas fa-clipboard-list"></i>
                                </button>';
                $columna6 .= '
                                <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Consultar DTE">
                                    <i class="fas fa-file-alt"></i>
                                </button>';
            break;
           }
                            
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
                $columna6
            );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalEmitirDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalEmitirDTE', $data);
    }

    public function modalAnularDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalAnularDTE', $data);
    }

    public function vistaContinuarDTE(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-facturacion/pageContinuarDTE',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/pageContinuarDTE', $data);
    }

    public function tablaContinuarDTE(){
        $output['data'] = array();
            $n = 0;

            $n++;
            $output['data'] = array();

            // Aquí construye tus columnas
            $columna1 = $n;

            $columna2 = "(PD-001) Mario Kart";

            $columna3 = "$ 53.10";

            $columna4 = "2";

            $columna5 = "$ 13.80";
           
            $columna6 = "$ 120.00";
           
            $columna7 = '
                            <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="fas fa-sync-alt"></i>
                            </button>';
            $columna7 .= '
                            <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>';
                            
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5,
                $columna6,
                $columna7
            );

            if ($n > 0) {
                $output['footer'] = array(
                    '',
                    '' ,
                    ''
                );

                $output['footerTotales'] = '
                    <b>
                        <div class="row text-right">
                            <div class="col-8">
                                Sub total:
                            </div>
                            <div class="col-4">
                                $ 106.2
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                IVA 13%:
                            </div>
                            <div class="col-4">
                                $ 13.80
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total a pagar:
                            </div>
                            <div class="col-4">
                                $ 120.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total pagado:
                            </div>
                            <div class="col-4">
                                $ 120.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-12">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalPagoDTE()" data-toggle="tooltip" data-placement="top" title="Pagos">
                                    <i class="fas fa-hand-holding-usd"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-4">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalComplementoDTE()" data-toggle="tooltip" data-placement="top" title="Complementos">
                                    <i class="fas fa-clipboard-list"></i> Complementos
                                </button>
                            </div>
                            <div class="col-4">
                                <button type= "button" class="btn btn-danger mb-1" onclick="modalErrorDTE()" data-toggle="tooltip" data-placement="top" title="Error de certificación">
                                    <i class="fas fa-ban"></i> Errores de certificación
                                </button>
                            </div>
                            <div class="col-4">
                                <button type= "button" class="btn btn-primary mb-1" onclick="CertificarDTE()" data-toggle="tooltip" data-placement="top" title="Certificar DTE">
                                    <i class="fas fa-save"></i> Certificar DTE
                                </button>
                            </div>
                        </div>
                    </b>

                ';
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }     
    }
    public function modalPagoDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalPagoDTE', $data);
    }

    public function tablaPagoDTE(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "<b>Forma de pago: </b> Billetes y monedas" . "<br>" . "<b>Descripción/Comprobanto: </b> Cancelado en efectivo";

        $columna3 = "$ 120.00";
        
        $columna4 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>';
                        
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
    public function modalComplementoDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalComplementoDTE', $data);
    }

    public function tablaComplementoDTE(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "Edición especial";
        
        $columna3 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>';
                        
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

    public function tablaErrorDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalErrorDTE', $data);
    }

    public function imprimirDTE(){
        $data['variable'] = 0;
        return view('ventas/modals/modalImprimirDTE', $data);
    }
}
