<?php

namespace App\Controllers\compras\Reportes;
require_once(APPPATH . 'Libraries/fpdf/fpdf.php');

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

        

        $pdf = new PDF();

        //$x = 100;
        //$xx = 131;
        // Agregar una página
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        
        $pdf->SetY(12);
        $pdf->SetX(100);


        $pdf->Cell(190,10,utf8_decode('Código de generación: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('8929B407-1C01-4165-93EA-1349074AE05F'),0,0,'L');
        
        $pdf->SetY(15);
        $pdf->SetX(100);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Sello recepción: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('2024491690B8E8FA498C82DB72A28E4FC6C1ZLTO'),0,0,'L');

        $pdf->SetY(18);
        $pdf->SetX(100);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Número de control: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('DTE-01-B001P001-000000000000001'),0,0,'L');

        $pdf->SetY(21);
        $pdf->SetX(100);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Fecha de emisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('26/07/2024'),0,0,'L');

        $pdf->SetY(24);
        $pdf->SetX(100);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Tipo de transmisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('Normal'),0,0,'L');

        $pdf->SetY(27);
        $pdf->SetX(100);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Forma de pago: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('Billetes y monedas'),0,0,'L');


        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetY(12);
        $pdf->SetX(50);

        $pdf->Cell(190,10,utf8_decode('NIT: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(56);
        $pdf->Cell(190,10,utf8_decode('0614-010184-002-2'),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetY(15);
        $pdf->SetX(50);

        $pdf->Cell(190,10,utf8_decode('NRC: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(58);
        $pdf->Cell(190,10,utf8_decode('620-3'),0,0,'L');

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->SetY(18);
        $pdf->SetX(50);

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
        $pdf->Cell(190,10,utf8_decode('7105-8593'),0,0,'L');

        $pdf->Image('../assets/plugins/img/QR-Hacienda.jpeg', 170, 25, 30);

        $pdf->SetXY(10,32);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('Abigail Elizabeth Beltran'),0,0,'L');
        $pdf->Ln(4);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('Ventas al por menor de otros productos n.c.p'),0,0,'L');

        $pdf->Ln(23);
        $pdf->SetFont('Arial','B',8);
        $pdf->SetX(10);
        $pdf->SetFillColor(154, 193, 229);
        $pdf->Cell(190,5,utf8_decode('Información del receptor'),1,0,'L', true);

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();
    }
}
