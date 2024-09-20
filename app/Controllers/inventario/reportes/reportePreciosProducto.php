<?php

namespace App\Controllers\inventario\reportes;
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

class reportePreciosProducto extends Controller
{

    public function preciosProductos(){

        $pdf = new PDF();
        $pdf->AliasNbPages();

        //$x = 100;
        //$xx = 131;
        // Agregar una página
        $pdf->AddPage();

        $pdf->SetXY(50, 10);
        $pdf->SetFillColor(154, 193, 229);
        $pdf->Cell(150,5,utf8_decode('DOCUMENTO TRIBUTARIO ELECTRONICO: '),1,0,'L', true);

        $pdf->SetFont('Arial', 'B', 8);
        
        $pdf->SetXY(100,12);


        $pdf->Cell(190,10,utf8_decode('Código de generación: '),0,0,'L');
        $pdf->SetFont('Arial', '', 8);
        $pdf->SetX(131);
        $pdf->Cell(190,10,utf8_decode('yy'),0,0,'L');

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();

    }
}
