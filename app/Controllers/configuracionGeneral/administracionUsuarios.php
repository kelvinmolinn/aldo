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

        $mostrarUsuario = new conf_empleados();

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'conf-general/admin-usuarios/index',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('configuracion-general/vistas/administracionUsuarios');
    }

    public function tablaUsuarios() {
        $mostrarUsuario = new conf_empleados();
        $sucursalesUsuarios = new conf_sucursales_empleados();

        $datos = $mostrarUsuario
        ->select('conf_empleados.*,conf_usuarios.usuarioId, conf_usuarios.correo, conf_roles.rol, conf_usuarios.flgEnLinea, conf_usuarios.fhUltimoIngreso, conf_usuarios.estadoUsuario')
        ->join('conf_usuarios', 'conf_usuarios.empleadoId = conf_empleados.empleadoId', 'left')
        ->join('conf_roles', 'conf_roles.rolId = conf_usuarios.rolId', 'left')
        ->where('conf_empleados.flgElimina', 0)
        ->findAll();

        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach($datos as $campos) {
            $nombreCompleto = $campos['primerNombre'].' '.$campos['segundoNombre'].' '.$campos['primerApellido'].' '.$campos['segundoApellido'];
            $empleadoId = $campos['empleadoId'];
            $estadoUsuario = $campos['estadoEmpleado'] ;

            if(!($campos['usuarioId'] == "")) {
                $usuarioId = $campos['usuarioId'];

                if($campos["flgEnLinea"] == 1) {
                    $columnaEnlinea = "
                        <b>En línea: </b> <span class='font-weight-bold text-success'>Conectado</span><br>
                        <b>Hora de conexión: </b> ".date("d/m/Y H:i:s", strtotime($campos['fhUltimoIngreso']))."
                    ";
                } else {
                    $columnaEnlinea = "
                        <b>En línea: </b> <span class='font-weight-bold text-secondary'>Desconectado</span><br>
                        <b>Última conexión: </b> ".date("d/m/Y H:i:s", strtotime($campos['fhUltimoIngreso']))."
                    ";
                }

                $columnaUsuario = "
                    <b>Correo: </b> ".$campos['correo']."  <br>
                    <b>Rol: </b> ".$campos['rol']. " <br>
                    $columnaEnlinea
                ";
            } else {
                // No tiene usuario
                $usuarioId = "0";
                $columnaUsuario = "<b>Usuario no creado</b>";
            }

            if($campos["estadoEmpleado"] == "Activo") {
                if($usuarioId == "0") {              
                    $tituloMensaje = "¿Está seguro que desea desactivar el usuario?";
                    $cuerpoMensaje = "Se bloquearán sus credenciales y no podrá realizar ninguna operación";
                    $classBoton = "danger";
                    $iconoBoton = '<i class="fas fa-ban"></i>';

                    $estadoUsuario = "<p class='font-weight-bold text-success'>Activo</p>";
                } else {
                    if($campos["estadoUsuario"] == "Activo") {
                        $tituloMensaje = "¿Está seguro que desea desactivar el usuario?";
                        $cuerpoMensaje = "Se bloquearán sus credenciales y no podrá realizar ninguna operación";
                        $classBoton = "danger";
                        $iconoBoton = '<i class="fas fa-ban"></i>';
                        $estadoUsuario = "<p class='font-weight-bold text-success'>Activo</p>";
                    } else {
                        $tituloMensaje = "¿Está seguro que desea activar el usuario?";
                        $cuerpoMensaje = "Se habilitarán sus credenciales y establecerá la contraseña predeterminada";
                        $classBoton = "success";
                        $iconoBoton = '<i class="fas fa-check"></i>';
                        $estadoUsuario = "<p class='font-weight-bold text-danger'>Bloqueado</p>";
                    }
                }
            } else {
                $tituloMensaje = "¿Está seguro que desea activar el usuario?";
                $cuerpoMensaje = "Se habilitarán sus credenciales y establecerá la contraseña predeterminada";
                $classBoton = "success";
                $iconoBoton = '<i class="fas fa-check"></i>';

                $estadoUsuario = "<p class='font-weight-bold text-danger'>Inactivo</p>";
            }

            $columna1 = $n;
            $columna2 = '
                <b>Nombre completo: </b> '.$nombreCompleto.'<br>
                <b>DUI: </b> '.$campos["dui"].'
            ';
            $columna3 = $columnaUsuario;
            $columna4 = $estadoUsuario;

            $jsonUsuario = [
                "operacion"             => "editar",
                "usuarioId"             => $usuarioId,
                "empleadoId"            => $empleadoId
            ];

            $columna5 = '
                <button type="button" class="btn btn-primary mb-1" data-toggle="tooltip" data-placement="top" title="Editar" onclick="modalUsuario('.htmlspecialchars(json_encode($jsonUsuario)).');">
                    <i class="fas fa-pencil-alt"></i>
                </button>
            ';

            $jsonSucursales = [
                "empleadoId"            => $empleadoId,
                "nombreCompleto"        => $nombreCompleto
            ];

            $conteoSucursalesEmpleado = $sucursalesUsuarios->where('flgElimina', 0)
                                    ->where('empleadoId', $campos['empleadoId'])
                                    ->countAllResults();
            $columna5 .= '
                <button type="button" class="btn btn-primary mb-1" data-toggle="tooltip" data-placement="top" title="Sucursales" onclick="cambiarInterfaz(`conf-general/admin-usuarios/vista/usuario/sucursal`, '.htmlspecialchars(json_encode($jsonSucursales)).');">
                    <span class="badge badge-light">'.$conteoSucursalesEmpleado.'</span>
                    <i class="fas fa-store"></i>
                </button>
            ';

            if($usuarioId == "0") {
                $estadoUpdate = $campos["estadoEmpleado"];
            } else {
                $estadoUpdate = $campos["estadoUsuario"];
            }

            $jsonEstado = [
                "usuarioId"             => $usuarioId,
                "estadoUsuario"         => $estadoUpdate,
                "empleadoId"            => $empleadoId,
                "mensaje"               => $tituloMensaje,
                "mensaje2"              => $cuerpoMensaje
            ];

            $columna5 .= '
                <button type="button" class="btn btn-'.$classBoton.' mb-1" onclick="ActivarDesactivarUsuario('.htmlspecialchars(json_encode($jsonEstado)).');">
                    '.$iconoBoton.'
                </button>
            ';

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

    public function modalAdministracionUsuarios()
    {
        // Cargar el modelo de roles
        $rolesModel = new conf_roles();

        $data['roles'] = $rolesModel->where('flgElimina', 0)->findAll();
        $data['operacion'] = $this->request->getPost('operacion');
        $data['usuarioId'] = $this->request->getPost('usuarioId');
        $data['empleadoId'] = $this->request->getPost('empleadoId');

        if($data['operacion'] == 'editar') {
            $mostrarEmpleado = new conf_empleados();
            // seleccionar solo los campos que estan en la modal (solo los input y select)
            $data['campos'] = $mostrarEmpleado
            ->select('conf_empleados.dui,conf_empleados.fechaNacimiento,conf_empleados.primerNombre,conf_empleados.segundoNombre,conf_empleados.primerApellido,conf_empleados.segundoApellido,conf_empleados.sexoEmpleado,conf_usuarios.correo, conf_usuarios.rolId')
            ->join('conf_usuarios', 'conf_usuarios.empleadoId = conf_empleados.empleadoId', 'left')
            ->join('conf_roles', 'conf_roles.rolId = conf_usuarios.rolId', 'left')
            ->where('conf_empleados.flgElimina', 0)
            ->where('conf_empleados.empleadoId', $data['empleadoId'])
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
        $usuarioId = $this->request->getPost('usuarioId');
        $crearUsuario = $this->request->getPost('radioCrear');

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
                if($crearUsuario == "si") {
                    $dataUsuarios = [
                        'empleadoId'        => $empleadoId,
                        'rolId'             => $this->request->getPost('selectRol'),
                        'correo'            => $this->request->getPost('correoUsuario'),
                        'clave'             => password_hash('aldo'.date('Y').'$', PASSWORD_DEFAULT),
                        'estadoUsuario'     => 'Activo'
                    ];

                    if($operacion == 'editar') {
                        if($usuarioId == 0) {
                            $insertUsuario = $modelUsuario->insert($dataUsuarios);
                        } else {
                            $insertUsuario = $modelUsuario->update($usuarioId, $dataUsuarios);
                        }
                    }else{
                        $insertUsuario = $modelUsuario->insert($dataUsuarios);
                    }
                } else {
                    // No crear usuario
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

    public function usuarioSucursal(){
        // $empleadoId, $nombreCompleto
        $session = session();
        
        $data['empleadoId'] = $this->request->getPost('empleadoId');
        $data['nombreCompleto'] = $this->request->getPost('nombreCompleto');

        $camposSession = [
            'renderVista'       => 'No',
            'empleadoId'        => $data['empleadoId'],
            'nombreCompleto'    => $data['nombreCompleto']
        ];
        $session->set([
            'route'             => 'conf-general/admin-usuarios/vista/usuario/sucursal',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('configuracion-general/vistas/pageUsuariosSucursales', $data);
    }

    public function tablaUsuariosSucursales() {
        $empleadoId = $this->request->getPost('empleadoId');

        $mostrarSucursalUsuario = new conf_sucursales_empleados();

        $datos = $mostrarSucursalUsuario
            ->select('conf_sucursales.sucursal, conf_sucursales_empleados.sucursalUsuarioId')
            ->join('conf_sucursales', 'conf_sucursales.sucursalId = conf_sucursales_empleados.sucursalId')
            ->where('conf_sucursales_empleados.flgElimina', 0)
            ->where('conf_sucursales_empleados.empleadoId',$empleadoId)
            ->findAll(); 

        // Construye el array de salida
        $output['data'] = array();
        $n = 1; // Variable para contar las filas
        foreach($datos as $campos) {
            $columna1 = $n;
            $columna2 = "<b>Sucursal </b>: " . $campos['sucursal'];

            $columna3 = '
                <button type="button" class="btn btn-danger" onclick="eliminarSucursal(`'.$campos["sucursalUsuarioId"].'`);" data-toggle="tooltip" data-placement="top" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            ';

            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3
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
        $desactivarActivarEmpleado = new conf_empleados();
        $desactivarActivarUsuario = new conf_usuarios();

        $usuarioId = $this->request->getPost('usuarioId');
        $empleadoId = $this->request->getPost('empleadoId');
        $estadoUsuario = $this->request->getPost('estadoUsuario');

        if($estadoUsuario == 'Activo'){
            $data = ['estadoEmpleado' => 'Inactivo'];
            $dataUsuario = ['estadoUsuario' => 'Inactivo', 'clave' => password_hash("CLAVE_TEMPORAL_ACCESO_RESTRINGIDO1234$", PASSWORD_DEFAULT)];
        }else{
            $data = ['estadoEmpleado' => 'Activo'];
            $dataUsuario = ['estadoUsuario' => 'Activo', 'clave' => password_hash('aldo'.date('Y').'$', PASSWORD_DEFAULT)];
        }

        $desactivarActivarEmpleado->update($empleadoId, $data);

        if($desactivarActivarEmpleado) {
            if(!($usuarioId == "")) {
                $desactivarActivarUsuario->update($usuarioId, $dataUsuario);
            } else {
                // No tiene usuario, solo se cambia el estado del empleado
            }

            if($desactivarActivarUsuario) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Se cambió el estado con éxito'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'mensaje' => 'No se pudo cambiar el estado del usuario'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'No se pudo cambiar el estado del empleado'
            ]);
        }
    }
}
