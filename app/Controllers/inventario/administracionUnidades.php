<?php

namespace App\Controllers\inventario;

use CodeIgniter\Controller;
use App\Models\cat_unidades_medida;
class AdministracionUnidades extends Controller
{
    public function index()
    {
        $session = session();
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $data['variable'] = 0;
            // Cargar la vista 'administracionModulos.php' desde la carpeta 'Views/configuracion-general/vistas'
            return view('inventario/vistas/administracionUnidades', $data);
        }
    }

    public function modalAdministracionUnidades()
    {
        // Cargar el modelo de unidades de medida
        $unidadesModel = new cat_unidades_medida();

        $data['unidades'] = $unidadesModel->where('flgElimina', 0)->findAll();
        $data['operacion'] = $this->request->getPost('operacion');
        $data['unidadMedidaId'] = $this->request->getPost('unidadMedidaId');

        if($data['operacion'] == 'editar') {
            $mostrarUnidad = new cat_unidades_medida();

            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $mostrarEmpleado
            ->select('cat_unidades_medida.unidadMedida,cat_unidades_medida.abreviaturaUnidadMedida')
            ->where('cat_unidades_medida.flgElimina', 0)
            ->where('cat_unidades_medida.unidadMedidaId', $data['unidadMedidaId'])
            ->first();
        } else {
            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'unidadMedida'               => '',
                'abreviaturaUnidadMedida'    => ''

            ];
        }
        return view('configuracion-general/modals/modalAdministracionUnidades', $data);
    }

}