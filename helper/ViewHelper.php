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

    public function redireccionConMensaje($ruta, $tipo, $texto){

        $_SESSION['mensaje'] = array("tipo" => $tipo, "texto" => $texto);
        header("Location:".$_SESSION["home"].$ruta);

    }

    public function permisos($permiso=null){


        if (isset($_SESSION['usuario']) AND ($permiso == null OR $_SESSION[$permiso] == 1)){
            return true;
        }
        else{
            $this->redireccionConMensaje("admin","yellow", "No tienes permiso para realizar esta operación");
        }


    }

}