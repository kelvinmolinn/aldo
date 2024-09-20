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
        /*if ($this->PageNo() == 1) { // Verifica si es la primera página
            // Logo
            $this->Image('../assets/plugins/img/aldo_game_store2.png', 11, 10, 30);
        }*/
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
        $pdf->SetXY(10, 10);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(190,5,utf8_decode('Reporte de lista de precios de los productos'),0,0,'C');

        $pdf->SetXY(10,20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(15,5,utf8_decode('#'),1,0,'C');

        $pdf->SetXY(25,20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(35,10,utf8_decode('Producto'),1,0,'C');

        $pdf->SetXY(60, 20); // Posicionar la celda
        $pdf->SetFont('Arial', '', 10);
        $pdf->MultiCell(50, 5, utf8_decode("Plataforma\nOtro texto aquí"), 1, 'C');

        $pdf->SetXY(110,20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(45,5,utf8_decode('Precio de venta (sin IVA)'),1,0,'C');

        $pdf->SetXY(155,20);

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(45,5,utf8_decode('Precio de venta (con IVA)'),1,0,'C');


        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();

    }
}
