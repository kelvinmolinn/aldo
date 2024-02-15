<?php

namespace App\Controllers;

class Productos extends BaseController{
    public function index(){
        
        $data =['titulo'=>'Catalogo de productos'];
       return view('productos/index', $data);
        /*return view('plantilla/header', $data)
        .view('productos/index', $data)
        .view('plantilla/footer',['copy'=>"2024"]);*/
        //return view('productos/index', $data);
       // print_r($this->session);
    }

    public function show($id){
        $data =[
            'titulo'=>'Catalogo de productos',
            'id' => $id
        ];
        return view('productos/show', $data);
       /* return view('plantilla/header', $data)
        .view('productos/show', $data)
        .view('plantilla/footer',['copy'=>"2024"]);*/
    }

    public function cat($categoria, $id){
        return "<h2>Categoria: $categoria <br> Productos: $id</h2>";
    }
}

?>