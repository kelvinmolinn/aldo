<?php

namespace App\Controllers\select;
use CodeIgniter\Controller;
use App\Models\cat_19_actividad_economica;
use App\Models\cat_12_paises_ciudades;
use App\Models\cat_13_paises_estados;

class selectCatalogosMH extends Controller
{
	public function selectActividadEconomica() {
		$txtBuscar = $this->request->getPost('txtBuscar');

		if($txtBuscar == "") {
	  		$jsonSelect[] = ['id'=>'', 'text'=>'Digite código o actividad económica'];
	  		$jsonRespuesta = json_encode($jsonSelect);
            return $this->response->setJSON($jsonRespuesta);
		} else {
	        $catActividadEconomica = new cat_19_actividad_economica();
	        $consultaActividadEconomica = $catActividadEconomica
	            ->select('actividadEconomicaId,codigoMH,actividadEconomica')
	            ->where('flgElimina', 0)
			    ->groupStart()
			        ->like('actividadEconomica', $txtBuscar)
			        ->orLike('codigoMH', $txtBuscar)
			    ->groupEnd()
	            ->findAll();
	        $n = 0;
	        foreach($consultaActividadEconomica as $actividadEconomica) {
	        	$n++;
	        	$jsonSelect[] = array("id" => $actividadEconomica['actividadEconomicaId'], "text" => "(" . $actividadEconomica["codigoMH"] . ") " . $actividadEconomica["actividadEconomica"]);
	        }

	        if($n > 0) {
	        	$jsonRespuesta = json_encode($jsonSelect);
	        	return $this->response->setJSON($jsonSelect);
	        } else {
		  		$jsonSelect[] = ['id'=>'', 'text'=>'Digite código o actividad económica'];
		  		$jsonRespuesta = json_encode($jsonSelect);
	            return $this->response->setJSON($jsonRespuesta);
	        }
		}
	}

	public function selectPaisDepartamento() {
		$paisId = $this->request->getPost('paisId');

        $catPaisCiudad = new cat_12_paises_ciudades();
        $consultaPaisDepartamento = $catPaisCiudad
            ->select('paisCiudadId,paisCiudad')
            ->where('flgElimina', 0)
            ->where('paisId', $paisId)
            ->findAll();
        $n = 0;
        foreach($consultaPaisDepartamento as $paisDepartamento) {
        	$n++;
        	$jsonSelect[] = array("id" => $paisDepartamento['paisCiudadId'], "text" => $paisDepartamento["paisCiudad"]);
        }

        if($n > 0) {
        	$jsonRespuesta = json_encode($jsonSelect);
        	return $this->response->setJSON($jsonSelect);
        } else {
	  		$jsonSelect[] = ['id'=>'', 'text'=>'Digite el departamento del país'];
	  		$jsonRespuesta = json_encode($jsonSelect);
            return $this->response->setJSON($jsonRespuesta);
        }
	}
}