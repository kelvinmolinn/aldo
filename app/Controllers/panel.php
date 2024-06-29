<?php 
namespace App\Controllers;
use App\Models\UsuarioLogin;
use App\Models\conf_usuarios;

class Panel extends BaseController{
    public function index(){        
        $session = session();
        
        if(!$session->get('nombreUsuario')) {
            return view('login');
        } else {            
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
            $data['defaultPass'] = $session->get('defaultPass');

            if($session->get('defaultPass') == "Default") {
                return view('Panel/defaultPassword', $data);
            } else {
                return view('Panel/app', $data);
            }
        }
    }

    public function cambiarClave() {
        $session = session();
        $modelUsuario = new conf_usuarios();

        $usuarioId = $session->get('usuarioId');
        $nuevaClave = $this->request->getPost("nuevaClave");
        $confirmarClave = $this->request->getPost("confirmarClave");

        if($nuevaClave == $confirmarClave) {
            $dataUsuarios = [
                'clave'             => password_hash($nuevaClave, PASSWORD_DEFAULT)
            ];
            $insertUsuario = $modelUsuario->update($usuarioId, $dataUsuarios);

            return $this->response->setJSON([
                'success' => true,
                'mensaje' => 'Contraseña actualizada con éxito'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
        }

    }

    public function escritorio(){        
        $session = session();
    
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
