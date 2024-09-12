<?php
namespace App\Controllers\ventas;

use CodeIgniter\Controller;

class correos extends Controller
{
    public function enviarCorreo()
    {
        $email = \Config\Services::email();

        // Recibir con POST el facturaId
        // Obtener el clienteId y traer su correo
        // En setSubject colocar el codGeneracion del DTE
        // En setMessage colocar el nombre del cliente y el monto total del DTE
        // Ejemplo: Estimado $cliente, le compartimos su DTE: $tipoDTE por un monto de $totalDTE. 
        // Y con ese facturaId traer el correo y leer sobre cómo adjuntar el PDF y JSON

        $email->setFrom('aldo.games@textilesbym.com', 'Aldo Games Store');
        $email->setTo('kelvinmolinn@gmail.com');
        $email->setSubject('DTE codGeneracion');
        $email->setMessage('Estimado cliente, le compartimos su DTE. <br><br> Gracias por su compra.<hr><b>Nota: </b>Por favor no conteste a este correo porque fue generado automaticamente.');

        if ($email->send()) {
            echo 'Correo enviado exitosamente.';
        } else {
            // Muestra el error en caso de fallar el envío
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
}