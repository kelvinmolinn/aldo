<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos_plataforma;
class AdministracionPlataforma extends Controller
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
                'route'             => 'inventario/admin-plataforma/index',
                'camposSession'     => json_encode($camposSession)
            ]);

         return view('inventario/vistas/administracionPlataforma', $data);
        }
    }

    public function modalAdministracionPlataforma()
    {

        $operacion = $this->request->getPost('operacion');
        if($operacion == 'editar') {
            $productoPlataformaId = $this->request->getPost('productoPlataformaId');
            $productoPlataforma = new inv_productos_plataforma();
            $data['campos'] = $productoPlataforma->select('productoPlataformaId,productoPlataforma')->where('flgElimina', 0)->where('productoPlataformaId', $productoPlataformaId)->first();
        } else {
            $data['campos'] = [
                'productoPlataformaId'      => 0,
                'productoPlataforma'        => ''
            ];
        }
        $data['operacion'] = $operacion;

        return view('inventario/modals/modalAdministracionPlataforma', $data);
    }

    public function eliminarPlataforma(){
    
        $eliminarPlataforma = new inv_productos_plataforma();
        
        $productoPlataformaId = $this->request->getPost('productoPlataformaId');
        $data = ['flgElimina' => 1];
        
        $eliminarPlataforma->update($productoPlataformaId, $data);

        if($eliminarPlataforma) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Plataforma de producto eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar La plataforma de producto'
            ]);
        }
    }

    public function tablaPlataforma()
    {
        $mostrarPlataforma = new inv_productos_plataforma();
        $datos = $mostrarPlataforma
        ->select('productoPlataformaId, productoPlataforma')
        ->where('flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Plataforma de producto:</b> " . $columna['productoPlataforma'];

            // Aquí puedes construir tus botones en la última columna
            $columna3 = '
                <button class="btn btn-primary mb-1" onclick="modalPlataforma(`'.$columna['productoPlataformaId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar Plataforma de producto">
                    <span></span>
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
    

            $columna3 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarPlataforma(`'.$columna['productoPlataformaId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    public function modalPlataformaOperacion()
    {


            // Continuar con la operación de inserción o actualización en la base de datos
            $operacion = $this->request->getPost('operacion');
            $model = new inv_productos_plataforma();
            $productoPlataforma = $this->request->getPost('productoPlataforma');
            $productoPlataformaId = $this->request->getPost('productoPlataformaId');

        if ($model->existePlataforma($productoPlataforma, $productoPlataformaId)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'La plataforma ya está registrada en la base de datos'
            ]);
        } else {
        
            $data = [
                'productoPlataforma' => $this->request->getPost('productoPlataforma')
            ];
        
            if ($operacion == 'editar') {
                $operacionPlataforma = $model->update($this->request->getPost('productoPlataformaId'), $data);
            } else {
                // Insertar datos en la base de datos
                $operacionPlataforma = $model->insert($data);
            }
        
            if ($operacionPlataforma) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Plataforma de producto ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                    'productoPlataformaId' => ($operacion == 'editar' ? $this->request->getPost('productoPlataformaId') : $model->insertID())
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar la plataforma de producto'
                ]);
            }
        }
    }
        
}