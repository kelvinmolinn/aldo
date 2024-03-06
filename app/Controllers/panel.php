<?php 
namespace App\Controllers;
use App\Models\Usuario;

class Panel extends BaseController{
    public function index(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return redirect()->to(base_url('Login/index'));
        }
        $usuario = new Usuario();
    
        $usuarios = $usuario->obtenerDatos();
        
        var_dump($usuarios);
        
        return view("Panel/escritorio", ['usuarios' => $usuarios]);
    }
}

?>
