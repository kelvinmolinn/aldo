<?php 
namespace App\Controllers;
class Usuarios extends Controller
{
    public function index(){
        // Carga la vista de usuarios
        return view('modal/modalAdministracionUsuario');
    }
}
?>