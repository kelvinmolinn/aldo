<?php

namespace App\Models;

use CodeIgniter\Model;

class conf_empleados extends Model
{
    protected $table = 'conf_empleados';
    protected $primaryKey = 'empleadoId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['dui','primerNombre','segundoNombre','primerApellido','segundoApellido','fechaNacimiento','sexoEmpleado','estadoEmpleado']; // Campos permitidos para la inserción


    protected $useTimestamps = true; // Utiliza campos de timestamp para created_at y updated_at

    protected $createdField  = 'fhAgrega'; // Campo creado automáticamente al insertar
    protected $updatedField  = 'fhEdita'; // Campo actualizado automáticamente al actualizar

    public function duiExiste($dui)
    {
        // Realizar una consulta para verificar si el DUI ya existe en la base de datos
        $resultado = $this->where('dui', $dui)->countAllResults();

        return $resultado > 0; // Devuelve true si el DUI existe, false en caso contrario
    }

}


