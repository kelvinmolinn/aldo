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
            $data['campos'] = $modulo->select('productoId,productoTipoId,productoPlataformaId, unidadMedidaId','codigoProducto','producto','descripcionProducto','existenciaMinima','fechaInicioInventario','costoUnitarioFOB','CostoUnitarioRetaceo','CostoPromedio','flgProductoVenta','precioVenta','estadoProducto')->where('flgElimina', 0)->where('productoId', $productoId)->first();
        } else {
            $data['campos'] = [
                'productoId'      => 0,
                'menu'        => '',
                'iconoMenu'   => '', 
                'urlMenu'     => ''
            ];
        }

        return view('configuracion-general/modals/modalAdministracionMenus', $data);
    }

}