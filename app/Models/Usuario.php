<?php 
    namespace App\Models;
    use CodeIgniter\Model;

    class Usuario extends Model{
        public function obtenerUsuario($data){
            $usuario = $this->db->table('conf_usuarios');
            $usuario->where($data);
            return $usuario->get()->getResultArray();
        }
    }



    /*  protected $table = 'usuario';
        protected $primaryKey = 'usuarioId';

        public function buscarUsuarioProEmail($correo){
            $db = db_connect();
            $builder = $db->table($this->table)->where('correoUsuario', $correo)->where('estadoUsuario', 'Activo');
            $resultado = $builder->get();
            return $resultado->getResult() ? $resultado->getResult()[0] : false; 
        }*/
?>