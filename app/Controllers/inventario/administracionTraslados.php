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
            $data['sucursales'] = $sucursalesModel->where('flgElimina', 0)->findAll();
            $operacion = $this->request->getPost('operacion');
            $data['sucursalId'] = $this->request->getPost('sucursalId');
    
            if($operacion == 'editar') {
                $trasladosId = $this->request->getPost('trasladosId');
                $salidaProducto = new inv_traslados();
    
                 // seleccionar solo los campos que estan en la modal (solo los input y select)
                $data['campos'] = $producto->select('inv_traslados.trasladosId,inv_traslados.sucursalIdSalida,inv_traslados.sucursalIdEntrada,inv_traslados.obsSolicitud,inv_traslados.estadoTraslado,conf_sucursales.sucursalId,conf_sucursales.sucursal')
                ->join('conf_sucursales', 'conf_sucursales.sucursalId = inv_traslados.sucursalId')
                ->where('inv_traslados.flgElimina', 0)
                ->where('inv_traslados.trasladosId', $trasladosId)->first();
            } else {
    
                 // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
                $data['campos'] = [
                    'trasladosId'             => 0,
                    'sucursalIdSalida'        => '',
                    'sucursalIdEntrada'       => '',
                    'obsSolicitud'             => ''
    
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
            $session = session();
            $usuarioIdAgrega = $session->get("usuarioId");
    
            $data = [
                'sucursalIdSalida'        => $this->request->getPost('sucursalIdSalida'),
                'sucursalIdEntrada'       => $this->request->getPost('sucursalIdEntrada'),
                'fhSolicitud'              => date('Y-m-d H:i:s'),
                'obsSolicitud'             => $this->request->getPost('obsSolicitud'),
                'estadoTraslado'          => "Pendiente",
                'usuarioIdSalida'         => $usuarioIdAgrega
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

}