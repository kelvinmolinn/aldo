<?php

namespace App\Controllers\ventas;
use CodeIgniter\Controller;


use App\Models\comp_proveedores;

class administracionClientes extends Controller
{
    //ESTE CONTROLLERS ES DE PERMISOS 
    public function index(){
        $session = session();

        $data['variable'] = 0;

        $camposSession = [
            'renderVista' => 'No'
        ];
        $session->set([
            'route'             => 'ventas/admin-clientes/index',
            'camposSession'     => json_encode($camposSession)
        ]);
        return view('ventas/vistas/clientes', $data);
    }
    public function tablaClientes(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Tipo de persona: </b> Natural" . "<br>" . "<b>Cliente:</b> Test " . "<br>" . "<b>Giro:</b> otros servicios de información n.c.p" . "<br>" . "<b>Dirección:</b> Col. San salvador";

        $columna3 = "<b>Categoría: </b>" . "<br>" . "<b>NRC:</b> 55555 " . "<br>" . "<b>DUI:</b> 123456789";
        
        $columna4 = '
                        <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-pencil-alt"></i>
                        </button>';
        $columna4 .= '
                        <button type= "button" class="btn btn-primary mb-1" onclick="modalContactoClientes()" data-toggle="tooltip" data-placement="top" title="Contactos">
                            <i class="fas fa-address-book"></i>
                        </button>';

        $columna4 .= '
                         <button type= "button" class="btn btn-primary mb-1" onclick="modalHistorialVentas()" data-toggle="tooltip" data-placement="top" title="Historial de ventas">
                            <i class="fas fa-history"></i>
                        </button>
                    ';

        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3,
            $columna4
        );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalNuevoCliente(){

        $data['variable'] = 0;
        return view('ventas/modals/modalNuevoCliente', $data);
    }

    public function modalContactoCliente(){
        $data['variable'] = 0;
        return view('ventas/modals/modalContactoCliente', $data);
    }

    public function tablaContactoClientes(){
        $output['data'] = array();
        $n = 0;

        $n++;
        // Aquí construye tus columnas
        $columna1 = $n;
        $columna2 = "<b>Correo electronico: </b> prueba@gmail.com";
        
        $columna3 = '
                        <button type= "button" class="btn btn-danger mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="fas fa-trash"></i>
                        </button> 
                    ';

        $output['data'][] = array(
            $columna1,
            $columna2,
            $columna3
        );

        // Verifica si hay datos
        if ($n > 0) {
            return $this->response->setJSON($output);
        } else {
            return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
        }
    }

    public function modalHistorialVentas(){

        $data['variable'] = 0;
        return view('ventas/modals/modalHistorialVentas', $data);
    }

    public function tablaHistorialVentas(){
            $output['data'] = array();
            $n = 0;
            $n++;
            
            $columna1 = "<b>Sucursal: </b> Aldo Games Store (Principal)" . "<br>" . "<b>Tipo de DTE: </b> Factura ";
            
            $columna2 = "<b>Cód. Generación: </b> C6A9868C-028D-421B-A9A0-36274CECC2C7" . "<br>" . "<b>Núm. control: </b> DTE-03-12345678-000000000000001";
            $columna3 = "<b>Fecha: </b> 05/05/2024" . "<br>" . "<b>Hora: </b>05:25:01 ";

            $columna4 = "$ 200.00";

            $columna5 = '
                            <button type= "button" class="btn btn-primary mb-1" onclick="" data-toggle="tooltip" data-placement="top" title="Ver factura">
                                <i class="fas fa-eye"></i>
                            </button>';

            $output['data'][] = array(
                $columna1,
                $columna2,
                $columna3,
                $columna4,
                $columna5
            );

            // Verifica si hay datos
            if ($n > 0) {
                return $this->response->setJSON($output);
            } else {
                return $this->response->setJSON(array('data' => '')); // No hay datos, devuelve un array vacío
            }       
    }
}
