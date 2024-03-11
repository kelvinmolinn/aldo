<?php

namespace App\Controllers;

use App\Models\UsuarioLogin;

class Login extends BaseController
{
    public function index()
    {
        // Cargar la vista de inicio de sesión
        return view('login');
    }

    public function validarIngreso()
    {
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
            if (password_verify($clave, $dataUsuario['clave'])) {
                // Iniciar sesión y redirigir al dashboard
                $session = session();
                // Establecer variables de sesión
                $session->set([
                    'nombreUsuario' => $dataUsuario['correo']
                    // 'nombreUsuario' => $dataUsuario['primerNombre'] . ' ' . $dataUsuario['primerApellido']
                ]);
                return redirect()->to(base_url('escritorio/dashboard'));
            } else {
                // Si la contraseña no coincide, redirigir de vuelta al formulario de inicio de sesión con un mensaje de error
                return redirect()->back()->with('mensaje', 'Usuario o contraseña incorrecto');
            }
        } else {
            // Si no se encuentra un usuario, redirigir de vuelta al formulario de inicio de sesión con un mensaje de error
            return redirect()->back()->with('mensaje', 'Usuario o contraseña incorrecto');
        }
    }
    

    public function cerrarSession()
    {
        // Cerrar la sesión y redirigir al inicio de sesión
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
}
