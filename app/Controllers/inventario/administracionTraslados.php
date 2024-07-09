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
use App\Models\inv_traslados_detalle;
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
    $vistaUsuariosEmpleados = new vista_usuarios_empleados();
    $usuarioIdAgrega = $this->request->getPost("usuarioId");
    $datos = $mostrarTraslado
        ->select('inv_traslados.trasladosId,inv_traslados.usuarioIdSalida,inv_traslados.usuarioIdEntrada,inv_traslados.sucursalIdSalida,inv_traslados.sucursalIdEntrada, inv_traslados.fhSolicitud, inv_traslados.obsSolicitud, inv_traslados.estadoTraslado, cs.sucursal AS sucursalEntrada, s.sucursal AS sucursalSalida, vista_usuarios_empleados.primerNombre, vista_usuarios_empleados.primerApellido')
        ->join('conf_sucursales AS s', 's.sucursalId = inv_traslados.sucursalIdSalida')
        ->join('conf_sucursales AS cs', 'cs.sucursalId = inv_traslados.sucursalIdEntrada','left')
        ->join('vista_usuarios_empleados', 'inv_traslados.usuarioIdSalida = vista_usuarios_empleados.usuarioId')
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
        $columna2 = "<b>Solicitado por:</b> " . $columna['primerNombre'] . " " . $columna['primerApellido'] . " (" . $columna['sucursalSalida'] . ")"."<br>"."<b>Autorizado por: </b>". $columna['sucursalEntrada'];
        $columna3 = "<b>Solicitud:</b> " . $columna['fhSolicitud']. "<br>" . "<b>Autorización:</b>";
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
    
                // Consulta para traer los valores de los input que se pueden actualizar
                $data['dataSucursal'] = $mostrarSalida
                ->select("conf_sucursales.sucursal")
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_traslados.usuarioIdSalida')
                ->where("inv_traslados.flgElimina", 0)
                ->where("inv_traslados.trasladosId", $trasladosId)
                ->first(); 

        
        return view('inventario/vistas/pageContinuarTraslados', $data);
    }
  /*  public function modalAdministracionNuevoTraslado()
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
                $data['campos'] = $salidaProducto->select('inv_traslados_detalles.trasladoDetalleId,inv_traslados_detalles.trasladosId,inv_traslados_detalles.cantidadSolicitud,inv_traslados_detalles.obsTrasladoSolicitudDetalle,inv_productos.productoId')
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
                    'cantidadSolicitud'                => '',
                    'obsTrasladoSolicitudDetalle'      => ''
    
                ];
            }
            $data['operacion'] = $operacion;
    
            return view('inventario/modals/modalAdministracionNuevoTraslado', $data);
    }

    public function modalNuevaSalidaOperacion()
{
    $operacion = $this->request->getPost('operacion');
    $descargoDetalleId = $this->request->getPost('descargoDetalleId');
    $model = new inv_descargos_detalle();
    $sucursalModel = new inv_descargos();  // Renombrado para mayor claridad
    $descargosId = $this->request->getPost('descargosId');
    $productoId = $this->request->getPost('productoId');
    $cantidadDescargo = $this->request->getPost('cantidadDescargo');
    
    //Necesito Traer sucursalId de inv_descargos 
    $descargoData = $sucursalModel->find($descargosId);
    $sucursalId = $descargoData['sucursalId'];  // Asegúrate de que el campo se llama sucursalId en la base de datos
   // $sucursalId = 1;

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
                                ->where('descargosId', $descargosId)
                                ->where('productoId', $productoId)
                                ->countAllResults();

    if ($descargoDetalleId || $cantidadDetalleActual > 0) {
        if($descargoDetalleId) {
            // Es update-editar
            $detalleActual = $model->selectSum('cantidadDescargo')
                                    ->where('flgElimina', 0)
                                    ->where('descargosId', $descargosId)
                                    ->where('productoId', $productoId)
                                    ->where('descargoDetalleId <>', $descargoDetalleId)
                                    ->first();
        } else {
            $detalleActual = $model->selectSum('cantidadDescargo')
                                ->where('flgElimina', 0)
                                ->where('descargosId', $descargosId)
                                ->where('productoId', $productoId)
                                ->first();
        }

        if (!$detalleActual) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Detalle de descargo no encontrado'
            ]);
        }

        if (($cantidadDescargo + $detalleActual['cantidadDescargo']) > $existenciaActual) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No hay existencias suficientes para realizar la salida'
            ]);
        }
    } else {
        // Validar si la existencia es suficiente para una nueva inserción
        if ($cantidadDescargo > $existenciaActual) {
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
*/

}