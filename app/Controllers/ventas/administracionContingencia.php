<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;

use App\Models\fel_factura_contingencia;

class administracionContingencia extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function indexContingencia(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-contingencia/contingencia',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('ventas/vistas/contingencia', $data);
    }

    public function tablaContingencias(){
        $felFacturaContingencia = new fel_factura_contingencia();

        $mostrarTablaContingencia = $felFacturaContingencia 
                            ->select('DATE_FORMAT(fechaInicio, "%d/%m/%Y") AS fechaInicio,horaInicio,DATE_FORMAT(fechaFin, "%d/%m/%Y") AS fechaFin,horaFin,tipoContingenciaId,motivoContingencia')
                            ->where('flgElimina',0)
                            ->findAll();
        $n = 1;
        foreach ($mostrarTablaContingencia as $columna) {
            $n++;

            $columna1 = "<b>Fecha inicio:</b> " . $columna['fechaInicio'] . "<br><b>Hora inicio:</b> " . $columna['horaInicio'];

            $columna2 = "<b>Fecha fin:</b> " . $columna['fechaFin'] . "<br><b>Hora fin:</b> " . $columna['horaFin'];

            $columna3 = "<b>Tipo contingencia:</b> " . $columna['tipoContingenciaId'] . "<br><b>Motivo:</b> " . $columna['motivoContingencia'];
            
            $jsonDatosContingencia = [
                "fechaInicio"           => $columna['fechaInicio'],
                "horaInicio"            => $columna['horaInicio'],
                "fechaFin"              => $columna['fechaFin'],
                "horaFin"               => $columna['horaFin'],
                "tipoContingenciaId"    => $columna['tipoContingenciaId'],
                "motivoContingencia"    => $columna['motivoContingencia']
            ];

            $columna4 = '
                    <button class="btn btn-primary mb-1" onclick="modalVerDTEContingencia('.htmlspecialchars(json_encode($jsonDatosContingencia)).')" data-toggle="tooltip" data-placement="top" title="Ver DTE contingencia">
                        <i class="fas fa-eye"></i><span> </span>
                    </button>';

            $columna4 .= '
                    <button class="btn btn-info mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Ver json contingencia">
                        <i class="fas fa-file-code"></i><span> </span>
                    </button>';

        }

        $output['data'][] = array(
            $n,
            $columna1,
            $columna2,
            $columna3,
            $columna4
        );

        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalDTEContingencia(){
        $data['variable'] = 0;

        return view('ventas/modals/modalContingenciaDTE', $data);
    }

}
