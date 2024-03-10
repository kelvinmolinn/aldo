<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\InsertNuevoUsuario;
use App\Models\Roles;
use App\Models\TblUsuarios;

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
        //$data['mensaje'] = $this->mensaje();
        
        $mostrarUsuario = new TblUsuarios();
        /*
        //$mostrarUsuario->join('conf_usuarios', 'conf_usuarios.empleadoId = conf_empleados.empleadoId');
        //$mostrarUsuario->select('conf_empleados','conf_usuarios.correo');

        //$data['empleados'] = $mostrarUsuario->where('flgElimina', 0)->findAll();

        $consultaEmpleados = $mostrarUsuario->select('emp.*, u.correo, r.rol')
            ->from('conf_usuarios as u') // Aquí asignamos el alias 'e' a la tabla 'conf_empleados'
            ->join('conf_empleados as emp', 'emp.empleadoId = u.empleadoId')
            ->join('conf_roles as r', 'r.rolId = u.rolId')
            ->where('u.flgElimina', 0)
            ->get();

        $data['empleados'] = $consultaEmpleados->getResult();

        // Cargar la vista 'administracionUsuarios.php' desde la carpeta 'Views/configuracion-general/vistas'
        return view('configuracion-general/vistas/administracionUsuarios', $data);
        */
        $data['empleados'] = $mostrarUsuario
        ->select('conf_empleados.*, conf_usuarios.correo, conf_roles.rol')
        ->join('conf_empleados', 'conf_empleados.empleadoId = conf_usuarios.empleadoId')
        ->join('conf_roles', 'conf_roles.rolId = conf_usuarios.rolId')
        ->where('conf_usuarios.flgElimina', 0)
        ->findAll();

    return view('configuracion-general/vistas/administracionUsuarios', $data);
    }

    public function mensaje() {
        return "Hola mundo";
    }

    public function modalAdministracionUsuarios()
    {
        // Cargar el modelo de roles
        $rolesModel = new Roles();

        $data['roles'] = $rolesModel->where('flgElimina', 0)->findAll();
        return view('configuracion-general/modals/modalAdministracionUsuarios', $data);
    }

    public function insertarNuevoUsuario()
    {
    
        $model = new InsertNuevoUsuario();
        $modelUsuario = new TblUsuarios();

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
            //'contrasena' => password_hash('aldo'.date('Y').'$', PASSWORD_DEFAULT) // Encriptar contraseña
        ];
        // Insertar datos en la base de datos
        $insertEmpleado = $model->insert($data);
        if ($insertEmpleado) {
            // Si el insert fue exitoso, devuelve el último ID insertado
            $empleadoId =  $insertEmpleado;
            $dataUsuarios = [
                'empleadoId'        => $empleadoId,
                'rolId'             => $this->request->getPost('selectRol'),
                'correo'            => $this->request->getPost('correoUsuario'),
                'clave'             => '123456',
                'estadoUsuario'     => 'Activo'
            ];

            $insertUsuario = $modelUsuario->insert($dataUsuarios);
                
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
