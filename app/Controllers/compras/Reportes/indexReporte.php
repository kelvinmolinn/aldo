<?php

namespace App\Controllers\compras\Reportes;
use CodeIgniter\Controller;



class indexReporte extends Controller
{
    public function generate(){

        require_once(APPPATH . 'Libraries/fpdf/fpdf.php');
        
        // Crear una instancia de FPDF


        $pdf = new \FPDF();
        
        // Agregar una página
        $pdf->AddPage();
        
        // Establecer fuente
        $pdf->SetFont('Arial', 'B', 16);
        
        // Agregar una celda con texto
        $pdf->Cell(40, 10, utf8_decode('¡Hola, Mundo!'), 0, 0, 'C');

        $this->response->setHeader('Content-Type', 'application/pdf');
        // Salida del PDF (en el navegador)
        $pdf->Output();
    }
}
