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

        $data['renderVista'] = $this->request->getPost("renderVista");

        if($data['renderVista'] == "") {
            $data['renderVista'] = "Sí";
        } else {
            $data['renderVista'] = "No";
        }

        $data['route'] = $session->get('route');
        $data['tituloVentana'] = $session->get('tituloVentana');
        $data['campos'] = $session->get('camposSession');

        return view('Panel/app', $data);
    }

    public function escritorio(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        }
        $usuario = new UsuarioLogin();

        $camposSession = [
            'renderVista' => 'No'
        ];

        $session->set([
            'route'             => 'escritorio/dashboard',
            'camposSession'     => json_encode($camposSession)
        ]);

        return view('Panel/escritorio');
    }
}

?>
