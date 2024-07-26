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
        
        // Arial bold 15
        /*$imagePath = FCPATH . 'assets/plugins/img/algo_game_store.jpg'; // Utiliza FCPATH para obtener la ruta completa

        // Logo
        if (file_exists($imagePath)) {
            $this->Image($imagePath, 190,5,20,20); // Ajusta la posición y tamaño según lo necesites
        }*/
        $this->SetFont('Arial','B',8);
        // Movernos a la derecha
        $this->Cell(1);
        // Título

        $this->SetFillColor(154, 193, 229);
        $this->Cell(190,5,'DOCUMENTO TRIBUTARIO ELECTRONICO',1,0,'L', true);
        //$this->Image('../../assets/plugins/img/algo_game_store.jpg',190,5,20,20,'JPG');



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

        
        // Crear una instancia de FPDF
        $pdf = new PDF();

 
        // Agregar una página
        $pdf->AddPage();
        
        // Establecer fuente
        $pdf->SetFont('Arial', 'B', 16);
        
        // Agregar una celda con texto
        //$pdf->Cell(40, 10, utf8_decode('¡Hola, Mundo!'), 0, 0, 'C');

        $this->response->setHeader('Content-Type', 'application/pdf');
        // Salida del PDF (en el navegador)
        $pdf->Output();
    }
}
