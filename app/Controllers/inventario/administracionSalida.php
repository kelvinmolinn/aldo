<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos;
use App\Models\inv_productos_existencias;
use App\Models\cat_14_unidades_medida;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos_plataforma;
use App\Models\conf_sucursales;
use App\Models\inv_kardex;
use App\Models\log_productos_precios;
use App\Models\conf_parametrizaciones;
use App\Models\inv_descargos;
use App\Models\inv_descargos_detalle;
use App\Models\vista_usuarios_empleados;

class AdministracionSalida extends Controller
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
                'route'             => 'inventario/admin-salida/index',
                'camposSession'     => json_encode($camposSession)
            ]);

            return view('inventario/vistas/administracionSalida', $data);
        }
    }
    public function modalAdministracionSalida()
    { 
               // Cargar el modelos
            $sucursalesModel = new conf_sucursales();
            $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['sucursalId'] = $this->request->getPost('sucursalId');
    
            if($operacion == 'editar') {
                $descargosId = $this->request->getPost('descargosId');
                $salidaProducto = new inv_descargos();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $producto->select('inv_descargos.descargosId,inv_descargos.fhDescargo,inv_descargos.obsDescargo,inv_descargos.estadoDescargo,conf_sucursales.sucursalId,conf_sucursales.sucursal')
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_descargos.sucursalId')
                ->where('inv_descargos.flgElimina', 0)
                ->where('inv_descargos.descargosId', $descargosId)->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'descargosId'             => 0,
                    'sucursalId'              => '',
                    'obsDescargo'             => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('inventario/modals/modalAdministracionSalida', $data);
        }
        public function modalSalidaOperacion()
        {
            // Continuar con la operación de inserción o actualización en la base de datos
            $operacion = $this->request->getPost('operacion');
            $descargosId = $this->request->getPost('descargosId');
            $model = new inv_descargos();
            $session = session();
            $usuarioIdAgrega = $session->get("usuarioId");
    
            $data = [
                'sucursalId'        => $this->request->getPost('sucursalId'),
                'fhDescargo'        => date('Y-m-d H:i:s'),
                'obsDescargo'       => $this->request->getPost('obsDescargo'),
                'estadoDescargo'    => "Pendiente",
                'usuarioIdAgrega'   => $usuarioIdAgrega
            ];
        
            if ($operacion == 'editar') {
                $operacionSalida = $model->update($this->request->getPost('descargosId'), $data);
            } else {
                // Insertar datos en la base de datos
                $operacionSalida = $model->insert($data);
            }
        
            if ($operacionSalida) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Salida ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                    'descargosId' => ($operacion == 'editar' ? $this->request->getPost('descargosId') : $model->insertID())
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar la salida o descargo'
                ]);
            }
          
        }

public function tablaSalida()
{
    $descargosId = $this->request->getPost('descargosId');
    $mostrarSalida = new inv_descargos();
    $vistaUsuariosEmpleados = new vista_usuarios_empleados();
    $usuarioIdAgrega = $this->request->getPost("usuarioId");
    $datos = $mostrarSalida
        ->select('inv_descargos.descargosId, inv_descargos.fhDescargo, inv_descargos.obsDescargo, inv_descargos.estadoDescargo, conf_sucursales.sucursalId, conf_sucursales.sucursal, inv_descargos.usuarioIdAgrega, vista_usuarios_empleados.primerNombre, vista_usuarios_empleados.primerApellido')
        ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_descargos.sucursalId')
        ->join('vista_usuarios_empleados', 'inv_descargos.usuarioIdAgrega = vista_usuarios_empleados.usuarioId')
        ->where('inv_descargos.flgElimina', 0)
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas
    foreach ($datos as $columna) {
        // Determina la clase Bootstrap basada en el estado del descargo
        $estadoClase = '';
        if ($columna['estadoDescargo'] === 'Pendiente') {
            $estadoClase = 'badge badge-secondary';
        } elseif ($columna['estadoDescargo'] === 'Finalizado') {
            $estadoClase = 'badge badge-success';
        } elseif ($columna['estadoDescargo'] === 'Anulado') {
            $estadoClase = 'badge badge-danger';
        }

        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Sucursal:</b> " . $columna['sucursal'];
        $columna3 = "<b>Usuario: </b> ". $columna['primerNombre'] . " " . $columna['primerApellido'] . "<br>" ."<b>Fecha/Hora:</b> " . $columna['fhDescargo'];
        $columna4 = "<b>Motivo/Justificación:</b> " . $columna['obsDescargo'] . "<br>" . "<b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoDescargo'] . "</span>";
        
        // Construir botones basado en estadoDescargo
        if ($columna['estadoDescargo'] === 'Pendiente') {
            $jsonActualizarDescargo = [
                "descargosId"          => $columna['descargosId']
            ];
            $columna5 = '
                <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`inventario/admin-salida/vista/actualizar/descargo`, '.htmlspecialchars(json_encode($jsonActualizarDescargo)).');" data-toggle="tooltip" data-placement="top" title="Continuar">
                    <i class="fas fa-sync-alt"></i> <span> Continuar</span>
                </button>

            ';
        } elseif ($columna['estadoDescargo'] === 'Finalizado') {
            $columna5 = '
                <button class="btn btn-info mb-1" onclick="modalExistenciaProducto(`'.$columna['descargosId'].'`);" data-toggle="tooltip" data-placement="top" title="Ver descargo">
                    <i class="fas fa-eye"></i>
                </button>
            ';
        } else {
            $columna5 = '
                   <button class="btn btn-info mb-1" onclick="modalExistenciaProducto(`'.$columna['descargosId'].'`);" data-toggle="tooltip" data-placement="top" title="Ver descargo">
        <i class="fas fa-eye"></i>
                </button>'; // No buttons if the status is neither 'Pendiente' nor 'Finalizado'
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

public function vistaContinuarDescargo(){
    $session = session();

    $descargosId = $this->request->getPost('descargosId');


    $camposSession = [
        'renderVista' => 'No',
        'descargosId'    => $descargosId
    ];
    $session->set([
        'route'             => 'inventario/admin-salida/vista/actualizar/descargo',
        'camposSession'     => json_encode($camposSession)
    ]);
    $data['descargosId'] = $descargosId;
    $mostrarSalida = new inv_descargos();
   
            // Consulta para traer los valores de los input que se pueden actualizar
            $data['dataSucursal'] = $mostrarSalida
            ->select("conf_sucursales.sucursal")
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_descargos.sucursalId')
            ->where("inv_descargos.flgElimina", 0)
            ->where("inv_descargos.descargosId", $descargosId)
            ->first(); 

    
    return view('inventario/vistas/pageContinuarDescargo', $data);
}
    public function modalAdministracionNuevaSalida()
    { 
               // Cargar el modelos
            $productosModel = new inv_productos();
            $data['producto'] = $productosModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['productoId'] = $this->request->getPost('productoId');
            $descargosId = $this->request->getPost('descargosId');
    
            if($operacion == 'editar') {
                $descargoDetalleId = $this->request->getPost('descargoDetalleId');
                $salidaProducto = new inv_descargos_detalle();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $producto->select('inv_descargos_detalle.descargoDetalleId,inv_descargos_detalle.descargoId,inv_descargos_detalle.cantidadDescargo,inv_descargos_detalle.obsDescargoDetalle,inv_productos.productoId')
                ->join('inv_productos', 'inv_productos.productoId = inv_descargos_detalle.productoId')
                ->where('inv_descargos_detalle.flgElimina', 0)
                //->where('inv_descargos_detalle.descargoDetalleId', $descargoDetalleId)
                ->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'descargoDetalleId'       => 0,
                    'descargosId'             => $descargosId,
                    'productoId'              => '',
                    'cantidadDescargo'        => '',
                    'obsDescargoDetalle'      => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('inventario/modals/modalAdministracionNuevaSalida', $data);
        }

  /*      public function modalNuevaSalidaOperacion()
{
    // Continuar con la operación de inserción o actualización en la base de datos
    $operacion = $this->request->getPost('operacion');
    $descargoDetalleId = $this->request->getPost('descargoDetalleId');
    $model = new inv_descargos_detalle();
    $descargosId = $this->request->getPost('descargosId');
    $productoId = $this->request->getPost('productoId');
    $cantidadDescargo = $this->request->getPost('cantidadDescargo');
    
    // Obtener la existencia actual del producto en la sucursal
    $productosModel = new inv_productos_existencias();
    $producto = $productosModel->find($productoId);

    if (!$producto) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Producto no encontrado'
        ]);
    }

    $existenciaActual = $producto['existenciaProducto']; 

    // Validar si la existencia es suficiente
    if ($existenciaActual < $cantidadDescargo) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No hay existencias suficientes para realizar la salida'
        ]);
    }

    $data = [
        'productoId'            => $productoId,
        'cantidadDescargo'      => $cantidadDescargo,
        'obsDescargoDetalle'    => $this->request->getPost('obsDescargoDetalle'),
        'descargosId'           => $descargosId
    ];

    if ($operacion == 'editar') {
        $operacionSalida = $model->update($descargoDetalleId, $data);
    } else {
        // Insertar datos en la base de datos
        $operacionSalida = $model->insert($data);
    }

    if ($operacionSalida) {
        // Si el insert fue exitoso, devuelve el último ID insertado
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Salida ' . ($operacion == 'editar' ? 'actualizada' : 'agregada') . ' correctamente',
            'descargoDetalleId' => ($operacion == 'editar' ? $descargoDetalleId : $model->insertID())
        ]);
    } else {
        // Si el insert falló, devuelve un mensaje de error
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo insertar la salida o descargo'
        ]);
    }
}


    Necesito una consulta que me muestre los detalles del descargo por productoId, descargoId, sucursalId, luego de eso si no hay descargoDetalleId hacer el insert, pero si hay descargoDetalleId hacer la validacion anterior, luego si se hace otro descargoDetalleId validar la existencia menos el descargoDetalleId y ver si se puuede hacer el descargo */

public function modalNuevaSalidaOperacion()
{
    $operacion = $this->request->getPost('operacion');
    $descargoDetalleId = $this->request->getPost('descargoDetalleId');
    $model = new inv_descargos_detalle();
    $descargosId = $this->request->getPost('descargosId');
    $productoId = $this->request->getPost('productoId');
    $cantidadDescargo = $this->request->getPost('cantidadDescargo');
    
    // Obtener la existencia actual del producto en la sucursal
    $productosModel = new inv_productos_existencias();
    $producto = $productosModel->find($productoId);

    if (!$producto) {
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'Producto no encontrado'
        ]);
    }

    $existenciaActual = $producto['existenciaProducto'];

    if ($descargoDetalleId) {
        $detalleActual = $model->find($descargoDetalleId);

        if (!$detalleActual) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Detalle de descargo no encontrado'
            ]);
        }

        $cantidadAnterior = $detalleActual['cantidadDescargo'];
        $nuevaCantidad = $existenciaActual + $cantidadAnterior - $cantidadDescargo;

        if ($nuevaCantidad <= 0) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No hay existencias suficientes para realizar la salida'
            ]);
        }
    } else {
        // Validar si la existencia es suficiente para una nueva inserción
        if ($existenciaActual < $cantidadDescargo) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No hay existencias suficientes para realizar la salida'
            ]);
        }
    }

    $data = [
        'productoId'            => $productoId,
        'cantidadDescargo'      => $cantidadDescargo,
        'obsDescargoDetalle'    => $this->request->getPost('obsDescargoDetalle'),
        'descargosId'           => $descargosId
    ];

    if ($operacion == 'editar' && $descargoDetalleId) {
        $operacionSalida = $model->update($descargoDetalleId, $data);
    } else {
        // Insertar datos en la base de datos
        $operacionSalida = $model->insert($data);
    }

    if ($operacionSalida) {
        // Si el insert fue exitoso, devuelve el último ID insertado
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Salida ' . ($operacion == 'editar' ? 'actualizada' : 'agregada') . ' correctamente',
            'descargoDetalleId' => ($operacion == 'editar' ? $descargoDetalleId : $model->insertID())
        ]);
    } else {
        // Si el insert falló, devuelve un mensaje de error
        return $this->response->setJSON([
            'success' => false,
            'mensaje' => 'No se pudo insertar la salida o descargo'
        ]);
    }
}

 

public function tablaContinuarSalida()
{

    $descargosId = $this->request->getPost('descargosId');
    $mostrarSalida = new inv_descargos_detalle();
    $datos = $mostrarSalida
        ->select('inv_descargos_detalle.descargoDetalleId,inv_descargos_detalle.descargosId,inv_descargos_detalle.cantidadDescargo,inv_descargos_detalle.obsDescargoDetalle,inv_productos.productoId, inv_productos.producto,inv_productos.codigoProducto,cat_14_unidades_medida.unidadMedida')
        ->join('inv_productos', 'inv_productos.productoId = inv_descargos_detalle.productoId')
        ->join('cat_14_unidades_medida', 'cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->join('inv_descargos', 'inv_descargos.descargosId = inv_descargos_detalle.descargosId')
        ->where('inv_descargos_detalle.flgElimina', 0)
        ->where('inv_descargos_detalle.descargosId', $descargosId)
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas
    foreach ($datos as $columna) {
    
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Producto:</b> " . $columna['producto']. "<br>" ."<b>Código :</b> " . $columna['codigoProducto'];
        $columna3 = "<b>Cantidad: </b> ". $columna['cantidadDescargo'] ." (". $columna['unidadMedida'] .")" ;
        $columna4 = "<b>Motivo/Justificación:</b> " . $columna['obsDescargoDetalle'] ;
        
        $columna5 = '

        <button class="btn btn-primary mb-1" onclick="modalHistorial(`'.$columna['descargoDetalleId'].'`);" data-toggle="tooltip" data-placement="top" title="Editar">
            <i class="fas fa-pen"></i> <span></span>
        </button>
        ';
        $columna5 .= '

            <button class="btn btn-danger mb-1" onclick="modalHistorial(`'.$columna['descargoDetalleId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                <i class="fas fa-trash"></i> <span></span>
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
    
}