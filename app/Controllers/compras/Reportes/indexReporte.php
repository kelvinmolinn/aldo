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
        $this->Image('../assets/plugins/img/aldo_game_store2.png', 15, 18, 30);

        $this->SetX(100);
        $this->SetFillColor(154, 193, 229);
        $this->Cell(100,5,utf8_decode('DOCUMENTO TRIBUTARIO ELECTRONICO'),1,0,'L', true);
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

        $x = 100;
        $xx = 131;
        // Agregar una página
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 8);
        
        $pdf->SetY(12);
        $pdf->SetX($x);


        $pdf->Cell(190,10,utf8_decode('Código de generación: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('8929B407-1C01-4165-93EA-1349074AE05F'),0,0,'L');
        
        $pdf->SetY(15);
        $pdf->SetX($x);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Sello recepción: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('2024491690B8E8FA498C82DB72A28E4FC6C1ZLTO'),0,0,'L');

        $pdf->SetY(18);
        $pdf->SetX($x);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Número de control: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('DTE-01-B001P001-000000000000001'),0,0,'L');

        $pdf->SetY(25);
        $pdf->SetX($x);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Fecha de emisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('26/07/2024'),0,0,'L');

        $pdf->SetY(28);
        $pdf->SetX($x);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Tipo de transmisión: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('Normal'),0,0,'L');

        $pdf->SetY(31);
        $pdf->SetX($x);

        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(190,10,utf8_decode('Forma de pago: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX($xx);
        $pdf->Cell(190,10,utf8_decode('Billetes y monedas'),0,0,'L');

        //$pdf->Image('../assets/plugins/img/QR-Hacienda.jpeg', 171, 16, 30);

        $pdf->SetY(48);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('Abigail Elizabeth Beltran'),0,0,'L');
        $pdf->Ln(4);

        $pdf->Cell(1);
        $pdf->Cell(190,5,utf8_decode('VENTAS AL POR MENOS DE OTROS PRODUCTOR N.C.P'),0,0,'L');

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();
    }
}
