<?php

namespace App\Models;

use CodeIgniter\Model;

class fel_facturas_detalle extends Model
{
    protected $table = 'fel_facturas_detalle';
    protected $primaryKey = 'facturaDetalleId'; // si el nombre de la clave primaria es diferente

    protected $allowedFields = ['facturaDetalleId', 'facturaId', 'productoId', 'codigoProducto', 'conceptoProducto', 'tipoItemMHId', 'precioUnitario', 'precioUnitarioIVA', 'cantidadProducto', 'ivaUnitario', 'ivaTotal', 'porcentajeDescuento', 'descuentoTotal', 'totalDetalle', 'totalDetalleIVA', 'fhEdita', 'fhAgrega', 'usuarioIdEdita', 'usuarioIdAgrega', 'flgElimina', 'fhElimina', 'usuarioIdElimina'];

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
        
            public function existeCliente($cliente, $clienteId)
    {
        // Realizar una consulta para verificar si el producto ya existe en la base de datos
        $resultado = $this->where('cliente', $cliente)
        ->where('flgElimina', 0)
        ->whereNotIn('clienteId', [$clienteId])->countAllResults();

        return $resultado > 0; // Devuelve true si el producto existe, false en caso contrario
    }

        

}
