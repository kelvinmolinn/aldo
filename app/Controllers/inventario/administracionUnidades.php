<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\cat_unidades_medida;
class AdministracionUnidades extends Controller
{
    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;
            // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
            return view('inventario/vistas/administracionUnidades', $data);
        }
    }

    public function modalAdministracionUnidades()
    {
        // Cargar el modelo de unidades de medida
        $unidadesModel = new cat_unidades_medida();

        $data['unidades'] = $unidadesModel->where('flgElimina', 0)->findAll();
        $data['operacion'] = $this->request->getPost('operacion');
        $data['unidadMedidaId'] = $this->request->getPost('unidadMedidaId');

        if($data['operacion'] == 'editar') {
            $mostrarUnidad = new cat_unidades_medida();

            // seleccionar solo los campos que estan en la modal (solo los input)
            $data['campos'] = $mostrarEmpleado
            ->select('cat_unidades_medida.unidadMedida,cat_unidades_medida.abreviaturaUnidadMedida')
            ->where('cat_unidades_medida.flgElimina', 0)
            ->where('cat_unidades_medida.unidadMedidaId', $data['unidadMedidaId'])
            ->first();
        } else {
            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'unidadMedida'               => '',
                'abreviaturaUnidadMedida'    => ''

            ];
        }
        return view('inventario/modals/modalAdministracionUnidades', $data);
    }

    public function insertarNuevaUnidad()
    {
        $modelUnidad = new cat_unidades_medida();
    
        $unidadMedida = $this->request->getPost('unidadMedida');
        $operacion = $this->request->getPost('operacion');
        $unidadMedidaId = $this->request->getPost('unidadMedidaId');
    
        if ($modelUnidad->UnidadExiste($unidadMedida, $unidadMedidaId )) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'La unidad de medida ya está registrada en la base de datos'
            ]);
        } else {
            $data = [
                'unidadMedida' => $this->request->getPost('unidadMedida'),
                'abreviaturaUnidadMedida' => $this->request->getPost('abreviaturaUnidadMedida')
            ];
    
            // Insertar datos en la base de datos
            if ($operacion == 'editar') {
                $insertUnidad = $modelUnidad->update($unidadMedidaId, $data);
            } else {
                $insertUnidad = $modelUnidad->insert($data);
                // sobreescribir el cero que viene como unidadMedidaId
                $unidadMedidaId = $modelUnidad->insertID();
            }
    
            if ($insertUnidad) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Unidad de medida ' . ($operacion == 'editar' ? 'actualizada' : 'agregada') . ' correctamente',
                    'unidadMedidaId' => $unidadMedidaId
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar la unidad de medida'
                ]);
            }
        }
    }
    
    public function tablaUnidades()
    {
        $mostrarUnidades = new cat_unidades_medida();
        $datos = $mostrarUnidades
        ->select('unidadMedidaId, unidadMedida, abreviaturaUnidadMedida')
        ->where('flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Unidad de medida: </b>" . $columna['unidadMedida'];
            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalModulo(`'.$columna['unidadMedidaId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar modulo">
                    <span></span>
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
    

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarModulo(`'.$columna['unidadMedidaId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            ';

            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3
            );
    
            $n++;
        }
    
        // Verifica si hay datos
        if ($n > 1) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }
    
}