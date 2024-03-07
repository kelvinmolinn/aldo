<?php 
namespace App\Controllers;
use App\Models\Usuario;

class Panel extends BaseController{
    public function index(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        }
        $usuario = new Usuario();
        
        //$data['usuarios'] = $usuario->obtenerDatos();

        return view("Panel/escritorio");
    }
}

?>
