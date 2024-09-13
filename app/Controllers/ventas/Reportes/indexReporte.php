<?php

namespace App\Controllers\ventas\Reportes;
require_once(APPPATH . 'Libraries/fpdf/fpdf.php');

use App\Models\fel_facturas;
use App\Models\fel_facturas_detalle;
use App\Models\fel_clientes;
use App\Models\fel_cliente_contacto;
use App\Models\fel_facturas_complemento;

use CodeIgniter\Controller;
use FPDF;

class PDF extends FPDF
{
// Cabecera de página
    function Header()
    {
        // Logo
        $this->SetFont('Arial','B',8);
        // Movernos a la derecha
        $this->Cell(1);
        // Título
        if ($this->PageNo() == 1) { // Verifica si es la primera página
            // Logo
            $this->Image('../assets/plugins/img/aldo_game_store2.png', 11, 10, 30);
        }
        //190

        // Salto de línea
        $this->Ln(20);
    }

    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

class indexReporte extends Controller
{

    public function generate(){

        $felFactura = new fel_facturas();
        $felFacturaDetalle = new fel_facturas_detalle();
        $felClientes = new fel_clientes();
        $felClienteContacto = new fel_cliente_contacto();
        $felFacturasComplemento = new fel_facturas_complemento();

        $pdf = new PDF();
        $pdf->AliasNbPages();

        $facturaId = isset($_GET['facturaId']) ? intval($_GET['facturaId']) : 0;

        $datosDte = $felFactura
            ->select('cat_04_tipo_transmision.tipoTransmision,cat_17_forma_pago.formaPago,DATE_FORMAT(fel_facturas.fechaEmision, "%d/%m/%Y") as fechaEmision ,fel_facturas.horaEmision,fel_factura_certificacion.numeroControl,fel_factura_certificacion.codigoGeneracion,fel_factura_certificacion.selloRecibido,fel_clientes.cliente,fel_clientes.direccionCliente,fel_clientes.numDocumentoIdentificacion,fel_clientes.nrcCliente,cat_19_actividad_economica.actividadEconomica,fel_clientes.clienteId,cat_02_tipo_dte.tipoDocumentoDTE,cat_02_tipo_dte.tipoDTEId')
            ->join('fel_factura_certificacion','fel_factura_certificacion.facturaId = fel_facturas.facturaId')
            ->join('cat_04_tipo_transmision','cat_04_tipo_transmision.tipoTransmisionMHId = fel_factura_certificacion.tipoTransmisionMHId')
            ->join('fel_facturas_pago','fel_facturas_pago.facturaId = fel_facturas.facturaId')
            ->join('cat_17_forma_pago','cat_17_forma_pago.formaPagoMHId = fel_facturas_pago.formaPagoMHId')
            ->join('fel_clientes','fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('cat_19_actividad_economica','cat_19_actividad_economica.actividadEconomicaId = fel_clientes.actividadEconomicaId')
            ->join('fel_cliente_contacto', 'fel_cliente_contacto.clienteId = fel_clientes.clienteId')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas.facturaId', $facturaId)
            ->where('fel_facturas.estadoFactura','Certificado')
            ->first();

        $telefono = $felClienteContacto
            ->select('contactoCliente')
            ->where('clienteId', $datosDte['clienteId'])
            ->where('tipoContactoId', 1)
            ->where('flgElimina', 0)
            ->first();
    
        $correo = $felClienteContacto
            ->select('contactoCliente')
            ->where('clienteId', $datosDte['clienteId'])
            ->where('tipoContactoId', 2)
            ->where('flgElimina', 0)
            ->first();

        $codGeneracion = $datosDte['codigoGeneracion'];
        $numControl = $datosDte['numeroControl'];
        $sello = $datosDte['selloRecibido'];
        $fechaEmision = $datosDte['fechaEmision'];
        $horaEmision = $datosDte['horaEmision'];
        $formaPago = $datosDte['formaPago'];
        $tipoTransmision = $datosDte['tipoTransmision'];
        $cliente = $datosDte['cliente'];
        $actividad = $datosDte['actividadEconomica'];
        $direccionCliente = $datosDte['direccionCliente'];
        $numeroIdentificacion = $datosDte['numDocumentoIdentificacion'];
        $nrc = $datosDte['nrcCliente'];

        
        $telefonoCliente = isset($telefono['contactoCliente']) ? $telefono['contactoCliente'] : '';
        $correoCliente = $correo['contactoCliente'];


        $datosDteProductos = $felFacturaDetalle
        ->select('fel_facturas_detalle.facturaId, fel_facturas_detalle.codigoProducto,fel_facturas_detalle.cantidadProducto,cat_14_unidades_medida.abreviaturaUnidadMedida,inv_productos.producto,fel_facturas_detalle.precioUnitario,fel_facturas_detalle.porcentajeDescuento,fel_facturas_detalle.precioUnitarioIVA,fel_facturas_detalle.totalDetalleIVA')
        ->join('inv_productos','inv_productos.productoId = fel_facturas_detalle.productoId')
        ->join('cat_14_unidades_medida','cat_14_unidades_medida.unidadMedidaId = inv_productos.unidadMedidaId')
        ->where('fel_facturas_detalle.flgElimina', 0)
        ->where('fel_facturas_detalle.facturaId', $facturaId)
        ->findAll();

        $datosDteComplementos = $felFacturasComplemento
        ->select('complementoFactura')
        ->where('flgElimina', 0)
        ->where('facturaId', $facturaId)
        ->first();

        $complemento =  isset($datosDteComplementos['complementoFactura']) ? $datosDteComplementos['complementoFactura'] : '';
        
        //$x = 100;
        //$xx = 131;
        // Agregar una página
        $pdf->AddPage();

        $pdf->SetXY(50, 10);
        $pdf->SetFillColor(154, 193, 229);
        $pdf->Cell(150,5,utf8_decode('DOCUMENTO TRIBUTARIO ELECTRONICO: ' . $datosDte['tipoDocumentoDTE']),1,0,'L', true);

        $pdf->SetFont('Arial', 'B', 8);
        
        $pdf->SetXY(100,12);


        $pdf->Cell(190,10,utf8_decode('Código de generación: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($codGeneracion),0,0,'L');
        
        $pdf->SetXY(100,15);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Sello recepción: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($sello),0,0,'L');

        $pdf->SetXY(100,18);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Número de control: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($numControl),0,0,'L');

        $pdf->SetXY(100,21);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Fecha de emisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($fechaEmision),0,0,'L');

        $pdf->SetXY(100,24);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Hora de emisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($horaEmision),0,0,'L');

        $pdf->SetXY(100,27);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Tipo de transmisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($tipoTransmision),0,0,'L');

        $pdf->SetXY(100,30);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Forma de pago: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode($formaPago),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(50,12);

        $pdf->Cell(190,10,utf8_decode('NIT: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(56);
        $pdf->Cell(190,10,utf8_decode('03863624-1'),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(50,15);

        $pdf->Cell(190,10,utf8_decode('NRC: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(58);
        $pdf->Cell(190,10,utf8_decode('329956-5'),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(50,18);

        $pdf->Cell(190,10,utf8_decode('Dirección: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(64, 22);
        $pdf->MultiCell(35,3,utf8_decode('Res. Los Eliseos #9, San Salvador'),0,'L');

        // Si la altura es dinámica y se usa multicell, no se hace $altura += 5 por ejemplo.
        // Sino que, la altura después de un multicell se obtiene $altura = $pdf->GetY();

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(50, 25);

        $pdf->Cell(190,10,utf8_decode('Teléfono: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(63);
        $pdf->Cell(190,10,utf8_decode('7922-1469'),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(50, 28);

        $pdf->Cell(190,10,utf8_decode('Correo: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(61);
        $pdf->Cell(190,10,utf8_decode('aldogamesstore@gmail.com'),0,0,'L');

        $pdf->Image('../assets/plugins/img/QR-Hacienda.jpeg', 170, 25, 30);

        $pdf->SetXY(10,35);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('Abigail Elizabeth Beltran'),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('Ventas al por menor de otros productos n.c.p'),0,0,'L');

        $pdf->Ln(23);
        $pdf->SetFont('Arial','B',8);

        $pdf->SetXY(10,58);
        $pdf->SetFillColor(154, 193, 229);
        $pdf->Cell(190,5,utf8_decode('Información del receptor'),1,0,'L', true);
        
        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(10,63);
        $pdf->Cell(190,5,utf8_decode('Nombre: '),0,0,'L');
        $pdf->SetFont('Arial','',8);

        $pdf->SetXY(22,63);
        $pdf->Cell(190,5,utf8_decode($cliente),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(10,67);
        $pdf->Cell(190,5,utf8_decode('Actividad: '),0,0,'L');
        $pdf->SetFont('Arial','',8);

        $pdf->SetXY(24,67);
        $pdf->Cell(190,5,utf8_decode($actividad),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(10,71);
        $pdf->Cell(190,5,utf8_decode('Dirección: '),0,0,'L');
        $pdf->SetFont('Arial','',8);

        $pdf->SetXY(24,71);
        $pdf->Cell(190,5,utf8_decode($direccionCliente),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(100,63);
        $pdf->Cell(190,5,utf8_decode('N° de documento: '),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(125,63);
        $pdf->Cell(190,5,utf8_decode($numeroIdentificacion),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(100,67);
        $pdf->Cell(190,5,utf8_decode('NRC: '),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(108,67);
        $pdf->Cell(190,5,utf8_decode($nrc),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(100,71);
        $pdf->Cell(190,5,utf8_decode('Teléfono: '),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(114,71);
        $pdf->Cell(190,5,utf8_decode($telefonoCliente),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(100,75);
        $pdf->Cell(190,5,utf8_decode('Correo: '),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(111,75);
        $pdf->Cell(190,5,utf8_decode($correoCliente),0,0,'L');

        $pdf->Ln(23);
        $pdf->SetFont('Arial','B',8);

        $pdf->SetXY(10,80);
        $pdf->SetFillColor(154, 193, 229);
        $pdf->Cell(190,5,utf8_decode('Cuerpo del documento'),1,0,'L', true);

        //AQUI INICIA
        if($datosDte['tipoDTEId'] == 1){
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(10,85);
            $pdf->Cell(10,6,utf8_decode('#'),0,0,'C');

            $pdf->SetXY(20,85);
            $pdf->Cell(20,6,utf8_decode('Cantidad'),0,0,'C');

            $pdf->SetXY(40, 85);
            $pdf->MultiCell(20, 3, utf8_decode('Unidad de medida'), 0, 'C');
            
            $pdf->SetXY(60,85);
            $pdf->Cell(20,6,utf8_decode('Código'),0,0,'C');

            $pdf->SetXY(80,85);
            $pdf->Cell(35,6,utf8_decode('Descripción'),0,0,'C');

            $pdf->SetXY(115,85);
            $pdf->MultiCell(25, 6, utf8_decode('Precio unitario'), 0, 'C');

            $pdf->SetXY(140,85);
            $pdf->MultiCell(30, 6, utf8_decode('Descuentos por item'), 0, 'C');

            $pdf->SetXY(170 ,85);
            $pdf->MultiCell(30, 6, utf8_decode('Ventas gravadas'), 0, 'C');

            $pdf->SetFont('Arial','',8);

            $alturaDetalle = 95;
            $espacioDisponible = 270; // Altura total disponible en la página (restando encabezados y pies de página)
            $alturaFila = 5;  // Altura de cada fila
            $n = 0;

            $ventasTotales = 0;
            $ivaRetenido = 0;
            $subTotal = 0;
            $montoTotalOperacion = 0;
            $totaPagar = 0;
            //for ($i=0; $i < 40; $i++) { 
                foreach($datosDteProductos AS $datosProductos) {             
                    $n++;
                    if ($alturaDetalle + $alturaFila > $espacioDisponible) {
                        $pdf->AddPage();
                        
                        // Volver a imprimir los títulos de las columnas
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetXY(10,10); // Ajusta la posición inicial en la nueva página
                        $pdf->Cell(190,5,utf8_decode('Cuerpo del documento'),1,0,'L', true);
                        
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetXY(10,15);
                        $pdf->Cell(10,6,utf8_decode('#'),0,0,'C');
                        
                        $pdf->SetXY(20,15);
                        $pdf->Cell(20,6,utf8_decode('Cantidad'),0,0,'C');
                        
                        $pdf->SetXY(40, 15);
                        $pdf->MultiCell(20, 3, utf8_decode('Unidad de medida'), 0, 'C');
                        
                        $pdf->SetXY(60,15);
                        $pdf->Cell(20,6,utf8_decode('Código'),0,0,'C');
                        
                        $pdf->SetXY(80,15);
                        $pdf->Cell(35,6,utf8_decode('Descripción'),0,0,'C');
                        
                        $pdf->SetXY(115,15);
                        $pdf->MultiCell(25, 6, utf8_decode('Precio unitario'), 0, 'C');
                        
                        $pdf->SetXY(140,15);
                        $pdf->MultiCell(30, 6, utf8_decode('Descuentos por item'), 0, 'C');
                        
                        $pdf->SetXY(170,15);
                        $pdf->MultiCell(30, 6, utf8_decode('Ventas gravadas'), 0, 'C');
        
                        // Reiniciar la posición de `alturaDetalle` para la nueva página
                        $alturaDetalle = 25;  // Ajustar según el espacio que ocupan los títulos
                        $pdf->SetFont('Arial','',8);
                    }
                    
                    $ventasTotales += $datosProductos['totalDetalleIVA'];
                    $ivaRetenido = 0.00;
                    $subTotal += $datosProductos['totalDetalleIVA'];
                    $montoTotalOperacion += $datosProductos['totalDetalleIVA'];
                    $totaPagar += $datosProductos['totalDetalleIVA'];


                    $pdf->SetXY(10,$alturaDetalle);
                    $pdf->Cell(10,5,utf8_decode($n),0,0,'L');
        
                    $pdf->SetXY(20,$alturaDetalle);
                    $pdf->Cell(20,5,utf8_decode($datosProductos['cantidadProducto']),0,0,'C');
        
                    $pdf->SetXY(40,$alturaDetalle); 
                    $pdf->Cell(20, 5, utf8_decode($datosProductos['abreviaturaUnidadMedida']), 0, 0, 'C');
        
                    $pdf->SetXY(60,$alturaDetalle);
                    $pdf->Cell(20,5,utf8_decode($datosProductos['codigoProducto']),0,0,'C');
        
                    $pdf->SetXY(80,$alturaDetalle);
                    $pdf->Cell(35,5,utf8_decode($datosProductos['producto']),0,0,'C');
        
                    $pdf->SetXY(115,$alturaDetalle);
                    $pdf->Cell(25,5,utf8_decode(number_format($datosProductos['precioUnitarioIVA'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(115,$alturaDetalle);
                    $pdf->Cell(25,5,utf8_decode("$"),0,0,'L');
        
                    $pdf->SetXY(140,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode(number_format($datosProductos['porcentajeDescuento'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(140,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode("%"),0,0,'L');

                    $pdf->SetXY(170,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode(number_format($datosProductos['totalDetalleIVA'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(170,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode("$"),0,0,'L');
                    
                    // Incrementar altura para la siguiente fila
                    $alturaDetalle += $alturaFila;
                }
            //}

            if($alturaDetalle + 30 > $espacioDisponible) {
                $pdf->AddPage();
                $alturaDetalle = 15;
            } 
            $pdf->Line(10, $alturaDetalle, 200, $alturaDetalle);        

            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(10,$alturaDetalle);
            $pdf->MultiCell(70, 6, utf8_decode('Complemento:'), 0, 'L');

            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(30,$alturaDetalle);
            $pdf->MultiCell(50,6, utf8_decode($complemento), 0, 'L');

            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Total operaciones'), 0, 0, 'L');

            $pdf->SetXY(155,$alturaDetalle);
            $pdf->Cell(20,5, utf8_decode('Gravadas:'), 0, 0, 'L');

            $alturaDetalle += 10;
            
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Sumatoria de venta:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($ventasTotales, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('IVA retenido:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('0.00'),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            
            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Subtotal:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($subTotal, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');
            
            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(40,5, utf8_decode('Monto total de la operación:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($montoTotalOperacion, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            $alturaDetalle += 5;
            $pdf->Line(115, $alturaDetalle, 200, $alturaDetalle);
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Total a pagar:'), 0, 0, 'L');
            
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($totaPagar, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

        }else if($datosDte['tipoDTEId'] == 2){
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(10,85);
            $pdf->Cell(10,6,utf8_decode('#'),0,0,'C');

            $pdf->SetXY(20,85);
            $pdf->Cell(20,6,utf8_decode('Cantidad'),0,0,'C');

            $pdf->SetXY(40, 85);
            $pdf->MultiCell(20, 3, utf8_decode('Unidad de medida'), 0, 'C');
            
            $pdf->SetXY(60,85);
            $pdf->Cell(20,6,utf8_decode('Código'),0,0,'C');

            $pdf->SetXY(80,85);
            $pdf->Cell(35,6,utf8_decode('Descripción'),0,0,'C');

            $pdf->SetXY(115,85);
            $pdf->MultiCell(25, 6, utf8_decode('Precio unitario'), 0, 'C');

            $pdf->SetXY(140,85);
            $pdf->MultiCell(30, 6, utf8_decode('Descuentos por item'), 0, 'C');

            $pdf->SetXY(170 ,85);
            $pdf->MultiCell(30, 6, utf8_decode('Ventas gravadas'), 0, 'C');

            $pdf->SetFont('Arial','',8);

            $alturaDetalle = 95;
            $espacioDisponible = 270; // Altura total disponible en la página (restando encabezados y pies de página)
            $alturaFila = 5;  // Altura de cada fila
            $n = 0;

            $ventasTotales = 0;
            $ivaRetenido = 0;
            $subTotal = 0;
            $montoTotalOperacion = 0;
            $totaPagar = 0;
            //for ($i=0; $i < 40; $i++) { 
                foreach($datosDteProductos AS $datosProductos) {             
                    $n++;
                    if ($alturaDetalle + $alturaFila > $espacioDisponible) {
                        $pdf->AddPage();
                        
                        // Volver a imprimir los títulos de las columnas
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetXY(10,10); // Ajusta la posición inicial en la nueva página
                        $pdf->Cell(190,5,utf8_decode('Cuerpo del documento'),1,0,'L', true);
                        
                        $pdf->SetFont('Arial','B',8);
                        $pdf->SetXY(10,15);
                        $pdf->Cell(10,6,utf8_decode('#'),0,0,'C');
                        
                        $pdf->SetXY(20,15);
                        $pdf->Cell(20,6,utf8_decode('Cantidad'),0,0,'C');
                        
                        $pdf->SetXY(40, 15);
                        $pdf->MultiCell(20, 3, utf8_decode('Unidad de medida'), 0, 'C');
                        
                        $pdf->SetXY(60,15);
                        $pdf->Cell(20,6,utf8_decode('Código'),0,0,'C');
                        
                        $pdf->SetXY(80,15);
                        $pdf->Cell(35,6,utf8_decode('Descripción'),0,0,'C');
                        
                        $pdf->SetXY(115,15);
                        $pdf->MultiCell(25, 6, utf8_decode('Precio unitario'), 0, 'C');
                        
                        $pdf->SetXY(140,15);
                        $pdf->MultiCell(30, 6, utf8_decode('Descuentos por item'), 0, 'C');
                        
                        $pdf->SetXY(170,15);
                        $pdf->MultiCell(30, 6, utf8_decode('Ventas gravadas'), 0, 'C');
        
                        // Reiniciar la posición de `alturaDetalle` para la nueva página
                        $alturaDetalle = 25;  // Ajustar según el espacio que ocupan los títulos
                        $pdf->SetFont('Arial','',8);
                    }
                    
                    $ventasTotales += $datosProductos['totalDetalleIVA'];
                    $ivaRetenido = 0.00;
                    $subTotal += $datosProductos['totalDetalleIVA'];
                    $montoTotalOperacion += $datosProductos['totalDetalleIVA'];
                    $totaPagar += $datosProductos['totalDetalleIVA'];


                    $pdf->SetXY(10,$alturaDetalle);
                    $pdf->Cell(10,5,utf8_decode($n),0,0,'L');
        
                    $pdf->SetXY(20,$alturaDetalle);
                    $pdf->Cell(20,5,utf8_decode($datosProductos['cantidadProducto']),0,0,'C');
        
                    $pdf->SetXY(40,$alturaDetalle); 
                    $pdf->Cell(20, 5, utf8_decode($datosProductos['abreviaturaUnidadMedida']), 0, 0, 'C');
        
                    $pdf->SetXY(60,$alturaDetalle);
                    $pdf->Cell(20,5,utf8_decode($datosProductos['codigoProducto']),0,0,'C');
        
                    $pdf->SetXY(80,$alturaDetalle);
                    $pdf->Cell(35,5,utf8_decode($datosProductos['producto']),0,0,'C');
        
                    $pdf->SetXY(115,$alturaDetalle);
                    $pdf->Cell(25,5,utf8_decode(number_format($datosProductos['precioUnitarioIVA'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(115,$alturaDetalle);
                    $pdf->Cell(25,5,utf8_decode("$"),0,0,'L');
        
                    $pdf->SetXY(140,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode(number_format($datosProductos['porcentajeDescuento'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(140,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode("%"),0,0,'L');

                    $pdf->SetXY(170,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode(number_format($datosProductos['totalDetalleIVA'], 2, '.', ',')),0,0,'R');
                    $pdf->SetXY(170,$alturaDetalle);
                    $pdf->Cell(30,5,utf8_decode("$"),0,0,'L');
                    
                    // Incrementar altura para la siguiente fila
                    $alturaDetalle += $alturaFila;
                }
            //}

            if($alturaDetalle + 30 > $espacioDisponible) {
                $pdf->AddPage();
                $alturaDetalle = 15;
            } 
            $pdf->Line(10, $alturaDetalle, 200, $alturaDetalle);        

            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(10,$alturaDetalle);
            $pdf->MultiCell(70, 6, utf8_decode('Complemento:'), 0, 'L');

            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(30,$alturaDetalle);
            $pdf->MultiCell(50,6, utf8_decode($complemento), 0, 'L');

            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Total operaciones'), 0, 0, 'L');

            $pdf->SetXY(155,$alturaDetalle);
            $pdf->Cell(20,5, utf8_decode('Gravadas:'), 0, 0, 'L');

            $alturaDetalle += 10;
            
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Sumatoria de venta:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($ventasTotales, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('IVA retenido:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('0.00'),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            $alturaDetalle += 5;

            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Impuesto al Valos Agregado 13%:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('0.00'),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            
            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Subtotal:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($subTotal, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');
            
            $alturaDetalle += 5;
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(40,5, utf8_decode('Monto total de la operación:'), 0, 0, 'L');
            
            $pdf->SetFont('Arial','',8);
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($montoTotalOperacion, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');

            $alturaDetalle += 5;
            $pdf->Line(115, $alturaDetalle, 200, $alturaDetalle);
            
            $pdf->SetFont('Arial','B',8);
            $pdf->SetXY(115,$alturaDetalle);
            $pdf->Cell(30,5, utf8_decode('Total a pagar:'), 0, 0, 'L');
            
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode(number_format($totaPagar, 2, '.', ',')),0,0,'R');
            $pdf->SetXY(170,$alturaDetalle);
            $pdf->Cell(30,5,utf8_decode('$'),0,0,'L');
        }else{

        }

        
        // Despues dibujar los totales  aqui abajo
        //$pdf->Cell(190,5,utf8_decode('Precio unitario'),0,0,'L');

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();
    }
}
