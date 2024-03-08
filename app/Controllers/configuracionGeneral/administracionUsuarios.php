<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\InsertNuevoUsuario;

class AdministracionUsuarios extends Controller
{
    public function index()
    {
        // Aquí puedes cargar cualquier modelo necesario
        // $usuarioModel = new UsuarioModel();
        // $usuarios = $usuarioModel->getUsuarios();

        // Puedes pasar datos a la vista si es necesario
        // $data['usuarios'] = $usuarios;

        // Llamar al método mensaje y pasar su resultado a la vista
        $data['mensaje'] = $this->mensaje();


        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionUsuarios', $data);
    }

    public function mensaje() {
        return "Hola mundo";
    }

    public function modalAdministracionUsuarios()
    {
                // Llamar al método mensaje y pasar su resultado a la vista
                
        // Cargar la modal
        return view('configuracion-general/modals/modalAdministracionUsuarios');
    }

    public function insertarNuevoUsuario()
    {
    
        $model = new InsertNuevoUsuario();

        $dui = $this->request->getPost('duiUsuario');
            
        if ($model->duiExiste($dui)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El DUI ya está registrado en la base de datos'
            ]);
        }
        $data = [
            'dui'               => $this->request->getPost('duiUsuario'),
            'primerNombre'      => $this->request->getPost('primerNombreUsuario'),
            'segundoNombre'     => $this->request->getPost('segundoNombreUsuario'),
            'primerApellido'    => $this->request->getPost('primerApellidoUsuario'),
            'segundoApellido'   => $this->request->getPost('segundoApellidoUsuario'),
            'fechaNacimiento'   => $this->request->getPost('fechaUsuario'),
            'sexoEmpleado'      => $this->request->getPost('selectGenero'),
            'estadoEmpleado'    => "Activo"
            //'contrasena' => password_hash($this->request->getPost('contrasena'), PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        // Insertar datos en la base de datos
        $insertEmpleado = $model->insert($data);
        if ($insertEmpleado) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Usuario insertado correctamente',
                'empleadoId' => $model->insertID()
            ]);
        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo insertar el empleado'
            ]);
        }
    }
}
