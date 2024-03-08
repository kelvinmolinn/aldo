<?php
namespace App\Controllers;

use App\Models\InsertNuevoUsuario;
use CodeIgniter\Controller;

class nuevoUsuario extends Controller
{
    public function insertarNuevoUsuario()
    {
        $moInsertNuevoUsuariodel = new InsertNuevoUsuario();

        // Validar si el duiUsuario no ha sido registrado
        // Si no ha sido registrado = INSERT
        // else return "Usuario ya fue registrado";
        $empleadoId = 0;
        $data = [
            'dui'               => $this->request->getPost('duiUsuario'),
            'primerNombre'      => $this->request->getPost('primerNombreUsuario'),
            'segundoNombre'     => $this->request->getPost('segundoNombreUsuario'),
            'primerApellido'    => $this->request->getPost('primerApellidoUsuario'),
            'segundoApellido'   => $this->request->getPost('segundoApellidoUsuario'),
            'fechaNacimiento'   => $this->request->getPost('fechaUsuario'),
            'sexoEmpleado'      => $this->request->getPost('selectGenero')
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        $empleadoId = $InsertNuevoUsuario->insert("conf_empleados", $data); // Insertar datos en la base de datos

        if($empleadoId > 0){
            return true;
        }else{
            return "No se realizo insert";
        }
    }
}
