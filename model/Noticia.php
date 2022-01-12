<?php
namespace App\Model;

class Noticia
{
    //Variables o atributos
    var $id;
    var $titulo;
    var $slug;
    var $entradilla;
    var $texto;
    var $activo;
    var $home;
    var $fecha;
    var $autor;
    var $imagen;

    function __construct($data=null){

        $this->id = ($data) ? $data->id : null;
        $this->titulo = ($data) ? $data->titulo : null;
        $this->slug = ($data) ? $data->slug : null;
        $this->entradilla = ($data) ? $data->entradilla : null;
        $this->texto = ($data) ? $data->texto : null;
        $this->activo = ($data) ? $data->activo : null;
        $this->home = ($data) ? $data->home : null;
        $this->fecha = ($data) ? $data->fecha : null;
        $this->autor = ($data) ? $data->autor : null;
        $this->imagen = ($data) ? $data->imagen : null;

    }

}