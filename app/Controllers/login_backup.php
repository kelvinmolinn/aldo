<?php

namespace App\Controllers;
use App\Models\Usuario;
class Login extends BaseController{

    public function index(){
       // $encrypter = \Config\Services::encrypter();
       // $clave = bin2hex($encrypter->encrypt(123));
       // echo  $clave;
        return view('login');
    }

    public function validarIngreso(){
        //$bd = \Config\Database::connect();

        $correoUsuario = $this->request->getPost("correoUsuario");
        $clave = $this->request->getPost("claveUsuario");
        $usuario = new Usuario();
    
        $dataUsuario = $usuario->obtenerUsuario(['correo' => $correoUsuario]); 
    
        //if(count($dataUsuario) > 0 && password_verify($clave, $dataUsuario[0]['clave'])){
        if(count($dataUsuario) > 0) {
          
            //if(password_verify("123", $dataUsuario[0]['clave'])) {
                $session = session();
                //return view("Panel/escritorio");
                return redirect()->to(base_url('dashboard'));
            //} else {
               // return view('Login/index');
            //}
        }else{
            
            //$data = ['tipo'=> 'danger', 'mensaje'=> 'Usuario o contraseña incorrecta'];
            //return redirect()->to(base_url().'../app/Views/Panel/escritorio.php');
            //var_dump($dataUsuario);
            //return view('Login/index');
            return redirect()->back()->with('mensaje', 'Usuario o contraseña incorrecto');
        }
        /*if(filter_var($correoUsuario, FILTER_VALIDATE_EMAIL)){
            $correo = filter_var($correoUsuario, FILTER_SANITIZE_EMAIL);
            $this->usuario = new Usuario();
            $resultadoUsuario = $this->usuario->buscarUsuarioProEmail($correo);
        }else{
            echo "Correo incorrecto";
        }*/

        /*if($resultadoUsuario){
            $claveDB = $resultadoUsuario->claveUsuario;

            $clave = $this->request->getPost("claveUsuario");
                if($clave == $claveDB){*/
                    /*$data = [
                        "nombreUsuario"     => $resultadoUsuario->nombreUsuario.' '.$resultadoUsuario->apellidoUsuario,
                        "correoUsuario"     => $resultadoUsuario->correoUsuario,
                        "fotoUsuario"       => $resultadoUsuario->fotoUsuario
                    ];*/
                    /*session()->set($data);
                    return redirect()->to(base_url().'escritorio');*/
                    
               // }else{
                    /*$data = ['tipo'=> 'danger', 'mensaje'=> 'Usuario o contraseña incorrecta'];
                    return view('Login/index', $data);*/
               // }
       // }else{
            /*$data = ['tipo'=> 'danger', 'mensaje'=> 'Usuario o contraseña incorrecta'];
            return view('Login/index', $data);*/
       // }
    }

    public function cerrarSession(){
        session()->destroy();
        return redirect()->to(base_url('login'));
    }
    
}


?>