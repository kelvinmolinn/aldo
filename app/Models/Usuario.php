<?php 
    namespace App\Models;
    use CodeIgniter\Model;

    class Usuario extends Model{
        protected $table = 'usuario';
        protected $primaryKey = 'usuarioId';

        public function buscarUsuarioProEmail($correo){
            $db = db_connect();
            $builder = $db->table($this->table)->where('correoUsuario', $correo)->where('estadoUsuario', 'Activo');
            $resultado = $builder->get();
            return $resultado->getResult() ? $resultado->getResult()[0] : false; 
        }
    }

?>