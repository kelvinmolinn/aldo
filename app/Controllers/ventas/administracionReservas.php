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

        $columna2 = "<b>Sucursal: </b> " . "<br>" . "<b>Num. Reserva:</b> " . "<br>" . "<b>Cliente:</b> " . "<br>" . "<b>Comentario:</b> ";

        $columna3 = "<b>fecha de la reserva: </b>";

        $columna4 = "<b>Reservado </b>";

        $columna5 = "<b>Total de la reserva: </b>" . "<br>" . "<b>Total pagado:</b> ";
        
        $columna6 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`ventas/admin-reservas/vista/continuar/reserva`);" data-toggle="tooltip" data-placement="top" title="Continuar reserva">
                            <i class="fas fa-sync-alt"></i>
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
        $n = 0;

        $n++;
        $session = session();
        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-reservas/vista/continuar/reserva',
            'camposSession'     => json_encode($camposSession)
        ]);
        $data['variable'] = 0;
        return view('ventas/vistas/pageContinuarReserva', $data);


        if ($n > 0) {
            $output['footer'] = array(
                '<div class="text-right"><b>Total</b></div>'
             );
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '', 'footer'=>'')); // No hay datos, devuelve un array vacío
        }

        $output['footerTotales'] = '
        <b>
        <div class="row text-right">
            <div class="col-8">
                Subtotal
            </div>
            <div class="col-4">
                $ 
            </div>
        </div>
        <div class="row text-right">
            <div class="col-8">
                IVA 13%
            </div>
            <div class="col-4">
                $ 
            </div>
        </div>
        <div class="row text-right">
            <div class="col-8">
                (+) IVA Percibido
            </div>
            <div class="col-4">
                $ 
            </div>
        </div>
        <div class="row text-right">
            <div class="col-8">
                Total a pagar
            </div>
            <div class="col-4">
                $ 
            </div>
        </div>                    
        </b>
    ';
    }
}
