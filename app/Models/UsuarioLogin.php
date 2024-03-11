<?php 
    namespace App\Models;

    use CodeIgniter\Model;
    
    class UsuarioLogin extends Model
    {
        protected $table = 'conf_usuarios';
    
        public function obtenerUsuario($data)
        {
            // Agregar JOIN a conf_empleados (cambiar cof a conf) 
            $usuario = $this->db->table('conf_usuarios'); // Establecer la tabla 'conf_usuarios'
            $usuario->where($data);
            $usuario->limit(1);
            return $usuario->get()->getRowArray();
        }
    }
?>