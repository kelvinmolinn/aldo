<?php
namespace App\Controllers\ventas;

use CodeIgniter\Controller;

use App\Models\fel_facturas;
use App\Models\fel_factura_certificacion;

class correos extends Controller
{
    public function enviarCorreo()
    {
        $email = \Config\Services::email();

        $felFacturas = new fel_facturas();
        $felFacturaCertificacion = new fel_factura_certificacion();

        $facturaId = $this->request->getPost('facturaId');
        // Ejemplo: Estimado $cliente, le compartimos su DTE: $tipoDTE por un monto de $totalDTE. 
        // Y con ese facturaId traer el correo y leer sobre cómo adjuntar el PDF y JSON
        $datosCliente = $felFacturas 
                        ->select('fel_cliente_contacto.contactoCliente,fel_clientes.cliente,cat_02_tipo_dte.tipoDocumentoDTE')
                        ->join('fel_cliente_contacto','fel_cliente_contacto.clienteId = fel_facturas.clienteId')
                        ->join('fel_clientes','fel_clientes.clienteId = fel_facturas.clienteId')
                        ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
                        ->where('fel_facturas.flgElimina', 0)
                        ->where('fel_cliente_contacto.flgElimina', 0)
                        ->where('fel_facturas.facturaId', $facturaId)
                        ->where('fel_cliente_contacto.tipoContactoId', 2)
                        ->first();

        $datosCodGeneracion = $felFacturaCertificacion 
                        ->select('codigoGeneracion')
                        ->where('flgElimina', 0)
                        ->where('facturaId', $facturaId)
                        ->first();

        $email->setFrom('aldo.games@textilesbym.com', 'Aldo Games Store');
        //$email->setTo('kelvinmolinn@gmail.com');
        $email->setTo($datosCliente['contactoCliente']);
        $email->setSubject('ALDO GAMES STORE - DTE-'.$datosCodGeneracion['codigoGeneracion']);
        // Generar el contenido del mensaje
        $message = 'Estimado cliente: '.$datosCliente['cliente'].', le compartimos su DTE: '.$datosCliente['tipoDocumentoDTE'].'. <br><br> Gracias por su compra.<hr><b>Nota: </b>Por favor no conteste a este correo porque fue generado automaticamente.';

        // Depuración: Verifica el contenido del mensaje
        echo '<pre>';
        echo htmlspecialchars($message);
        echo '</pre>';

        $email->setMessage($message);

        
       $pdfUrl = base_url('ventas/admin-facturacion/pdf/generate?facturaId=' . $facturaId);

        // Descargar el PDF desde la URL
        $pdfContent = file_get_contents($pdfUrl);

        // Verificar si se descargó correctamente el PDF
        if ($pdfContent !== false) {
            // Adjuntar el PDF descargado al correo
            $email->attach($pdfContent, 'attachment', 'DTE-'.$datosCodGeneracion['codigoGeneracion'].'.pdf', 'application/pdf');
        } else {
            echo 'No se pudo descargar el PDF.';
            return;
        }

        if ($email->send()) {
            echo 'Correo enviado exitosamente.';
        } else {
            // Muestra el error en caso de fallar el envío
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }
}