<?php

namespace App\Libraries;

require_once APPPATH . 'Libraries/fpdf/fpdf.php';

class Fpdf extends \FPDF
{
    public function __construct()
    {
        parent::__construct();
    }
}
