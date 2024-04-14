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

    public function modalProducto()
    {
        $operacion = $this->request->getPost('operacion');
        
        $data['operacion'] = $operacion;
        $data['productoTipoId'] = $this->request->getPost('productoTipoId');
        $data['productoPlataformaId'] = $this->request->getPost('productoPlataformaId');
        $data['unidadMedidaId'] = $this->request->getPost('unidadMedidaId');
        $data['productoId'] = $this->request->getPost('productoId');

        $modelUnidades = new cat_unidades_medida();
        $modelUnidades = new inv_productos_tipo();
        $modelUnidades = new inv_productos_plataforma();

        if($operacion == 'editar') {
            $productoId = $this->request->getPost('productoId');
            $modulo = new inv_productos();
            $data['campos'] = $modulo->select('inv_productos.productoId,productoTipoId,productoPlataformaId, unidadMedidaId,inv_productos.codigoProducto,inv_productos.producto,inv_productos.descripcionProducto,inv_productos.existenciaMinima,inv_productos.fechaInicioInventario,inv_productos.costoUnitarioFOB,inv_productos.CostoUnitarioRetaceo,inv_productos.CostoPromedio,inv_productos.flgProductoVenta,inv_productos.precioVenta,inv_productos.estadoProducto')
            ->join('inv_productos_tipo', 'inv_productos.productoTipoId = inv_productos_tipo.productoTipoId')
            ->join('inv_productos_plataforma', 'inv_productos.productoTipoId = inv_productos_plataforma.productoPlataformaId')
            ->join('cat_unidades_medida', 'inv_productos.productoTipoId = cat_unidades_medida.unidadMedidaId')
            ->where('flgElimina', 0)->where('productoId', $productoId)->first();
        } else {
            $data['campos'] = [
                'productoId'                => 0,
                'productoTipoId'            => '',
                'productoPlataformaId'      => '', 
                'unidadMedidaId'            => '',
                'codigoProducto'            => '',
                'producto'                  => '',
                'descripcionProducto'       => '',
                'existenciaMinima'          => '',
                'fechaInicioInventario'     => '',
                'flgProductoVenta'          => '',
                'precioVenta'               => ''
            ];
        }

        return view('intentario/modals/modalAdministracionProducto', $data);
    }

}