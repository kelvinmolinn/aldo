<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_empleados;
use App\Models\conf_roles;
use App\Models\conf_usuarios;
use App\Models\conf_sucursales;
use App\Models\conf_sucursales_usuario;

class AdministracionUsuarios extends Controller
{
    public function index()
    {        
        $mostrarUsuario = new conf_usuarios();

        $data['empleados'] = $mostrarUsuario
        ->select('conf_empleados.*,conf_usuarios.usuarioId, conf_usuarios.correo, conf_roles.rol')
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
        $rolesModel = new conf_roles();

        $data['roles'] = $rolesModel->where('flgElimina', 0)->findAll();
        return view('configuracion-general/modals/modalAdministracionUsuarios', $data);
    }

    public function insertarNuevoUsuario()
    {
    
        $model = new conf_empleados();
        $modelUsuario = new conf_usuarios();

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

    public function usuarioSucursal(){
        $request = \Config\Services::request(); 

        return view('configuracion-general/vistas/pageUsuariosSucursales', ['request' => $request]);
    }

    public function modalUsuariosSucursales(){
        
        $sucursales = new conf_sucursales();
        $data['sucursales'] = $sucursales->where('flgElimina', 0)->findAll();

        return view('configuracion-general/modals/modalUsuariosSucursales',$data);

    }

    public function insertUsuariosSucursal(){

        $asignarSucursal = new conf_sucursales_usuarios();

         $data = [
            'sucursalId'     => $this->request->getPost('duiUsuario'),
            'usuarioId'      => $this->request->getPost('sucursalId')
        ];
        // Insertar datos en la base de datos
        $insertAsignarSucursal = $asignarSucursal->insert($data);
        if ($insertAsignarSucursal) {
                
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Sucursal asignada correctamente'
            ]);

        } else {
            // Si el insert falló, devuelve un mensaje de error
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo asignar la sucursal al empleado'
            ]);
        }
    }

}
