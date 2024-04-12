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

        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $unidadMedidaId = $this->request->getPost('unidadMedidaId');
            $unidadMedida = new cat_unidades_medida();
            $data['campos'] = $unidadMedida->select('unidadMedidaId,unidadMedida,abreviaturaUnidadMedida')->where('flgElimina', 0)->where('unidadMedidaId', $unidadMedidaId)->first();
        } else {
            $data['campos'] = [
                'unidadMedidaId'      => 0,
                'unidadMedida'        => '',
                'abreviaturaUnidadMedida'   => ''
            ];
        }
        $data['operacion'] = $operacion;

        return view('inventario/modals/modalAdministracionUnidades', $data);
    }
    
    public function eliminarUnidades(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $eliminarUnidades = new cat_unidades_medida();
        
        $unidadMedidaId = $this->request->getPost('unidadMedidaId');
        $data = ['flgElimina' => 1];
        
        $eliminarUnidades->update($unidadMedidaId, $data);

        if($eliminarUnidades) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Unidad de medida eliminada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar la unidad de medida'
            ]);
        }
    }

    public function modalUnidadesOperacion()
    {
        // Establecer reglas de validación
        $validation = service('validation');
        $validation->setRules([
            'unidadMedida' => 'required|is_unique[cat_unidades_medida.unidadMedida]',
            'abreviaturaUnidadMedida' => 'required|is_unique[cat_unidades_medida.abreviaturaUnidadMedida]'
        ]);
    
        // Ejecutar la validación
        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, devolver los errores al cliente
            return $this->response->setJSON([
                'success' => false,
                'errors' => $validation->getErrors()
            ]);
        }
    
        // Continuar con la operación de inserción o actualización en la base de datos
        $operacion = $this->request->getPost('operacion');
        $model = new cat_unidades_medida();
    
        $data = [
            'unidadMedida' => $this->request->getPost('unidadMedida'),
            'abreviaturaUnidadMedida' => $this->request->getPost('abreviaturaUnidadMedida')
        ];
    
        if ($operacion == 'editar') {
            $operacionUnidad = $model->update($this->request->getPost('unidadMedidaId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionUnidad = $model->insert($data);
        }
    
        if ($operacionUnidad) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'UDM ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                'unidadMedidaId' => ($operacion == 'editar' ? $this->request->getPost('unidadMedidaId') : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar la UDM'
            ]);
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
            $columna2 = "<b>Unidad de medida:</b> " . $columna['unidadMedida'] . ' (' . $columna['abreviaturaUnidadMedida'] . ')';

            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalUnidades(`'.$columna['unidadMedidaId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar UDM">
                    <span></span>
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
    

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarUnidades(`'.$columna['unidadMedidaId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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