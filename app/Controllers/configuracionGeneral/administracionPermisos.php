<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;

class AdministracionPermisos extends Controller
{
    public function configuracionModulos()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionModulos');
    }
    public function modalnuevoModulo()
    {
        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/modals/modalAdministracionModulos');
    }

}
