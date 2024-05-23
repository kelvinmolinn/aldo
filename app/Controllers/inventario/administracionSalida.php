<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\inv_productos;
use App\Models\cat_14_unidades_medida;
use App\Models\inv_productos_tipo;
use App\Models\inv_productos_plataforma;
use App\Models\conf_sucursales;
use App\Models\inv_kardex;
use App\Models\log_productos_precios;
use App\Models\conf_parametrizaciones;
use App\Models\inv_descargos;
use App\Models\inv_descargos_detalle;

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
    
            $data = [
                'sucursalId'        => $this->request->getPost('sucursalId'),
                'fhDescargo'        => date('Y-m-d'),
                'obsDescargo'       => $this->request->getPost('obsDescargo'),
                'estadoDescargo'    => "Pendiente"
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
                    'mensaje' => 'No se pudo insertar el producto'
                ]);
            }
          
        }
    
}