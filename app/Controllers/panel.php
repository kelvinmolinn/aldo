<?php 
namespace App\Controllers;
class Panel extends BaseController{
    public function index(){
        $session = session();

        if(!$session->get('nombreUsuario')) {
            return redirect()->to(base_url('Login/index'));
        }
        return view("Panel/escritorio");
    }
}

?>