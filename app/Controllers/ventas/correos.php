<?php
namespace App\Controllers\ventas;

use CodeIgniter\Controller;

use App\Models\fel_facturas;
use App\Models\fel_factura_certificacion;
use App\Models\fel_clientes;
use App\Models\fel_cliente_contacto;
use App\Models\fel_facturas_detalle;
use App\Models\fel_facturas_pago;

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
        $email->setMessage('Estimado cliente: '.$datosCliente['cliente'].', le compartimos su DTE: '.$datosCliente['tipoDocumentoDTE'].'. <br><br> Gracias por su compra.<hr><b>Nota: </b>Por favor no conteste a este correo porque fue generado automaticamente.');

        
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








    // Modelos para realizar las consultas
    $facturaModel = new fel_facturas(); 
    $certificacionModel = new fel_factura_certificacion();
    $clienteModel = new fel_clientes();
    $contactoModel = new fel_cliente_contacto();
    $detalleModel = new fel_facturas_detalle();
    $pagoModel = new fel_facturas_pago();

    // Obtener datos de la factura
    $factura = $facturaModel
        ->select('tipoDTEId, fechaEmision, horaEmision')
        ->where('facturaId', $facturaId)
        ->first();

    // Obtener datos de la certificación
    $certificacion = $certificacionModel
        ->select('numeroControl, codigoGeneracion, tipoTransmisionMHId, fhAgrega, selloRecibido')
        ->where('facturaId', $facturaId)
        ->first();

    // Verificar si se encontró la certificación
    if (!$certificacion) {
        return $this->response->setJSON(['error' => 'No se encontró la certificación'], 404);
    }

    // Obtener datos del cliente (receptor)
    $cliente = $clienteModel
        ->select('clienteId, tipoPersonaId, nrcCliente, documentoIdentificacionId, clienteComercial, numDocumentoIdentificacion, cliente, direccionCliente, actividadEconomicaId, tipoContribuyenteId, paisId, paisCiudadId, paisEstadoId')
        ->where('clienteId', function($query) use ($facturaId) {
            $query->select('clienteId')
                  ->from('fel_facturas')
                  ->where('facturaId', $facturaId)
                  ->limit(1);
        })
        ->first();

    // Obtener teléfono y correo electrónico
    $telefono = $contactoModel
        ->select('contactoCliente')
        ->where('clienteId', $cliente['clienteId'])
        ->where('tipoContactoId', 1)
        ->first();

    $correo = $contactoModel
        ->select('contactoCliente')
        ->where('clienteId', $cliente['clienteId'])
        ->where('tipoContactoId', 2)
        ->first();

    // Obtener detalles de la factura (cuerpo del documento)
    $detalles = $detalleModel
        ->select('fel_facturas_detalle.cantidadProducto, fel_facturas_detalle.codigoProducto, fel_facturas_detalle.porcentajeDescuento, fel_facturas_detalle.descuentoTotal, fel_facturas_detalle.tipoItemMHId, fel_facturas_detalle.precioUnitario, fel_facturas_detalle.precioUnitarioIVA, fel_facturas_detalle.ivaTotal, fel_facturas_detalle.ivaUnitario, fel_facturas_detalle.precioUnitarioVenta, fel_facturas_detalle.totalDetalleIVA, inv_productos.producto, inv_productos.descripcionProducto, inv_productos.unidadMedidaId')
        ->join('inv_productos', 'inv_productos.productoId = fel_facturas_detalle.productoId')
        ->where('fel_facturas_detalle.facturaId', $facturaId)
        ->findAll();

    // Calcular los totales del resumen
    $totalGravada = 0;
    $totalIva = 0;
    $totalDescu = 0;
    $porcentajeDescuento = 0;

    foreach ($detalles as $detalle) {
        $totalGravada += $detalle['precioUnitario'] * $detalle['cantidadProducto'];
        $totalIva += $detalle['ivaTotal'];
        $totalDescu += $detalle['descuentoTotal'];
    }

    // Calcular porcentaje de descuento
    if ($totalGravada > 0) {
        $porcentajeDescuento = ($totalDescu / $totalGravada) * 100;
    }

    // Obtener pagos
    $pagos = $pagoModel
        ->select('formaPagoMHId as codigo, totalPago as montoPago')
        ->where('facturaId', $facturaId)
        ->findAll();

    $pagosData = array_map(function($pago) {
        return [
            "codigo" => $pago['codigo'],
            "montoPago" => number_format($pago['montoPago'], 2, '.', ','),
            "referencia" => null,
            "plazo" => "01",
            "periodo" => null
        ];
    }, $pagos);

    $totalEnLetras = $this->numeroALetras($totalGravada + $totalIva - $totalDescu);
    if ($factura['tipoDTEId'] == 1) {
        // JSON para tipoDTEId = 1
        $jsonDTE = array (
            "identificacion" => [
                "version" => 1,
                "ambiente" => "0",
                "tipoDte" => $factura['tipoDTEId'],
                "numeroControl" => $certificacion['numeroControl'],
                "codigoGeneracion" => strtoupper($certificacion['codigoGeneracion']),
                "tipoModelo" => 1,
                "tipoOperacion" => 1,
                "tipoContingencia" => null,
                "motivoContin" => null,
                "fecEmi" => date('Y-m-d', strtotime($factura['fechaEmision'])),
                "horEmi" => date('H:i:s', strtotime($factura['horaEmision'])),
                "tipoMoneda" => "USD"
            ],
            "emisor" => [
                "nit" => "03863624-1",
                "nrc" => "329956-5",
                "nombre" => "BELTRAN. ABIGAIL ELIZABETH",
                "codActividad" => 502,
                "descActividad" => "VENTA AL POR MENOR DE OTROS PRODUCTOS N.C.P",
                "nombreComercial" => "ALDO GAMES STORE",
                "direccion" => [
                    "departamento" => 6,
                    "municipio" => 214,
                    "complemento" => "POLIG. B, RES. LOS ELISEOS #9, SAN SALVADOR, SAN SALVADOR"
                ],
                "telefono" => "79221469",
                "correo" => "aldogamesstore@gmail.com"
            ],
            "receptor" => [
                "tipoDocumento" => $cliente['documentoIdentificacionId'],
                "numDocumento" => $cliente['numDocumentoIdentificacion'],
                "nombre" => $cliente['cliente'],
                "codActividad" => $cliente['actividadEconomicaId'],
                "direccion" => [
                    "departamento" => $cliente['paisCiudadId'],
                    "municipio" => $cliente['paisEstadoId'],
                    "complemento" => $cliente['direccionCliente']
                ],
                "telefono" => $telefono ? $telefono['contactoCliente'] : null,
                "correo" => $correo ? $correo['contactoCliente'] : null,
            ],
            "cuerpoDocumento" => array_map(function($detalle) {
                return [
                    "cantidad" => $detalle['cantidadProducto'],
                    "numeroDocumento" => null,
                    "codigo" => $detalle['codigoProducto'],
                    "codTributo" => null,
                    "tipoItem" => $detalle['tipoItemMHId'],
                    "uniMedida" => $detalle['unidadMedidaId'],
                    "descripcion" => $detalle['producto'],
                    "precioUni" => $detalle['precioUnitario'],
                    "montoDescu" => $detalle['descuentoTotal'],
                    "ventaNoSuj" => 0,
                    "ventaExenta" => 0,
                    "ventaGravada" => $detalle['totalDetalleIVA'],
                    "tributo" => null,
                    "ivaItem" => $detalle['ivaUnitario']
                ];
            }, $detalles),
            "resumen" => [
                "totalNoSuj" => 0,
                "totalExenta" => 0,
                "totalGravada" => number_format($totalGravada, 2, '.', ','),
                "subTotalVentas" => number_format($totalGravada, 2, '.', ','),
                "descuNoSuj" => 0,
                "descuExenta" => 0,
                "descuGravada" => number_format($totalDescu, 2, '.', ','),
                "porcentajeDescuento" => number_format($porcentajeDescuento, 2, '.', ','),
                "totalDescu" => number_format($totalDescu, 2, '.', ','),
                "tributos" => null,
                "subTotal" => number_format($totalGravada, 2, '.', ','),
                "ivaRete1" => 0,
                "reteRenta" => 0,
                "montoTotalOperacion" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
                "totalNoGravado" => 0,
                "totalPagar" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
                "totalLetras" => $totalEnLetras,
                "totalIva" => number_format($totalIva, 2, '.', ','),
                "saldoFavor" => 0,
                "condicionOperacion" => 1,
                "pagos" => $pagosData,
                "numPagoElectronico" => null
            ]
        );
    } elseif ($factura['tipoDTEId'] == 2) {
        // JSON para tipoDTEId = 2
        $jsonDTE = array (
            "identificacion" => [
                "version" => 3,
                "ambiente" => "01",
                "tipoDte" => "02",
                "numeroControl" => $certificacion['numeroControl'],
                "codigoGeneracion" => strtoupper($certificacion['codigoGeneracion']),
                "tipoModelo" => 1,
                "tipoOperacion" => 1,
                "tipoContingencia" => null,
                "motivoContin" => null,
                "fecEmi" => date('Y-m-d', strtotime($factura['fechaEmision'])),
                "horEmi" => date('H:i:s', strtotime($factura['horaEmision'])),
                "tipoMoneda" => "USD"
            ],
            "emisor" => [
                "nit" => "03863624-1",
                "nrc" => "329956-5",
                "nombre" => "BELTRAN. ABIGAIL ELIZABETH",
                "codActividad" => 502,
                "descActividad" => "VENTA AL POR MENOR DE OTROS PRODUCTOS N.C.P",
                "nombreComercial" => "ALDO GAMES STORE",
                "direccion" => [
                    "departamento" => 6,
                    "municipio" => 214,
                    "complemento" => "POLIG. B, RES. LOS ELISEOS #9, SAN SALVADOR, SAN SALVADOR"
                ],
                "telefono" => "79221469",
                "correo" => "aldogamesstore@gmail.com"
            ],
            "receptor" => [
                "nit" => $cliente['numDocumentoIdentificacion'],
                "nrc" => $cliente['nrcCliente'],
                "nombre" => $cliente['cliente'],
                "codActividad" => $cliente['actividadEconomicaId'],
                "descActividad" => "Cría de aves de corral y producción de huevos",
                "nombreComercial" => $cliente['clienteComercial'],
                "direccion" => [
                    "departamento" => $cliente['paisCiudadId'],
                    "municipio" => $cliente['paisEstadoId'],
                    "complemento" => $cliente['direccionCliente']
                ],
                "telefono" => $telefono ? $telefono['contactoCliente'] : null,
                "correo" => $correo ? $correo['contactoCliente'] : null,
            ],
            "cuerpoDocumento" => array_map(function($detalle) {
                return [
                    "numItem" => 1,
                    "tipoItem" => $detalle['tipoItemMHId'],
                    "numeroDocumento" => null,
                    "cantidad" => $detalle['cantidadProducto'],
                    "codigo" => $detalle['codigoProducto'],
                    "uniMedida" => $detalle['unidadMedidaId'],
                    "descripcion" => $detalle['producto'],
                    "precioUni" => $detalle['precioUnitario'],
                    "montoDescu" => $detalle['descuentoTotal'],
                    "ventaGravada" => $detalle['totalDetalleIVA'],
                    "tributos" => ["20"], // Tributos asumidos
                    "psv" => 0,
                    "noGravado" => 0
                ];
            }, $detalles),
            "resumen" => [
                "totalNoSuj" => 0,
                "totalExenta" => 0,
                "totalGravada" => number_format($totalGravada, 2, '.', ','),
                "subTotalVentas" => number_format($totalGravada, 2, '.', ','),
                "descuNoSuj" => 0,
                "descuExenta" => 0,
                "descuGravada" => number_format($totalDescu, 2, '.', ','),
                "porcentajeDescuento" => number_format($porcentajeDescuento, 2, '.', ','),
                "totalDescu" => number_format($totalDescu, 2, '.', ','),
                "tributos" => [
                    [
                        "codigo" => "20",
                        "descripcion" => "Impuesto al Valor Agregado 13%",
                        "valor" => number_format($totalIva, 2, '.', ',')
                    ]
                ],
                "subTotal" => number_format($totalGravada, 2, '.', ','),
                "ivaPerci1" => 17.5,
                "ivaRete1" => 0,
                "reteRenta" => 0,
                "montoTotalOperacion" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
                "totalNoGravado" => 0,
                "totalPagar" => number_format($totalGravada + $totalIva - $totalDescu, 2, '.', ','),
                "totalLetras" => $totalEnLetras,
                "saldoFavor" => 0,
                "condicionOperacion" => 1,
                "pagos" => $pagosData,
                "numPagoElectronico" => null
            ]
        );
    }

        $json = json_encode($jsonDTE, JSON_PRETTY_PRINT);

        //$email->attach('data://text/plain,' . urlencode($json), 'attachment', 'JSON-'.$datosCodGeneracion['codigoGeneracion'].'.json', 'application/json');

        $email->attach($json, 'attachment', 'JSON-'.$datosCodGeneracion['codigoGeneracion'].'.json', 'application/json');

        if ($email->send()) {
            echo 'Correo enviado exitosamente.';
        } else {
            // Muestra el error en caso de fallar el envío
            $data = $email->printDebugger(['headers']);
            print_r($data);
        }
    }

    private function numeroALetras($numero) {
        $formatter = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
        $entero = floor($numero);
        $fraccion = round(($numero - $entero) * 100);

        $texto = $formatter->format($entero);
        $texto .= " dólares";

        if ($fraccion > 0) {
            $texto .= " con " . $formatter->format($fraccion) . " centavos";
        }

        return ucfirst($texto);
    }
    
}