<?php
namespace App\Helper;

class ViewHelper {

    function vista($carpeta,$archivo,$datos=null){

        //Llamo a la cabecera
        require("../view/$carpeta/partials/header.php");

        //Llamo al contenido
        require ("../view/$carpeta/$archivo.php");

        //Llamo al pie
        require("../view/$carpeta/partials/footer.php");

    }

}