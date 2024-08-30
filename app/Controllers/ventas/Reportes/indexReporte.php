<?php

namespace App\Controllers\ventas\Reportes;
require_once(APPPATH . 'Libraries/fpdf/fpdf.php');

use App\Models\fel_facturas;
use App\Models\fel_facturas_detalle;
use App\Models\fel_clientes;
use App\Models\fel_cliente_contacto;

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
        $this->Image('../assets/plugins/img/aldo_game_store2.png', 11, 10, 30);

        $this->SetX(50);
        $this->SetFillColor(154, 193, 229);
        $this->Cell(150,5,utf8_decode('DOCUMENTO TRIBUTARIO ELECTRONICO'),1,0,'L', true);
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
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

class indexReporte extends Controller
{

    public function generate(){

        $felFactura = new fel_facturas();
        $felFacturaDetalle = new fel_facturas_detalle();
        $felClientes = new fel_clientes();
        $felClienteContacto = new fel_cliente_contacto();

        $pdf = new PDF();

        $facturaId = isset($_GET['facturaId']) ? intval($_GET['facturaId']) : 0;

        $datosDte = $felFactura
            ->select('cat_04_tipo_transmision.tipoTransmision,cat_17_forma_pago.formaPago,DATE_FORMAT(fel_facturas.fechaEmision, "%d/%m/%Y") as fechaEmision ,fel_facturas.horaEmision,fel_factura_certificacion.numeroControl,fel_factura_certificacion.codigoGeneracion,fel_factura_certificacion.selloRecibido,fel_clientes.cliente,fel_clientes.direccionCliente,fel_clientes.numDocumentoIdentificacion,fel_clientes.nrcCliente,cat_19_actividad_economica.actividadEconomica,fel_clientes.clienteId')
            ->join('fel_factura_certificacion','fel_factura_certificacion.facturaId = fel_facturas.facturaId')
            ->join('cat_04_tipo_transmision','cat_04_tipo_transmision.tipoTransmisionMHId = fel_factura_certificacion.tipoTransmisionMHId')
            ->join('fel_facturas_pago','fel_facturas_pago.facturaId = fel_facturas.facturaId')
            ->join('cat_17_forma_pago','cat_17_forma_pago.formaPagoMHId = fel_facturas_pago.formaPagoMHId')
            ->join('fel_clientes','fel_clientes.clienteId = fel_facturas.clienteId')
            ->join('cat_19_actividad_economica','cat_19_actividad_economica.actividadEconomicaId = fel_clientes.actividadEconomicaId')
            ->join('fel_cliente_contacto', 'fel_cliente_contacto.clienteId = fel_clientes.clienteId')
            ->where('fel_facturas.flgElimina', 0)
            ->where('fel_facturas.facturaId', $facturaId)
            ->where('fel_facturas.estadoFactura','Certificado')
            ->where('fel_factura_certificacion.estadoCertificacion','Certificado')
            ->where('fel_factura_certificacion.estadoCertificacion','Contingencia')
            ->first();

        $telefono = $felClienteContacto
            ->select('contactoCliente')
            ->where('clienteId', $datosDte['clienteId'])
            ->where('tipoContactoId', 1)
            ->first();
    
        $correo = $felClienteContacto
            ->select('contactoCliente')
            ->where('clienteId', $datosDte['clienteId'])
            ->where('tipoContactoId', 2)
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

        //$x = 100;
        //$xx = 131;
        // Agregar una página
        $pdf->AddPage();
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

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(10,85);
        $pdf->Cell(190,5,utf8_decode('#'),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(10,95);
        $pdf->Cell(5,5,utf8_decode('#'),0,0,'L');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(20,85);
        $pdf->Cell(190,5,utf8_decode('Cantidad'),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(20,95);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'C');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetXY(40, 86);
        $pdf->MultiCell(20, 3, utf8_decode('Unidad de medida'), 0, 'L');
        
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetXY(40,95); 
        $pdf->Cell(15, 5, utf8_decode('#'), 0, 0, 'R');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(65,85);
        $pdf->Cell(190,5,utf8_decode('Código'),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(65,94);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'R');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(85,85);
        $pdf->Cell(190,5,utf8_decode('Descripción'),0,0,'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(85,94);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'R');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(115,86);
        $pdf->MultiCell(20, 3, utf8_decode('Precio unitario'), 0, 'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(115,94);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'R');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(140,86);
        $pdf->MultiCell(20, 3, utf8_decode('Descuentos por item'), 0, 'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(140,94);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'R');

        $pdf->SetFont('Arial','B',8);
        $pdf->SetXY(170 ,86);
        $pdf->MultiCell(20, 3, utf8_decode('Ventas gravadas'), 0, 'L');

        $pdf->SetFont('Arial','',8);
        $pdf->SetXY(170,94);
        $pdf->Cell(15,5,utf8_decode('#'),0,0,'R');

        //$pdf->Cell(190,5,utf8_decode('Precio unitario'),0,0,'L');

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();
    }
}
