<?php 
    namespace App\Models;

    use CodeIgniter\Model;
    
    class UsuarioLogin extends Model
    {
        protected $table = 'conf_usuarios';
        protected $primaryKey = 'usuarioId'; // si el nombre de la clave primaria es diferente

        protected $allowedFields = ['empleadoId','rolId','correo','clave', 'flgEnLinea', 'numIngresos', 'fhUltimoIngreso', 'intentosIngreso','estadoUsuario','flgElimina'];

        protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

        protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
        protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

        public function obtenerUsuario($data)
        {
            // Agregar JOIN a conf_empleados (cambiar cof a conf) 
            $usuario = $this->db->table('vista_usuarios_empleados'); // Establecer la tabla 'conf_usuarios'
            $usuario->where($data);
            $usuario->limit(1);
            return $usuario->get()->getRowArray();
        }
    }
?>