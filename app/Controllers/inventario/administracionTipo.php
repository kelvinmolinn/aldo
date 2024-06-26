<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos;
class AdministracionTipo extends Controller
{

    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'inventario/admin-tipo/index',
                'camposSession'     => json_encode($camposSession)
            ]);

            return view('inventario/vistas/administracionTipo', $data);
        }
    }

    public function modalAdministracionTipo()
    {

        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $productoTipoId = $this->request->getPost('productoTipoId');
            $productoTipo = new inv_productos_tipo();
            $data['campos'] = $productoTipo->select('productoTipoId,productoTipo')->where('flgElimina', 0)->where('productoTipoId', $productoTipoId)->first();
        } else {
            $data['campos'] = [
                'productoTipoId'      => 0,
                'productoTipo'        => ''
            ];
        }
        $data['operacion'] = $operacion;

        return view('inventario/modals/modalAdministracionTipo', $data);
    }

    public function eliminarTipo()
    {
        $productoTipoId = $this->request->getPost('productoTipoId');
        
        // Verificar si el tipo de producto está asignado a algún producto
        $productosModel = new Inv_Productos();
        $tipoAsignado = $productosModel->where('productoTipoId', $productoTipoId)->first();

        if ($tipoAsignado) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El tipo de producto no se puede eliminar porque ya está asignado a un producto'
            ]);
        }

        // Proceder con la eliminación si no está asignado
        $eliminarTipo = new Inv_Productos_Tipo();
        $data = ['flgElimina' => 1];
        $resultado = $eliminarTipo->update($productoTipoId, $data);

        if ($resultado) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Tipo de producto eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el tipo de producto'
            ]);
        }
    }

    public function tablaTipo()
    {
        $mostrarTipo = new inv_productos_tipo();
        $datos = $mostrarTipo
        ->select('productoTipoId, productoTipo')
        ->where('flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Tipo de producto:</b> " . $columna['productoTipo'];

            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalTipo(`'.$columna['productoTipoId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar tipo de producto">
                    <span></span>
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
    

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarTipo(`'.$columna['productoTipoId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function modalTipoOperacion()
    {
    
        // Continuar con la operación de inserción o actualización en la base de datos
        $operacion = $this->request->getPost('operacion');
        $model = new inv_productos_tipo();
        $productoTipo = $this->request->getPost('productoTipo');
        $productoTipoId = $this->request->getPost('productoTipoId');

        if ($model->existeTipo($productoTipo, $productoTipoId)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El tipo de producto ya está registrado'
            ]);
        } else {
    
            $data = [
                'productoTipo' => $this->request->getPost('productoTipo')
            ];
        
            if ($operacion == 'editar') {
                $operacionTipo = $model->update($this->request->getPost('productoTipoId'), $data);
            } else {
                // Insertar datos en la base de datos
                $operacionTipo = $model->insert($data);
            }
        
            if ($operacionTipo) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Tipo de producto ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                    'productoTipoId' => ($operacion == 'editar' ? $this->request->getPost('productoTipoId') : $model->insertID())
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar el tipo de producto'
                ]);
            }
     }
    }
        
}