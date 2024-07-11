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
use App\Models\inv_traslados;
use App\Models\inv_traslados_detalles;
use App\Models\vista_usuarios_empleados;
use App\Models\conf_empleados;


class AdministracionTraslados extends Controller
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
                'route'             => 'inventario/admin-traslados/index',
                'camposSession'     => json_encode($camposSession)
            ]);

            return view('inventario/vistas/administracionTraslados', $data);
        }
    }

    public function modalAdministracionTraslados()
    { 
               // Cargar el modelos
            $sucursalesModel = new conf_sucursales();
            $empleadosModel = new conf_empleados();
            $data['empleados'] = $empleadosModel->where('flgElimina', 0)->findAll();
            $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['empleadoId'] = $this->request->getPost('empleadoId');
            $data['sucursalId'] = $this->request->getPost('sucursalId');
           
    
            if($operacion == 'editar') {
                $trasladosId = $this->request->getPost('trasladosId');
                $salidaProducto = new inv_traslados();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $producto->select('inv_traslados.trasladosId,inv_traslados.sucursalIdSalida,inv_traslados.sucursalIdEntrada,inv_traslados.empleadoIdSalida,inv_traslados.empleadoIdEntrada,inv_traslados.obsSolicitud,inv_traslados.fechaTraslado,inv_traslados.estadoTraslado,conf_sucursales.sucursalId,conf_sucursales.sucursal,conf_empleados.empleadoId,conf_empleados.primerNombre,conf_empleados.primerApellido')
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_traslados.sucursalId')
                ->join('conf_empleados', 'conf_empleados.empleadoId = inv_traslados.empleadoId')
                ->where('inv_traslados.flgElimina', 0)
                ->where('inv_traslados.trasladosId', $trasladosId)->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'trasladosId'             => 0,
                    'sucursalIdSalida'        => '',
                    'sucursalIdEntrada'       => '',
                    'empleadoIdSalida'        => '',
                    'empleadoIdEntrada'       => '',
                    'fechaTraslado'           => '',
                    'obsSolicitud'            => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('inventario/modals/modalAdministracionTraslados', $data);
        }

        public function modalTrasladoOperacion()
        {
            // Continuar con la operación de inserción o actualización en la base de datos
            $operacion = $this->request->getPost('operacion');
            $trasladosId = $this->request->getPost('trasladosId');
            $model = new inv_traslados();

    
            $data = [
                'sucursalIdSalida'        => $this->request->getPost('sucursalIdSalida'),
                'sucursalIdEntrada'       => $this->request->getPost('sucursalIdEntrada'),
                'empleadoIdSalida'        => $this->request->getPost('empleadoIdSalida'),
                'empleadoIdEntrada'       => $this->request->getPost('empleadoIdEntrada'),
                'fechaTraslado'           => $this->request->getPost('fechaTraslado'),
                'obsSolicitud'            => $this->request->getPost('obsSolicitud'),
                'estadoTraslado'          => "Pendiente"
            ];
        
            if ($operacion == 'editar') {
                $operacionSalida = $model->update($this->request->getPost('trasladosId'), $data);
            } else {
                // Insertar datos en la base de datos
                $operacionSalida = $model->insert($data);
            }
        
            if ($operacionSalida) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Traslado ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                    'trasladosId' => ($operacion == 'editar' ? $this->request->getPost('trasladosId') : $model->insertID())
                ]);
            } else {
                // Si el insert falló, devuelve un mensaje de error
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo insertar el traslado'
                ]);
            }
          
        }

        public function tablaTraslados()
{
    $trasladosId = $this->request->getPost('trasladosId');
    $mostrarTraslado = new inv_traslados();
    $vistaUsuariosEmpleados = new conf_empleados();
    $usuarioIdAgrega = $this->request->getPost("empleadoId");
    $datos = $mostrarTraslado
        ->select('inv_traslados.trasladosId,inv_traslados.empleadoIdSalida,inv_traslados.empleadoIdEntrada,inv_traslados.sucursalIdSalida,inv_traslados.sucursalIdEntrada, inv_traslados.fechaTraslado, inv_traslados.obsSolicitud, inv_traslados.estadoTraslado, cs.sucursal AS sucursalEntrada, s.sucursal AS sucursalSalida, ce.primerNombre AS empleadoIdEntrada,ce.primerApellido AS empleadoIdEntrada2, e.primerNombre AS empleadoIdSalida,e.primerApellido AS empleadoIdSalida2')
        ->join('conf_sucursales AS s', 's.sucursalId = inv_traslados.sucursalIdSalida')
        ->join('conf_sucursales AS cs', 'cs.sucursalId = inv_traslados.sucursalIdEntrada','left')
        ->join('conf_empleados AS e', 'e.empleadoId = inv_traslados.empleadoIdSalida')
        ->join('conf_empleados AS ce', 'ce.empleadoId = inv_traslados.empleadoIdEntrada','left')
        ->where('inv_traslados.flgElimina', 0)
        ->findAll();

    $output['data'] = array();
    $n = 1; // Variable para contar las filas
    foreach ($datos as $columna) {
        // Determina la clase Bootstrap basada en el estado del descargo
        $estadoClase = '';
        if ($columna['estadoTraslado'] === 'Pendiente') {
            $estadoClase = 'badge badge-secondary';
        } elseif ($columna['estadoTraslado'] === 'Finalizado') {
            $estadoClase = 'badge badge-success';
        } elseif ($columna['estadoTraslado'] === 'Anulado') {
            $estadoClase = 'badge badge-danger';
        }

        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Recibe :</b> " . $columna['empleadoIdEntrada'] . " " . $columna['empleadoIdEntrada2'] . " (" . $columna['sucursalEntrada'] . ")"."<br>"."<b>Envía: </b>". $columna['empleadoIdSalida'] . " " . $columna['empleadoIdSalida2'] . " (" . $columna['sucursalSalida'] . ")";
        $columna3 = "<b>Fecha:</b> " . $columna['fechaTraslado']. "<br>" ;
        $columna4 = "<b>Motivo/Justificación:</b> " . $columna['obsSolicitud'] . "<br>" . "<b>Estado:</b> <span class='" . $estadoClase . "'>" . $columna['estadoTraslado'] . "</span>";
        
        // Construir botones basado en estadoTraslado
        if ($columna['estadoTraslado'] === 'Pendiente') {
            $jsonActualizarDescargo = [
                "trasladosId"          => $columna['trasladosId']
            ];
            $columna5 = '
                <button class="btn btn-primary mb-1" onclick="cambiarInterfaz(`inventario/admin-traslados/vista/actualizar/traslados`, '.htmlspecialchars(json_encode($jsonActualizarDescargo)).');" data-toggle="tooltip" data-placement="top" title="Continuar traslado">
                    <i class="fas fa-sync-alt"></i> <span> Continuar</span>
                </button>

            ';
        } elseif ($columna['estadoTraslado'] === 'Finalizado') {
            $columna5 = '
                <button class="btn btn-info mb-1" onclick="modalAdministracionVerDescargo(`'.$columna['trasladosId'].'`);" data-toggle="tooltip" data-placement="top" title="Ver ">
                    </i><span> Ver Traslado</span>
                </button>
            ';
        } else {
            $columna5 = '
                   <button class="btn btn-info mb-1" onclick="modalAdministracionVerDescargo(`'.$columna['trasladosId'].'`);" data-toggle="tooltip" data-placement="top" title="Ver descargo">
        <span> Ver Traslado</span>
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

    public function vistaContinuarTraslados(){
        $session = session();
        $trasladosId = $this->request->getPost('trasladosId');


        $camposSession = [
            'renderVista' => 'No',
            'trasladosId'    => $trasladosId
        ];
        $session->set([
            'route'             => 'inventario/admin-traslados/vista/actualizar/traslados',
            'camposSession'     => json_encode($camposSession)
        ]);

        $data['trasladosId'] = $trasladosId;
        $mostrarSalida = new inv_traslados();
    
                $sucursales = new conf_sucursales;
                $empleados = new conf_empleados;

                $data['sucursales'] = $sucursales
                ->select("sucursalId,sucursal")
                ->where("flgElimina", 0)
                ->findAll();

                $data['empleados'] = $empleados
                ->select("empleadoId,primerNombre,primerApellido")
                ->where("flgElimina", 0)
                ->findAll();

        // Consulta para traer los valores de los input que se pueden actualizar
        $consultaTraslados = $mostrarSalida
        ->select('inv_traslados.empleadoIdSalida,inv_traslados.empleadoIdEntrada,inv_traslados.sucursalIdSalida,inv_traslados.sucursalIdEntrada,inv_traslados.fechaTraslado, inv_traslados.obsSolicitud')
        ->join('conf_sucursales AS s', 's.sucursalId = inv_traslados.sucursalIdSalida')
        ->join('conf_sucursales AS cs', 'cs.sucursalId = inv_traslados.sucursalIdEntrada','left')
        ->join('conf_empleados AS e', 'e.empleadoId = inv_traslados.empleadoIdSalida')
        ->join('conf_empleados AS ce', 'ce.empleadoId = inv_traslados.empleadoIdEntrada','left')
        ->where("inv_traslados.flgElimina", 0)
        ->where("inv_traslados.trasladosId", $trasladosId)
        ->first(); 

        $data['camposEncabezado'] = [
        'sucursalIdSalida'        => $consultaTraslados['sucursalIdSalida'],
        'sucursalIdEntrada'       => $consultaTraslados['sucursalIdEntrada'],
        'empleadoIdSalida'        => $consultaTraslados['empleadoIdSalida'],
        'empleadoIdEntrada'       => $consultaTraslados['empleadoIdEntrada'],
        'fechaTraslado'           => $consultaTraslados['fechaTraslado'],
        'obsSolicitud'            => $consultaTraslados['obsSolicitud']
        // Sacar estos valores de la consulta
        ]; 
        
        return view('inventario/vistas/pageContinuarTraslados', $data);
    }
    public function modalAdministracionNuevoTraslado()
    { 
               // Cargar el modelos
            $productosModel = new inv_productos();
            $data['producto'] = $productosModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['productoId'] = $this->request->getPost('productoId');
            $trasladosId = $this->request->getPost('trasladosId');
    
            if($operacion == 'editar') {
                $trasladoDetalleId = $this->request->getPost('trasladoDetalleId');
                $salidaProducto = new inv_traslados_detalles();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $salidaProducto->select('inv_traslados_detalles.trasladoDetalleId,inv_traslados_detalles.trasladosId,inv_traslados_detalles.cantidadTraslado,inv_traslados_detalles.obsTrasladoSolicitudDetalle,inv_productos.productoId')
                ->join('inv_productos', 'inv_productos.productoId = inv_traslados_detalles.productoId')
                ->where('inv_traslados_detalles.flgElimina', 0)
                ->where('inv_traslados_detalles.trasladoDetalleId', $trasladoDetalleId)
                ->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'trasladoDetalleId'                => 0,
                    'trasladosId'                      => $trasladosId,
                    'productoId'                       => '',
                    'cantidadTraslado'                  => '',
                    'obsTrasladoSolicitudDetalle'      => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('inventario/modals/modalAdministracionNuevoTraslado', $data);
    }

        public function modalNuevoTrasladoOperacion()
    {
        $operacion = $this->request->getPost('operacion');
        $trasladoDetalleId = $this->request->getPost('trasladoDetalleId');
        $model = new inv_traslados_detalles();
        $sucursalModel = new inv_traslados();  // Renombrado para mayor claridad
        $trasladosId = $this->request->getPost('trasladosId');
        $productoId = $this->request->getPost('productoId');
        $cantidadTraslado = $this->request->getPost('cantidadTraslado');
        
        //Necesito Traer sucursalId de inv_traslados 
        $trasladoData = $sucursalModel->find($trasladosId);
        $sucursalId = $trasladoData['sucursalIdSalida'];  // Asegúrate de que el campo se llama sucursalId en la base de datos

        // Obtener la existencia actual del producto en la sucursal
        $productosModel = new inv_productos_existencias();

        $producto = $productosModel->select('existenciaProducto')
                    ->where('flgElimina', 0)
                    ->where('sucursalId', $sucursalId)
                    ->where('productoId', $productoId)
                    ->first();

        if (!$producto) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Producto no encontrado'
            ]);
        }

        $existenciaActual = $producto['existenciaProducto'];

        $cantidadDetalleActual = $model->where('flgElimina', 0)
                                    ->where('trasladosId', $trasladosId)
                                    ->where('productoId', $productoId)
                                    ->countAllResults();

        if ($trasladoDetalleId || $cantidadDetalleActual > 0) {
            if($trasladoDetalleId) {
                // Es update-editar
                $detalleActual = $model->selectSum('cantidadTraslado')
                                        ->where('flgElimina', 0)
                                        ->where('trasladosId', $trasladosId)
                                        ->where('productoId', $productoId)
                                        ->where('trasladoDetalleId <>', $trasladoDetalleId)
                                        ->first();
            } else {
                $detalleActual = $model->selectSum('cantidadTraslado')
                                    ->where('flgElimina', 0)
                                    ->where('trasladosId', $trasladosId)
                                    ->where('productoId', $productoId)
                                    ->first();
            }

            if (!$detalleActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'Detalle del traslado no encontrado'
                ]);
            }

            if (($cantidadTraslado + $detalleActual['cantidadTraslado']) > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar la salida'
                ]);
            }
        } else {
            // Validar si la existencia es suficiente para una nueva inserción
            if ($cantidadTraslado > $existenciaActual) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No hay existencias suficientes para realizar el traslado'
                ]);
            }
        }

        $data = [
            'productoId'                     => $productoId,
            'cantidadTraslado'               => $cantidadTraslado,
            'obsTrasladoSolicitudDetalle'    => $this->request->getPost('obsTrasladoSolicitudDetalle'),
            'trasladosId'                    => $trasladosId
        ];

        if ($operacion == 'editar' && $trasladoDetalleId) {
            $operacionSalida = $model->update($trasladoDetalleId, $data);
        } else {
            // Insertar datos en la base de datos
            $operacionSalida = $model->insert($data);
        }

        if ($operacionSalida) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Traslado ' . ($operacion == 'editar' ? 'actualizado' : 'agregado') . ' correctamente',
                'trasladoDetalleId' => ($operacion == 'editar' ? $trasladoDetalleId : $model->insertID())
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el traslado'
            ]);
        }
    }

    public function eliminarTraslado(){
        
        $eliminarTraslado = new inv_traslados_detalles();
        
        $trasladoDetalleId = $this->request->getPost('trasladoDetalleId');
        $data = ['flgElimina' => 1];
        
        $eliminarTraslado->update($trasladoDetalleId, $data);

        if($eliminarTraslado) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Traslado de producto eliminado correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar el traslado de producto'
            ]);
        }
    }

        public function tablaContinuarTraslado()
    {

        $trasladosId = $this->request->getPost('trasladosId');
        $mostrarTraslado = new inv_traslados_detalles();
        $datos = $mostrarTraslado
            ->select('inv_traslados_detalles.trasladoDetalleId,inv_traslados_detalles.trasladosId,inv_traslados_detalles.cantidadTraslado,inv_traslados_detalles.obsTrasladoSolicitudDetalle,inv_productos.productoId, inv_productos.producto,inv_productos.codigoProducto,cat_14_unidades_medida.unidadMedida')
            ->join('inv_productos', 'inv_productos.productoId = inv_traslados_detalles.productoId')
            ->join('cat_14_unidades_medida', 'cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
            ->join('inv_traslados', 'inv_traslados.trasladosId = inv_traslados_detalles.trasladosId')
            ->where('inv_traslados_detalles.flgElimina', 0)
            ->where('inv_traslados_detalles.trasladosId', $trasladosId)
            ->findAll();

        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach ($datos as $columna) {
        
            // Aquí construye tus columnas
            $columna1 = $n;
            $columna2 = "<b>Producto:</b> " . $columna['producto']. "<br>" ."<b>Código :</b> " . $columna['codigoProducto'];
            $columna3 = "<b>Cantidad: </b> ". $columna['cantidadTraslado'] ." (". $columna['unidadMedida'] .")" ;
            $columna4 = "<b>Motivo/Justificación:</b> " . $columna['obsTrasladoSolicitudDetalle'] ;
            
            $columna5 = '

            <button class="btn btn-primary mb-1" onclick="modalAdministracionNuevoTraslado(`'.$columna['trasladoDetalleId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar">
                <i class="fas fa-pen"></i> <span></span>
            </button>
            ';
            $columna5 .= '

                <button class="btn btn-danger mb-1" onclick="eliminarTraslado(`'.$columna['trasladoDetalleId'].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
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

    function vistaActualizarTrasladoOperacion(){
        $traslados = new inv_traslados;
        $trasladosId = $this->request->getPost('trasladosId');
            // Verificar si hay productos agregados al traslado
            $productosModel = new inv_traslados_detalles();
            $productosAgregados = $productosModel
            ->where('trasladosId', $trasladosId)
            ->where('flgElimina', 0)
            ->countAllResults();
        
        if ($productosAgregados > 0) {
            // Mostrar mensaje de error si hay productos agregados
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se puede actualizar las sucursales porque ya se han agregado productos al traslado.'
            ]);
        }

        $data = [
            'sucursalIdSalida'        => $this->request->getPost('sucursalIdSalida'),
            'sucursalIdEntrada'       => $this->request->getPost('sucursalIdEntrada'),
            'empleadoIdSalida'        => $this->request->getPost('empleadoIdSalida'),
            'empleadoIdEntrada'       => $this->request->getPost('empleadoIdEntrada'),
            'fechaTraslado'           => $this->request->getPost('fechaTraslado'),
            'obsSolicitud'            => $this->request->getPost('obsSolicitud')
        ];

            // Insertar datos en la base de datos
            $operacionCompra = $traslados->update($this->request->getPost('trasladosId'), $data);

        if ($operacionCompra) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Traslado actualizado correctamente',
                'trasladosId' => $this->request->getPost('trasladosId')
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo actualizar el traslado'
            ]);
        }
    }

    function finalizarTraslado(){
        $operacion = $this->request->getPost('operacion');
        $trasladosId = $this->request->getPost('trasladosId');
        $trasladoDetalleId = $this->request->getPost('trasladoDetalleId');
        $modelTrasladosDetalle = new inv_traslados_detalles();
        $modelTraslados = new inv_traslados();
        $productosModel = new Inv_Productos_Existencias();
        $modelKardex = new Inv_Kardex();
        $productosInfoModel = new Inv_Productos();

        
        // Obtener el traslado
        $traslado = $modelTraslados->find($trasladosId);
        if (!$traslado) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Traslado no encontrado.',
                'trasladosId' => $this->request->getPost('trasladosId')
            ]);
        }

        // Obtener los detalles del traslado
        $trasladoDetalles = $modelTrasladosDetalle
        ->where('flgElimina', 0)
        ->where('trasladosId', $trasladosId)->findAll();
        
        if (empty($trasladoDetalles)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No hay detalles para este traslado.',
                'trasladosId' => $this->request->getPost('trasladosId')
            ]);
        }

        foreach ($trasladoDetalles as $detalle) {
            $sucursalIdSalida = $traslado['sucursalIdSalida'];
            $productoId = $detalle['productoId'];
            $cantidadTraslado = $detalle['cantidadTraslado'];

            // Obtener la existencia del producto en la sucursal de salida
            $productoExistencia = $productosModel
                ->where('sucursalId', $sucursalIdSalida)
                ->where('productoId', $productoId)
                ->where('flgElimina', 0)
                ->first();

            if (!$productoExistencia) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => "No se encontró existencia para el producto ID: $productoId en la sucursal ID: $sucursalIdSalida.",
                    'trasladosId' => $this->request->getPost('trasladosId')
                ]);
            }

            $existenciaProducto = $productoExistencia['existenciaProducto'];

            // Comparar existencia con cantidad a trasladar
            if ($existenciaProducto < $cantidadTraslado) {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => "No hay suficiente existencia para el producto ID: $productoId. Existencia: $existenciaProducto, Cantidad a trasladar: $cantidadTraslado.",
                    'trasladosId' => $this->request->getPost('trasladosId')
                ]);
                
            }
             // Obtener datos adicionales del producto
        $productoInfo = $productosInfoModel->find($productoId);


        // Registrar movimiento de salida
        $existenciaDespuesSalida = $existenciaProducto - $cantidadTraslado;
        $insertSalida = [
            'tipoMovimiento' => 'Salida',
            'descripcionMovimiento' => "Salida registrada desde el traslado: $trasladosId",
            'productoExistenciaId' => $productoExistencia['productoExistenciaId'],
            'existenciaAntesMovimiento' => $existenciaProducto,
            'cantidadMovimiento' => $cantidadTraslado,
            'existenciaDespuesMovimiento' => $existenciaDespuesSalida,
            'costoUnitarioFOB' => 0,
            'costoUnitarioRetaceo' => 0,
            'costoPromedio' => 0,
            'precioVentaUnitario' => 0,
            'fechaDocumento' => $traslado['fechaTraslado'],
            'fechaMovimiento' => date('Y-m-d'),
            'tablaMovimiento' => 'inv_traslados_detalle',
            'tablaMovimientoId' => $detalle['trasladoDetalleId']
        ];
        $modelKardex->insert($insertSalida);


        }
        return $this->response->setJSON([
            'success' => true,
            'mensaje' => 'Todos los productos tienen suficiente existencia para el traslado.',
            'trasladosId' => $this->request->getPost('trasladosId')
        ]);

    }
}