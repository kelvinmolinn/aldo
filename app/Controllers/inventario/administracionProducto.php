<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos;
use App\Models\cat_unidades_medida;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos_plataforma;

class AdministracionProducto extends Controller
{

    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;
            // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
            return view('inventario/vistas/administracionProducto', $data);
        }
    }

    public function modalAdministracionProducto()
{ 

           // Cargar el modelos
        $tipoModel = new inv_productos_tipo();
        $plataformaModel = new inv_productos_plataforma();
        $unidadModel = new cat_unidades_medida();

        $data['tipo'] = $tipoModel->where('flgElimina', 0)->findAll();
        $data['plataforma'] = $plataformaModel->where('flgElimina', 0)->findAll();
        $data['unidad'] = $unidadModel->where('flgElimina', 0)->findAll();
        $operacion = $this->request->getPost('operacion');
        $data['productoTipoId'] = $this->request->getPost('productoTipoId');
        $data['productoPlataformaId'] = $this->request->getPost('productoPlataformaId');
        $data['unidadMedidaId'] = $this->request->getPost('unidadMedidaId');

        if($operacion == 'editar') {
            $productoId = $this->request->getPost('productoId');
            $producto = new inv_productos();

             // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $producto->select('inv_productos.productoId,inv_productos.codigoProducto,inv_productos.producto,inv_productos.descripcionProducto,inv_productos.existenciaMinima,inv_productos_plataforma.productoPlataformaId,inv_productos_tipo.productoTipoId,cat_unidades_medida.unidadMedidaId')
            ->join('inv_productos_tipo', 'inv_productos_tipo.productoTipoId = inv_productos.productoTipoId')
            ->join('inv_productos_plataforma', 'inv_productos_plataforma.productoPlataformaId = inv_productos.productoPlataformaId')
            ->join('cat_unidades_medida', 'cat_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
            ->where('flgElimina', 0)
            ->where('productoId', $productoId)->first();
        } else {

             // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'productoId'            => 0,
                'productoTipoId'        => '',
                'productoPlataformaId'  => '',
                'unidadMedidaId'        => '',
                'producto'              => '',
                'codigoProducto'        => '',
                'descripcionProducto'   => '',
                'existenciaMinima'      => '',
                'fechaInicioInventario' => '',
                'flgProductoVenta'      => ''

            ];
        }
        $data['operacion'] = $operacion;

        return view('inventario/modals/modalAdministracionProducto', $data);
    }

    public function modalAdministracionPrecio()
    { 
    

    
            return view('inventario/modals/modalAdministracionPrecio');
        }

    public function tablaProducto()
    {
        $mostrarProducto = new inv_productos();
        $datos = $mostrarProducto
        ->select('inv_productos.productoId,inv_productos.codigoProducto,inv_productos.producto,inv_productos.descripcionProducto,inv_productos.existenciaMinima,inv_productos.estadoProducto,inv_productos_plataforma.productoPlataformaId,inv_productos_tipo.productoTipoId,inv_productos_tipo.productoTipo,cat_unidades_medida.unidadMedidaId')
        ->join('inv_productos_tipo', 'inv_productos_tipo.productoTipoId = inv_productos.productoTipoId')
        ->join('inv_productos_plataforma', 'inv_productos_plataforma.productoPlataformaId = inv_productos.productoPlataformaId')
        ->join('cat_unidades_medida', 'cat_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->where('inv_productos.flgElimina', 0)
        ->findAll();
    
        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Codigo:</b> " . $columna['codigoProducto']."<br>"."<b>Producto:</b> " . $columna['producto']."<br>"."<b>Estado:</b> " . $columna['estadoProducto'];
            $columna3 = "<b>Tipo Producto:</b> " . $columna['productoTipo']."<br>"."<b>Plataforma:</b> " . $columna['producto']."<br>"."<b>Descripción:</b> " . $columna['descripcionProducto'];
            $columna4 = "<b>Sin IVA:</b> "."<br>"."<b>Con IVA:</b> ";

            // Aquí puedes construir tus botones en la última columna
            $columna5 = '
            <button class="btn btn-info mb-1" onclick="modalExistencia(`'.$columna['productoId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Existencias de producto">
                <span></span>
                <i class="fas fa-box-open"></i>
            </button>
        ';
            $columna5 .= '
                <button class="btn btn-primary mb-1" onclick="modalEditar(`'.$columna['productoId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar producto">
                    <span></span>
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';
            $columna5 .= '
            <button class="btn btn-success mb-1" onclick="modalPrecios(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Actualizar precios de venta">
                <span></span>
                <i class="fas fa-dollar-sign"></i>
            </button>
        ';
        $columna5 .= '
        <button class="btn btn-info mb-1" onclick="modalHistorial(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Historiales">
            <span></span>
            <i class="fas fa-clock"></i>
        </button>
    ';

            $columna5 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarPlataforma(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            ';

            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5
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

    public function modalProductoOperacion()
    {
        // Establecer reglas de validación
        $validation = service('validation');
        $validation->setRules([
            'productoTipoId'        => 'required',
            'productoPlataformaId'  => 'required',
            'unidadMedidaId'        => 'required',
            'producto'              => 'required',
            'codigoProducto'        => 'required',
            'descripcionProducto'   => 'required',
            'existenciaMinima'      => 'required',
            'fechaInicioInventario' => 'required',
            'flgProductoVenta'      => 'required'
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
        $codigoProducto = $this->request->getPost('codigoProducto');
        $operacion = $this->request->getPost('operacion');
        $productoId = $this->request->getPost('productoId');
        $model = new inv_productos();

        if ($model->existeCodigo($codigoProducto, $productoId)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El Codigo de producto ya está registrado en la base de datos'
            ]);
        } else {
        $data = [
            'productoTipoId'        => $this->request->getPost('productoTipoId'),
            'productoPlataformaId'  => $this->request->getPost('productoPlataformaId'),
            'unidadMedidaId'        => $this->request->getPost('unidadMedidaId'),
            'producto'              => $this->request->getPost('producto'),
            'codigoProducto'        => $this->request->getPost('codigoProducto'),
            'descripcionProducto'   => $this->request->getPost('descripcionProducto'),
            'existenciaMinima'      => $this->request->getPost('existenciaMinima'),
            'fechaInicioInventario' => $this->request->getPost('fechaInicioInventario'),
            'flgProductoVenta'      => $this->request->getPost('flgProductoVenta'),
            'estadoProducto'        => "Activo"
        ];
    
        if ($operacion == 'editar') {
            $operacionProducto = $model->update($this->request->getPost('productoId'), $data);
        } else {
            // Insertar datos en la base de datos
            $operacionProducto = $model->insert($data);
        }
    
        if ($operacionProducto) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Producto' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                'productoId' => ($operacion == 'editar' ? $this->request->getPost('productoId') : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el producto'
            ]);
        }
      }
    }


}
