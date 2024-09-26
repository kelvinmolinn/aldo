<?php

namespace App\Controllers\ventas\reportes;
require_once(APPPATH . 'Libraries/fpdf/fpdf.php');

use App\Models\fel_facturas_detalle;

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

class reporteConsolidadoVentas extends Controller
{

    public function consolidadoVentas(){
        $felFacturasDetalle = new fel_facturas_detalle();

        $pdf = new PDF();
        $pdf->AliasNbPages();

        $fechaInicio = $this->request->getGet('fechaInicio');
        $fechaFin = $this->request->getGet('fechaFin');

        //$x = 100;
        //$xx = 131;
        // Agregar una página
        $pdf->AddPage();
        $pdf->SetXY(10, 10);
        $pdf->SetFont('Arial', 'B', 15);
        $pdf->Cell(190,5,utf8_decode('Reporte de consolidado de ventas'),0,0,'C');

        $pdf->SetXY(10,20);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10,10,utf8_decode('#'),1,0,'C');

        $pdf->SetXY(20,20);
        $pdf->Cell(30,10,utf8_decode('Tipo de documento'),1,0,'C');

        $pdf->SetXY(50, 20);
        $pdf->Cell(35, 10, utf8_decode("Número de documento"), 1,0, 'C');

        $pdf->SetXY(85,20);
        $pdf->Cell(30,10,utf8_decode('Fecha de documento'),1,0,'C');

        $pdf->SetXY(115,20);
        $pdf->Cell(40,10,utf8_decode('Cliente'),1,0,'C');

        $pdf->SetXY(155,20);
        $pdf->Cell(45,10,utf8_decode('Total de la compra'),1,0,'C');

        $n = 0;  
        $y = 30;    

        $datosFactura = $felFacturasDetalle
            ->select('cat_02_tipo_dte.tipoDocumentoDTE,fel_facturas.facturaId, DATE_FORMAT(fel_facturas.fechaEmision, "%d-%m-%Y") as fechaEmision,fel_clientes.cliente,fel_facturas_detalle.totalDetalleIVA')
            ->join('fel_facturas','fel_facturas.facturaId = fel_facturas_detalle.facturaId')
            ->join('cat_02_tipo_dte','cat_02_tipo_dte.tipoDTEId = fel_facturas.tipoDTEId')
            ->join('fel_clientes','fel_clientes.clienteId = fel_facturas.clienteId')
            ->where('fel_facturas_detalle.flgElimina', 0)
            ->where('fel_facturas.estadoFactura','Certificado')
            ->where('fel_facturas.fechaEmision >=', $fechaInicio)
            ->where('fel_facturas.fechaEmision <=', $fechaFin)
            ->findAll();

        foreach ($datosFactura AS $datos) {
            $n++;

            $pdf->SetXY(10,$y);
            $pdf->SetFont('Arial', '', 8);
            $pdf->Cell(10,10,utf8_decode($n),1,0,'C');

            $pdf->SetXY(20,$y);
            $pdf->Cell(30,10,utf8_decode($datos['tipoDocumentoDTE']),1,0,'C');

            $pdf->SetXY(50, $y);
            $pdf->Cell(35, 10, utf8_decode($datos['facturaId']), 1,0, 'C');

            $pdf->SetXY(85,$y);
            $pdf->Cell(30,10,utf8_decode($datos['fechaEmision']),1,0,'C');

            $pdf->SetXY(115,$y);
            $pdf->Cell(40,10,utf8_decode($datos['cliente']),1,0,'C');

            $pdf->SetXY(155,$y);
            $pdf->Cell(45,10,utf8_decode("$ ".number_format($datos['totalDetalleIVA'], 2, '.', ',')),1,0,'C');

            $y += 10;
        }

        $this->response->setHeader('Content-Type', 'application/pdf');
  
        $pdf->Output();

    }
}
