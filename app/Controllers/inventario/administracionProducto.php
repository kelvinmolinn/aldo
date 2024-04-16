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

    public function tablaProducto()
    {
        $mostrarProducto = new inv_productos();
        $datos = $mostrarProducto
        ->select('inv_productos.productoId,inv_productos_tipo.productoTipo,inv_productos_plataforma.productoPlataforma,inv_productos.codigoProducto, inv_productos.producto,inv_productos.descripcionProducto,inv_productos.estadoProducto,inv_productos.costoUnitarioFOB,inv_productos.CostoUnitarioRetaceo')
        ->join('inv_productos_tipo', 'inv_productos_tipo.productoTipoId = inv_productos.productoTipoId')
        ->join('inv_productos_plataforma', 'inv_productos_plataforma.productoPlataformaId = inv_productos.productoPlataformaId')
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
            $columna4 = "<b>Con IVA: </b>$" . $columna['CostoUnitarioRetaceo']."<br>"."<b>Sin IVA: </b>$" . $columna['costoUnitarioFOB'];

            // Aquí puedes construir tus botones en la última columna
            $columna5 = '
                <button class="btn btn-primary mb-1" onclick="modalPlataforma(`'.$columna['productoId'].'`, `editar`);" data-toggle="tooltip" data-placement="top" title="Editar Plataforma de producto">
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
        <button class="btn btn-primary mb-1" onclick="modalHistorial(`'.$columna['productoId'].'`);" data-toggle="tooltip" data-placement="top" title="Historiales">
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


}
