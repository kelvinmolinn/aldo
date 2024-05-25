<?php

namespace App\Models;

use CodeIgniter\Model;

class vista_usuarios_empleados extends Model
{
    protected $table = 'vista_usuarios_empleados'; // Nombre de la vista
    protected $primaryKey = 'usuarioId'; // Clave primaria

    // Definir los campos que se pueden utilizar
    protected $allowedFields = [
        'usuarioId', 
        'rolId', 
        'empleadoId', 
        'correo', 
        'primerNombre', 
        'segundoNombre', 
        'primerApellido', 
        'segundoApellido', 
        'dui', 
        'sexoEmpleado', 
        'estadoEmpleado', 
        'estadoUsuario', 
        'fechaNacimiento'
    ];

    // Si quieres que los resultados sean devueltos como un array asociativo
    protected $returnType = 'array';

    // Deshabilitar las marcas de tiempo ya que una vista no las tiene
    public $timestamps = false;
}
