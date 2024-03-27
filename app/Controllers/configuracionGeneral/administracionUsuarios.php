<?php

namespace App\Controllers\configuracionGeneral;

use CodeIgniter\Controller;
use App\Models\conf_empleados;
use App\Models\conf_roles;
use App\Models\conf_usuarios;
use App\Models\conf_sucursales;
use App\Models\conf_sucursales_empleados;
class AdministracionUsuarios extends Controller
{
    public function index()
    {        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {
            $mostrarUsuario = new conf_empleados();

            $data['empleados'] = $mostrarUsuario
            ->select('conf_empleados.*,conf_usuarios.usuarioId, conf_usuarios.correo, conf_roles.rol')
            ->join('conf_usuarios', 'conf_usuarios.empleadoId = conf_empleados.empleadoId', 'left')
            ->join('conf_roles', 'conf_roles.rolId = conf_usuarios.rolId', 'left')
            ->where('conf_empleados.flgElimina', 0)
            ->findAll();
    
            $sucursalesUsuarios = new conf_sucursales_empleados();
            // Obtener el conteo de sucursales para cada empleado
            foreach ($data['empleados'] as &$empleado) {
                $conteo = $sucursalesUsuarios->where('flgElimina', 0)
                                        ->where('empleadoId', $empleado['usuarioId'])
                                        ->countAllResults();
                $empleado['conteo_sucursales'] = $conteo;
            }
    
    
        return view('configuracion-general/vistas/administracionUsuarios', $data);
        }
    }

    public function mensaje() {
        return "Hola mundo";
    }

    public function modalAdministracionUsuarios()
    {
        // Cargar el modelo de roles
        $rolesModel = new conf_roles();

        $data['roles'] = $rolesModel->where('flgElimina', 0)->findAll();
        $data['operacion'] = $this->request->getPost('operacion');
        $data['usuarioId'] = $this->request->getPost('usuarioId');
        $data['empleadoId'] = $this->request->getPost('empleadoId');

        if($data['operacion'] == 'editar') {
            $mostrarUsuario = new conf_usuarios();

            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $mostrarUsuario
            ->select('conf_empleados.dui,conf_empleados.fechaNacimiento,conf_empleados.primerNombre,conf_empleados.segundoNombre,conf_empleados.primerApellido,conf_empleados.segundoApellido,conf_empleados.sexoEmpleado,conf_usuarios.correo, conf_usuarios.rolId')
            ->join('conf_empleados', 'conf_empleados.empleadoId = conf_usuarios.empleadoId')
            ->join('conf_roles', 'conf_roles.rolId = conf_usuarios.rolId')
            ->where('conf_usuarios.flgElimina', 0)
            ->where('conf_usuarios.usuarioId', $data['usuarioId'])
            ->first();
        } else {
            // formar los campos que estan en la modal (input y select) con el nombre equivalente en la BD
            $data['campos'] = [
                'dui'               => '',
                'rolId'             => '',
                'primerNombre'      => '',
                'segundoNombre'     => '',
                'primerApellido'    => '',
                'segundoApellido'   => '',
                'fechaNacimiento'   => '',
                'sexoEmpleado'      => '',
                'correo'            => ''

            ];
        }
        return view('configuracion-general/modals/modalAdministracionUsuarios', $data);
    }

    public function insertarNuevoUsuario()
    {
        $model = new conf_empleados();
        $modelUsuario = new conf_usuarios();

        $dui = $this->request->getPost('duiUsuario');
        $operacion = $this->request->getPost('operacion');
        $empleadoId = $this->request->getPost('empleadoId');

        if ($model->duiExiste($dui, $empleadoId)) {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'El DUI ya está registrado en la base de datos'
            ]);
        } else {
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
            
            if($operacion == 'editar'){
                $insertEmpleado = $model->update($empleadoId, $data);
            }else{
                $insertEmpleado = $model->insert($data);
                // sobreescribir el cero que viene como empleadoId
                $empleadoId =  $model->insertID();
            }
            if ($insertEmpleado) {
                // Si el insert fue exitoso, devuelve el último ID insertado
                
                $dataUsuarios = [
                    'empleadoId'        => $empleadoId,
                    'rolId'             => $this->request->getPost('selectRol'),
                    'correo'            => $this->request->getPost('correoUsuario'),
                    'clave'             => '123456',
                    'estadoUsuario'     => 'Activo'
                ];

                if($operacion == 'editar'){
                    $insertUsuario = $modelUsuario->update($this->request->getPost('usuarioId'), $dataUsuarios);
                }else{
                    $insertUsuario = $modelUsuario->insert($dataUsuarios);
                }
                    return $this->response->setJSON([
                        'success' => true,
                        'mensaje' => 'Usuario'.($operacion == 'editar' ? 'actualizado' : 'agregado').' correctamente',
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

    public function usuarioSucursal($empleadoId, $nombreCompleto){
        $data['empleadoId'] = $empleadoId;
        $data['nombreCompleto'] = $nombreCompleto;

        $mostrarSucursalUsuario = new conf_sucursales_empleados();

            $data['sucursalUsuario'] = $mostrarSucursalUsuario
            ->select('conf_sucursales.sucursal, conf_sucursales_empleados.sucursalUsuarioId')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = conf_sucursales_empleados.sucursalId')
            ->where('conf_sucursales_empleados.flgElimina', 0)
            ->where('conf_sucursales_empleados.empleadoId',$empleadoId)
            ->findAll(); 

        return view('configuracion-general/vistas/pageUsuariosSucursales', $data);
    }

    public function modalUsuariosSucursales(){
        
        $sucursales = new conf_sucursales();

        $data['sucursales'] = $sucursales
            ->select('conf_sucursales.sucursalId, conf_sucursales.sucursal')
            ->where('conf_sucursales.flgElimina', 0)
            ->whereNotIn('conf_sucursales.sucursalId', function($builder) {
                $builder->select('conf_sucursales_empleados.sucursalId')
                        ->from('conf_sucursales_empleados')
                        ->where('conf_sucursales_empleados.flgElimina', 0)
                        ->where('conf_sucursales_empleados.empleadoId', $this->request->getPost('empleadoId'));
            })
            ->findAll();

        //$data['sucursales'] = $sucursales->where('flgElimina', 0)->findAll();
        $data['empleadoId'] = $this->request->getPost('empleadoId');
        $data['nombreCompleto'] = $this->request->getPost('nombreCompleto');

        return view('configuracion-general/modals/modalUsuariosSucursales',$data);

    }

    public function insertUsuariosSucursal(){
        $asignarSucursal = new conf_sucursales_empleados();

         $data = [
            'sucursalId'     => $this->request->getPost('selectSucursales'),
            'empleadoId'      => $this->request->getPost('empleadoId')
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
    public function eliminarUsuarioSucursal(){
        //$data['sucursalUsuarioId'] = $sucursalUsuarioId;

        $eliminarSucursal = new conf_sucursales_empleados();

        $sucursalUsuarioId = $this->request->getPost('sucursalUsuarioId');
        $data = ['flgElimina' => 1];

        $eliminarSucursal->update($sucursalUsuarioId, $data);

        if($eliminarSucursal) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Sucursal eliminada correctamente'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo eliminar la sucursal'
            ]);
        }
    }
    public function ActivarDesactivar(){
        $desactivarActivar = new conf_empleados();

        $usuarioId = $this->request->getPost('usuarioId');
        $estadoUsuario = $this->request->getPost('estadoUsuario');
        if($estadoUsuario == 'Activo'){
            $data = ['estadoEmpleado' => 'Inactivo'];
        }else{
            $data = ['estadoEmpleado' => 'Activo'];
        }

        $desactivarActivar->update($usuarioId, $data);

        if($desactivarActivar) {
            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Se cambió el estado con éxito'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo cambiar el estado'
            ]);
        }
    }
}
