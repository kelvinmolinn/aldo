<?php

namespace App\Controllers\inventario\reportes;
use CodeIgniter\Controller;

use App\Models\inv_productos;
use App\Models\inv_productos_existencias;
use App\Models\conf_sucursales;
class administracionReportes extends Controller{

    public function indexReportes(){
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;

            $camposSession = [
                'renderVista' => 'No'
            ];
            $session->set([
                'route'             => 'inventario/admin-reportes/index',
                'camposSession'     => json_encode($camposSession)
            ]);

         return view('inventario/vistas/administracionReportes', $data);
        }
    }
    public function reporteCatalogoproducto(){
            $invProductos = new inv_productos();
            $invProductosExistencias = new inv_productos_existencias();
            $confSucursales = new conf_sucursales();

            $data['variable'] = 0;

            $data['datos'] = $invProductos
                    ->select('inv_productos.productoId,inv_productos.codigoProducto,inv_productos.producto,inv_productos.descripcionProducto,inv_productos_plataforma.productoPlataforma,inv_productos_tipo.productoTipo,cat_14_unidades_medida.unidadMedida,inv_productos.flgProductoVenta,inv_productos.existenciaMinima')
                    ->join('inv_productos_plataforma','inv_productos_plataforma.productoPlataformaId = inv_productos.productoPlataformaId')
                    ->join('inv_productos_tipo','inv_productos_tipo.productoTipoId = inv_productos.productoTipoId')
                    ->join('cat_14_unidades_medida','cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
                    ->where('inv_productos.flgElimina', 0)
                    ->where('inv_productos.estadoProducto','Activo')
                    ->findAll();

            $data['sucurales'] = $confSucursales 
                    ->select('sucursalId,sucursal')
                    ->where('flgElimina', 0)
                    ->findAll();

            $data['existencias'] = [];

            foreach ($data['datos'] as $producto) {
                foreach ($data['sucurales'] as $sucursal) {
                    // Hacer la consulta de existencia para cada producto y sucursal
                    $existencia = $invProductosExistencias
                        ->select('SUM(existenciaProducto) AS existenciaProducto')
                        ->where('flgElimina', 0)
                        ->where('productoId', $producto['productoId'])
                        ->where('sucursalId', $sucursal['sucursalId'])
                        ->first(); // Usamos first para obtener un solo resultado

                    // Almacenar la existencia en el arreglo
                    $data['existencias'][$producto['productoId']][$sucursal['sucursalId']] = $existencia['existenciaProducto'] ?? 0; // Asigna 0 si no hay existencia
                }
            }

         return view('inventario/reportes/reporteCatalogoproductos', $data);
    }
        
}