<?php

namespace App\Controllers;
use App\Models\Usuario;
class Login extends BaseController{

    private $usuario;
    public function index(){
       // $encrypter = \Config\Services::encrypter();
       // $clave = bin2hex($encrypter->encrypt(123));
       // echo  $clave;
        return view("Login/index");
    }

    public function validarIngreso(){
        $correoUsuario = $this->request->getPost("correoUsuario");
        if(filter_var($correoUsuario, FILTER_VALIDATE_EMAIL)){
            $correo = filter_var($correoUsuario, FILTER_SANITIZE_EMAIL);
            $this->usuario = new Usuario();
            $resultadoUsuario = $this->usuario->buscarUsuarioProEmail($correo);
        }else{
            echo "Correo incorrecto";
        }

        if($resultadoUsuario){
            $claveDB = $resultadoUsuario->claveUsuario;

            $clave = $this->request->getPost("claveUsuario");
                if($clave == $claveDB){
                    $data = [
                        "nombreUsuario"     => $resultadoUsuario->nombreUsuario.' '.$resultadoUsuario->apellidoUsuario,
                        "correoUsuario"     => $resultadoUsuario->correoUsuario,
                        "fotoUsuario"       => $resultadoUsuario->fotoUsuario
                    ];
                    session()->set($data);
                    return redirect()->to(base_url().'escritorio');
                    
                }else{
                    $data = ['tipo'=> 'danger', 'mensaje'=> 'Usuario o contraseña incorrecta'];
                    return view('Login/index', $data);
                }
        }else{
            $data = ['tipo'=> 'danger', 'mensaje'=> 'Usuario o contraseña incorrecta'];
            return view('Login/index', $data);
        }
    }

    public function cerrarSession(){
        session()->destroy();
        return redirect()->to(base_url('Login/index'));
    }
    
}


?>