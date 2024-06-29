<?php

namespace App\Controllers;

use App\Models\UsuarioLogin;
use App\Models\log_usuarios;

class Login extends BaseController
{
    public function index()
    {
        $session = session();

        if($session->get('nombreUsuario')) {
            return redirect()->to(base_url('app'));
        } else {
            // Cargar la vista de inicio de sesión
            return view('login');
        }
    }

    public function validarIngreso()
    {
        $defaultPass = "aldo" . date("Y") . "$";
        // Obtener los datos del formulario de inicio de sesión
        $correoUsuario = $this->request->getPost("correoUsuario");
        $clave = $this->request->getPost("claveUsuario");
    
        // Crear una instancia del modelo Usuario
        $usuarioModel = new UsuarioLogin();
    
        // Obtener los datos del usuario desde la base de datos
        $dataUsuario = $usuarioModel->obtenerUsuario(['correo' => $correoUsuario]); 
    
        // Verificar si se encontró un usuario con el correo proporcionado
        if ($dataUsuario) {
            // Verificar la contraseña
            if (password_verify($clave, $dataUsuario['clave']) && $dataUsuario['estadoUsuario'] == "Activo") {
                $logUsuariosModel = new log_usuarios();

                // Obtener una instancia del objeto UserAgent
                $userAgent = $this->request->getUserAgent();

                // Obtener el navegador y la versión del agente de usuario
                $browser = $userAgent->getBrowser();
                $version = $userAgent->getVersion();

                // Combinar el navegador y la versión para almacenarlos juntos en la base de datos
                $infoNavegador = $browser . ' ' . $version;

                $dataBitacora = [
                    'usuarioId'                 => $dataUsuario['usuarioId'], 
                    'fhIngreso'                 => date('Y-m-d H:i:s'), 
                    'logInterfaces'             => "(".date('d-m-Y H:i:s').") Inició sesión, ", 
                    'ipIngreso'                 => $ipIngreso = $this->request->getIPAddress(), 
                    'infoNavegador'             => $infoNavegador
                ];
                $logUsuarioId = $logUsuariosModel->insert($dataBitacora);

                $updateUsuario = [
                    'flgEnLinea'            => 1,
                    'numIngresos'           => $dataUsuario['numIngresos'] + 1,
                    'fhUltimoIngreso'       => date('Y-m-d H:i:s'),
                    'intentosIngreso'       => 0
                ];
                $usuarioUpdate = $usuarioModel->update($dataUsuario['usuarioId'], $updateUsuario);

                if($clave == $defaultPass) {
                    $flgDefaultPassword = "Default";
                } else {
                    $flgDefaultPassword = "Propia";
                }

                // Iniciar sesión y redirigir al dashboard
                $session = session();
                // Establecer variables de sesión
                $session->set([
                    'usuarioId'     => $dataUsuario['usuarioId'],
                    'nombreUsuario' => $dataUsuario['primerNombre'] . " " . $dataUsuario['primerApellido'],
                    'logUsuarioId'  => $logUsuarioId,
                    'defaultPass'   => $flgDefaultPassword,
                    'route'         => 'escritorio/dashboard'
                    // 'nombreUsuario' => $dataUsuario['primerNombre'] . ' ' . $dataUsuario['primerApellido']
                ]);

                return redirect()->to(base_url('app'));
            } else {
                $respuesta = "";
                if($dataUsuario['estadoUsuario'] == "Bloqueado") {
                    $respuesta = "Usuario bloqueado por alcanzar el número máximo de intentos.";
                } else if($dataUsuario['estadoUsuario'] == "Inactivo") {
                    $respuesta = "Usuario o contraseña incorrecto";
                } else {
                    // Si la contraseña no coincide, redirigir de vuelta al formulario de inicio de sesión con un mensaje de error
                    $intentosLogin = (int)$dataUsuario['intentosIngreso'] + 1;

                    if($intentosLogin >= 5) {
                        // Desactivar el usuario por alcanzar los 5 intentos
                        $data = [
                            "intentosIngreso"   => $intentosLogin,
                            "estadoUsuario"     => "Bloqueado"
                        ];
                        $respuesta = "Usuario bloqueado por alcanzar el número máximo de intentos.";
                    } else {
                        // Sumar un intento
                        $data = [
                            "intentosIngreso"   => $intentosLogin
                        ];
                        $respuesta = "Usuario o contraseña incorrecto";
                    }

                    $usuarioModel->update($dataUsuario['usuarioId'], $data);
                }
                return redirect()->back()->with('mensaje', $respuesta)->with('correo', $correoUsuario);
            }
        } else {
            // Si no se encuentra un usuario, redirigir de vuelta al formulario de inicio de sesión con un mensaje de error
            return redirect()->back()->with('mensaje', 'Usuario o contraseña incorrecto')->with('correo', $correoUsuario);
        }
    }

    public function cerrarSession()
    {
        $session = session();
        $usuarioModel = new UsuarioLogin();
        $logUsuariosModel = new log_usuarios();

        $updateUsuario = [
            'flgEnLinea'        => 0
        ];
        $usuarioUpdate = $usuarioModel->update($session->get('usuarioId'), $updateUsuario);

        $textoLog = "(" . date('d-m-Y H:i:s') . ") Cerró sesión.";
        $updateLog = $logUsuariosModel->registrarLogInterfaces("logInterfaces", $textoLog, $session->get('logUsuarioId'));

        $updateLogSalida = [
            'fhSalida'      => date('Y-m-d H:i:s')
        ];
        $logUpdate = $logUsuariosModel->update($session->get('logUsuarioId'), $updateLogSalida);
        
        /*
            $session->set([
                'usuarioId'     => $dataUsuario['usuarioId'],
                'nombreUsuario' => $dataUsuario['primerNombre'] . " " . $dataUsuario['primerApellido'],
                'logUsuarioId'  => $logUsuarioId,
                'defaultPass'   => $flgDefaultPassword,
                'route'         => 'escritorio/dashboard'
                // 'nombreUsuario' => $dataUsuario['primerNombre'] . ' ' . $dataUsuario['primerApellido']
            ]);
        */

        // Cerrar la sesión y redirigir al inicio de sesión
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
