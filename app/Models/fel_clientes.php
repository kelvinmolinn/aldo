<?php

namespace App\Models;

use CodeIgniter\Model;

class fel_clientes extends Model
{
    protected $table = 'fel_clientes';
    protected $primaryKey = 'clienteId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['clienteId', 'tipoPersonaId', 'nrcCliente', 'documentoIdentificacionId', 'numDocumentoIdentificacion', 'cliente', 'clienteComercial', 'actividadEconomicaId', 'tipoContribuyenteId', 'direccionCliente', 'porcentajeDescuentoMaximo', 'paisId','paisCiudadId','paisEstadoId','estadoCliente', 'fhEdita', 'fhAgrega', 'usuarioIdEdita', 'usuarioIdAgrega', 'flgElimina', 'fhElimina', 'usuarioIdElimina'];

            //Agregar desde aqui

            protected $beforeInsert = ['manageInsertTimestamps'];
            protected $beforeUpdate = ['manageUpdateTimestamps'];
        
            protected function manageInsertTimestamps(array $data)
            {
                // Obtén la sesión y el ID del usuario
                $session = session();
                $usuarioId = $session->get('usuarioId');
        
                // Establecer usuarioIdAgrega
                $data['data']['usuarioIdAgrega'] = $usuarioId;
        
                // Establecer fhAgrega
                $data['data']['fhAgrega'] = date('Y-m-d H:i:s');
        
                return $data;
            }
        
            protected function manageUpdateTimestamps(array $data)
            {
                // Obtén la sesión y el ID del usuario
                $session = session();
                $usuarioId = $session->get('usuarioId');
        
                // Establecer usuarioIdEdita
                $data['data']['usuarioIdEdita'] = $usuarioId;
        
                // Actualiza fhEdita
                $data['data']['fhEdita'] = date('Y-m-d H:i:s');
        
                // Si se hace una eliminación lógica, actualiza fhElimina y usuarioIdElimina
                if (isset($data['data']['flgElimina']) && $data['data']['flgElimina'] == 1) {
                    $data['data']['fhElimina'] = date('Y-m-d H:i:s');
                    $data['data']['usuarioIdElimina'] = $usuarioId; // Establece usuarioIdElimina desde la sesión
                }
        
                return $data;
            }
        
        

}
