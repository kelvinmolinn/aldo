<?php 
namespace App\Controllers;
use App\Models\UsuarioLogin;

class Panel extends BaseController{
    public function index(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        }
        $usuario = new UsuarioLogin();
        
        //$data['usuarios'] = $usuario->obtenerDatos();

        return view("Panel/escritorio");
    }
}

?>
