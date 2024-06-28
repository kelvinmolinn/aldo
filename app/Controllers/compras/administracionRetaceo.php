<?php

namespace App\Controllers\compras;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

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
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>N° de retaceo: </b>" . "<br>" . "<b>Factura(s):</b> " . "<br>" . "<b>Estado:</b>" . "<br>" . "<b>Total de productos:</b> ";

        $columna3 = "<b>Flete: </b>" . "<br>" . "<b>Gastos:</b> " . "<br>" . "<b>Importe directo:</b>" . "<br>" . "<b>Costo total:</b> ";

        $columna4 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="cambiarInterfaz(`compras/admin-retaceo/vista/continuar/retaceo`);" data-toggle="tooltip" data-placement="top" title="Continuar retaceo">
                            <i class="fas fa-sync-alt"></i>
                        </button>';

        $columna4 .= '
                         <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Anular">
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

    public function modalNuevoRetaceo(){
        
        $data['variable'] = 0;
    return view('compras/modals/modalNuevoRetaceo', $data);
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
}
