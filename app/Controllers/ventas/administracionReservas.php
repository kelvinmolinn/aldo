<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionReservas extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-reservas/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/reservas', $data);
    }

    public function tablaReservas(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Num. Reserva:</b> 1" . "<br>" . "<b>Cliente:</b> Cliente prueba" . "<br>" . "<b>Comentario:</b> Reserva de juego de mario kart";

        $columna3 = "<b>fecha de la reserva: </b> 29/06/2024";

        $columna4 = "<b>Facturado </b>";

        $columna5 = "<b>Total de la reserva: </b> $ 35.00" . "<br>" . "<b>Total pagado:</b> $ 35.00";
        
        $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-reservas/vista/continuar/reserva`, {renderVista: `No`});" data-toggle="tooltip" data-placement="top" title="Continuar reserva">
                            <i class="fas fa-eye"></i>
                        </button>';
                        
        $columna6 .= '
                        <button type= "button" class="btn btn-danger mb-1" onclick="modalAnularReserva()" data-toggle="tooltip" data-placement="top" title="Anular reserva">
                            <i class="fas fa-ban"></i>
                        </button>';
                        
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
    
    public function modalNuevaReserva(){
        
        $data['variable'] = 0;
        return view('ventas/modals/modalNuevaReserva', $data);
    }

    public function modalAnularReserva(){
        $data['variable'] = 0;
        return view('ventas/modals/modalAnularReserva', $data);
    }

    public function vistaContinuarReserva(){

        $session = session();
        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-reservas/vista/continuar/reserva',
            'camposSession'     => json_encode($camposSession)
        ]);
        $data['reservaId'] = 0;
        return view('ventas/vistas/pageContinuarReserva', $data);       
        
    }

    public function tablaContinuarReserva(){
        $n = 0;
        $n++;
        $output['data'] = array();

        $columna1 = $n;
        $columna2 = '(PD-001) MARIO KART';
        $columna3 = '$ 35.00' ;
        $columna4 = '1';
        $columna5 = '$ 35.00 ';
        $columna6 = '
            <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                <i class="fas fa-pencil-alt"></i>
            </button>
        ';

        $columna6 .= '
            <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                                Total a pagar:
                            </div>
                            <div class="col-4">
                                $ 35.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-8">
                                Total pagado:
                            </div>
                            <div class="col-4">
                                $ 15.00
                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="col-12">
                                <button type= "button" class="btn btn-primary mb-1" onclick="modalPagoReserva()" data-toggle="tooltip" data-placement="top" title="Pagos">
                                    <i class="fas fa-hand-holding-usd"></i>
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

    public function modalPagoReserva(){
        $data['variable'] = 0;
        return view('ventas/modals/modalPagosReservas', $data);
    }

    public function tablePagoReserva(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;

        $columna2 = "<b>Forma pago: </b> Efectivo" . "<br>" . "<b>Comprobante:</b> 1" . "<br>" . "<b>Comentarios:</b> Abonó 15 dolares ";

        $columna3 = "24/06/2024";

        $columna4 = "$ 15.00";
        
        $columna5 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Eliminar">
                            <i class="fas fa-trash"></i>
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

    public function modalNuevoProductoReserva(){
        $data['variable'] = 0;
        return view('ventas/modals/modalProductoReserva', $data);
    }
}
