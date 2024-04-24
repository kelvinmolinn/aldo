<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos;
use App\Models\cat_unidades_medida;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos_plataforma;
use App\Models\conf_sucursales;
use App\Models\inv_productos_existencias;

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
            ->where('inv_productos.flgElimina', 0)
            ->where('inv_productos.productoId', $productoId)->first();
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

    public function tablaProducto()
    {
        $mostrarProducto = new inv_productos();
        $datos = $mostrarProducto
        ->select('inv_productos.productoId,inv_productos.codigoProducto,inv_productos.producto,inv_productos.descripcionProducto,inv_productos.existenciaMinima,inv_productos.estadoProducto,inv_productos_plataforma.productoPlataformaId,inv_productos_plataforma.productoPlataforma,inv_productos_tipo.productoTipoId,inv_productos_tipo.productoTipo,cat_unidades_medida.unidadMedidaId')
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
            $columna3 = "<b>Tipo Producto:</b> " . $columna['productoTipo']."<br>"."<b>Plataforma:</b> " . $columna['productoPlataforma']."<br>"."<b>Descripción:</b> " . $columna['descripcionProducto'];
            $columna4 = "<b>Sin IVA:</b> "."<br>"."<b>Con IVA:</b> ";

            // Aquí puedes construir tus botones en la última columna
        $columna5 = '
            <button class="btn btn-info mb-1" onclick="modalExistenciaProducto(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Existencias de producto">
                <span></span>
                <i class="fas fa-box-open"></i>
            </button>
        ';
        $columna5 .= '
                <button class="btn btn-primary mb-1" onclick="modalProducto(`'.$columna['productoId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar producto">
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
        /*  
        $columna5 .= '
                <button class="btn btn-danger mb-1" onclick="eliminarProducto(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            ';
        */

            if($columna['estadoProducto'] == 'Activo'){
                $mensaje = "¿Estás seguro que desea Desactivar el producto?";
                $mensaje2 = "pasará a Desactivado";
                $columna5 .= '
                <button class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="Desactivar" onclick="cambiarEstadoProducto({productoId: \''.$columna['productoId'].'\', estadoProducto: \''.$columna['estadoProducto'].'\', mensaje: \''.$mensaje.'\', mensaje2: \''.$mensaje2.'\'});">
                    <i class="fas fa-ban"></i>
                </button>';
            } else {
                $mensaje = "¿Estás seguro que desea Activar el producto?";
                $mensaje2 = "pasará a Activo";
                $columna5 .= '
                <button class="btn btn-success mb-1" data-toggle="tooltip" data-placement="top" title="Activar" onclick="cambiarEstadoProducto({productoId: \''.$columna['productoId'].'\', estadoProducto: \''.$columna['estadoProducto'].'\', mensaje: \''.$mensaje.'\', mensaje2: \''.$mensaje2.'\'})">
                    <i class="fas fa-check"></i>
                </button>';
            }        

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

    public function eliminarProducto(){
    
        $eliminarProducto = new inv_productos();
        
        $productoId = $this->request->getPost('productoId');
        $data = ['flgElimina' => 1];
        
        $eliminarProducto->update($productoId, $data);

        if($eliminarProducto) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Producto eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el producto'
            ]);
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
                'mensaje' => 'Producto ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
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

    public function modalAdministracionPrecio()
    { 
    

    
        return view('inventario/modals/modalAdministracionPrecio');
    }

    public function modalAdministracionExistenciaProducto()
    { 
        $data["productoId"] = $this->request->getPost('productoId');
    
        return view('inventario/modals/modalAdministracionExistenciaProducto', $data);
    }

    public function tablaExistenciaProducto()
    {
        $productoId = $this->request->getPost('productoId');
        $mostrarProductoExistencia = new inv_productos_existencias();
        $datos = $mostrarProductoExistencia
        ->select('inv_productos_existencias.productoExistenciaId,inv_productos_existencias.existenciaProducto,inv_productos_existencias.existenciaReservada,conf_sucursales.sucursalId,conf_sucursales.sucursal,inv_productos.productoId,inv_productos.producto,cat_unidades_medida.abreviaturaUnidadMedida')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_productos_existencias.sucursalId')
        ->join('inv_productos', 'inv_productos.productoId = inv_productos_existencias.productoId')
        ->join('cat_unidades_medida', 'cat_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->where('inv_productos_existencias.flgElimina', 0)
        ->where('inv_productos_existencias.productoId', $productoId)
        ->findAll();
    
        //validar si el producto en la sucursal ya existe sumar la nueva existencia al post
        // Construye el array de salida  para solo mostrar una 
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'];
            $columna3 = "<b>Existencia:</b> " . $columna['existenciaProducto'].' '.'('.$columna['abreviaturaUnidadMedida'].')';
            $columna4 = "<b>Existencia Reservada:</b> " . $columna['existenciaReservada'];

            // Agrega la fila al array de salida
            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4
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
    


    public function modalAdministracionExistencia()
{ 

           // Cargar el modelos
        $productoModel = new inv_productos();
        $sucursalesModel = new conf_sucursales();

        $data['producto'] = $productoModel->where('flgElimina', 0)->findAll();
        $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
        $operacion = $this->request->getPost('operacion');
        $data['productoId'] = $this->request->getPost('productoId');
        $data['sucursalId'] = $this->request->getPost('sucursalId');

        if($operacion == 'editar') {
            $productoExistenciaId = $this->request->getPost('productoExistenciaId');
            $existenciaProducto = new inv_productos_existencias();

             // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $producto->select('inv_productos_existencias.productoExistenciaId,inv_productos_existencias.existenciaProducto,inv_productos_existencias.existenciaReservada,inv_productos.productoId,inv_productos.producto,conf_sucursales.sucursalId,conf_sucursales.sucursal')

            ->join('inv_productos', 'inv_productos.productoId = inv_productos_existencias.productoId')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_productos_existencias.sucursalId')
            ->where('inv_productos_existencias.flgElimina', 0)
            ->where('inv_productos_existencias.productoExistenciaId', $productoExistenciaId)->first();
        } else {

             // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'productoExistenciaId'    => 0,
                'productoId'              => '',
                'sucursalId'              => '',
                'existenciaProducto'      => '',
                'existenciaReservada'     => ''

            ];
        }
        $data['operacion'] = $operacion;

        return view('inventario/modals/modalAdministracionExistencia', $data);
    }

       public function modalExistenciaOperacion()
{
    // Establecer reglas de validación
    $validation = service('validation');
    $validation->setRules([
        'productoId'            => 'required',
        'sucursalId'            => 'required',
        'existenciaProducto'    => 'required|greater_than[0]',
        'existenciaReservada'   => 'required|greater_than[-1]'
    ]);

    // Ejecutar la validación
    if (!$validation->withRequest($this->request)->run()) {
        // Si la validación falla, devolver los errores al cliente
        return $this->response->setJSON([
            'success' => false,
            'errors' => $validation->getErrors()
        ]);
    }

    // Obtener datos del formulario
    $productoId = $this->request->getPost('productoId');
    $sucursalId = $this->request->getPost('sucursalId');
    $existenciaProducto = $this->request->getPost('existenciaProducto');
    $existenciaReservada = $this->request->getPost('existenciaReservada');
    $operacion = $this->request->getPost('operacion');
    $productoExistenciaId = $this->request->getPost('productoExistenciaId');

    // Crear instancia del modelo
    $model = new inv_productos_existencias();

    // Buscar la entrada existente del producto en la sucursal
    $existingEntry = $model->where('productoId', $productoId)
                           ->where('sucursalId', $sucursalId)
                           ->first();

    // Construir datos para la inserción o actualización
    $data = [
        'productoId'             => $productoId,
        'sucursalId'             => $sucursalId,
        'existenciaProducto'     => $existenciaProducto,
        'existenciaReservada'    => $existenciaReservada
    ];

    // Si existe la entrada, actualizar; de lo contrario, insertar
    if ($existingEntry) {
        $data['existenciaProducto'] += $existingEntry['existenciaProducto'];
        $operacionExistencia = $model->update($existingEntry['productoExistenciaId'], $data);
    } else {
        $operacionExistencia = $model->insert($data);
    }

    // Preparar la respuesta JSON
    if ($operacionExistencia) {
        $response = [
            'success' => true,
            'mensaje' => 'Existencia ' . ($operacion == 'editar' ? 'actualizada' : 'agregada') . ' correctamente',
            'productoExistenciaId' => ($operacion == 'editar' ? $productoExistenciaId : $model->insertID())
        ];
    } else {
        $response = [
            'success' => false,
            'mensaje' => 'No se pudo ' . ($operacion == 'editar' ? 'actualizar' : 'insertar') . ' la Existencia'
        ];
    }

    return $this->response->setJSON($response);
}


    public function ActivarDesactivar(){
        $desactivarActivarProducto = new inv_productos();
    

        $productoId = $this->request->getPost('productoId');
        $estadoProducto = $this->request->getPost('estadoProducto');

        if($estadoProducto == 'Activo'){
            $data = ['estadoProducto' => 'Inactivo'];
        }else{
            $data = ['estadoProducto' => 'Activo'];
           
        }

        $desactivarActivarProducto->update($productoId, $data);

        if($desactivarActivarProducto) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Se cambió el estado con éxito'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo cambiar el estado del producto'
            ]);
        }
    }


}
