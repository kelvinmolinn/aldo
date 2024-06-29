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
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-compras/vista/actualizar/compra`);" data-toggle="tooltip" data-placement="top" title="Continuar reserva">
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
}
