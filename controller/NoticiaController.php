<?php
namespace App\Controller;

use App\Helper\ViewHelper;
use App\Helper\DbHelper;
use App\Model\Noticia;


class NoticiaController
{
    var $db;
    var $view;

    function __construct()
    {
        //Conexión a la BBDD
        $dbHelper = new DbHelper();
        $this->db = $dbHelper->db;

        //Instancio el ViewHelper
        $viewHelper = new ViewHelper();
        $this->view = $viewHelper;
    }

    //Listado de noticias
    public function index(){

        //Permisos
        $this->view->permisos("noticias");

        //Recojo las noticias de la base de datos
        $rowset = $this->db->query("SELECT * FROM noticias ORDER BY fecha DESC");

        //Asigno resultados a un array de instancias del modelo
        $noticias = array();
        while ($row = $rowset->fetch(\PDO::FETCH_OBJ)){
            array_push($noticias,new Noticia($row));
        }

        $this->view->vista("admin","noticias/index", $noticias);

    }

    //Para activar o desactivar
    public function activar($id){

        //Permisos
        $this->view->permisos("noticias");

        //Obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM noticias WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $noticia = new Noticia($row);

        if ($noticia->activo == 1){

            //Desactivo la noticia
            $consulta = $this->db->exec("UPDATE noticias SET activo=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$noticia->titulo</strong> se ha desactivado correctamente.") :
                $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Activo la noticia
            $consulta = $this->db->exec("UPDATE noticias SET activo=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$noticia->titulo</strong> se ha activado correctamente.") :
                $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");
        }

    }

    //Para mostrar o no en la home
    public function home($id){

        //Permisos
        $this->view->permisos("noticias");

        //Obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM noticias WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $noticia = new Noticia($row);

        if ($noticia->home == 1){

            //Quito la noticia de la home
            $consulta = $this->db->exec("UPDATE noticias SET home=0 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$noticia->titulo</strong> ya no se muestra en la home.") :
                $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");
        }

        else{

            //Muestro la noticia en la home
            $consulta = $this->db->exec("UPDATE noticias SET home=1 WHERE id='$id'");

            //Mensaje y redirección
            ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
                $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$noticia->titulo</strong> ahora se muestra en la home.") :
                $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");
        }

    }

    public function borrar($id){

        //Permisos
        $this->view->permisos("noticias");

        //Obtengo la noticia
        $rowset = $this->db->query("SELECT * FROM noticias WHERE id='$id' LIMIT 1");
        $row = $rowset->fetch(\PDO::FETCH_OBJ);
        $noticia = new Noticia($row);

        //Borro la noticia
        $consulta = $this->db->exec("DELETE FROM noticias WHERE id='$id'");

        //Borro la imagen asociada
        $archivo = $_SESSION['public']."img/".$noticia->imagen;
        $texto_imagen = "";
        if (is_file($archivo)){
            unlink($archivo);
            $texto_imagen = " y se ha borrado la imagen asociada";
        }

        //Mensaje y redirección
        ($consulta > 0) ? //Compruebo consulta para ver que no ha habido errores
            $this->view->redireccionConMensaje("admin/noticias","green","La noticia se ha borrado correctamente$texto_imagen.") :
            $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");

    }

    public function crear(){

        //Permisos
        $this->view->permisos("noticias");

        //Creo un nuevo usuario vacío
        $noticia = new Noticia();

        //Llamo a la ventana de edición
        $this->view->vista("admin","noticias/editar", $noticia);

    }

    public function editar($id){

        //Permisos
        $this->view->permisos("noticias");

        //Si ha pulsado el botón de guardar
        if (isset($_POST["guardar"])){

            //Recupero los datos del formulario
            $titulo = filter_input(INPUT_POST, "titulo", FILTER_SANITIZE_STRING);
            $entradilla = filter_input(INPUT_POST, "entradilla", FILTER_SANITIZE_STRING);
            $autor = filter_input(INPUT_POST, "autor", FILTER_SANITIZE_STRING);
            $fecha = filter_input(INPUT_POST, "fecha", FILTER_SANITIZE_STRING);
            $texto = filter_input(INPUT_POST, "texto", FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            //Formato de fecha para SQL
            $fecha = \DateTime::createFromFormat("d-m-Y", $fecha)->format("Y-m-d H:i:s");

            //Genero slug (url amigable)
            $slug = $this->view->getSlug($titulo);

            //Imagen
            $imagen_recibida = $_FILES['imagen'];
            $imagen = ($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : "";
            $imagen_subida = ($_FILES['imagen']['name']) ? '/var/www/html'.$_SESSION['public']."img/".$_FILES['imagen']['name'] : "";
            $texto_img = ""; //Para el mensaje

            if ($id == "nuevo"){

                //Creo una nueva noticia
                $consulta = $this->db->exec("INSERT INTO noticias 
                    (titulo, entradilla, autor, fecha, texto, slug, imagen) VALUES 
                    ('$titulo','$entradilla','$autor','$fecha','$texto','$slug','$imagen')");

                //Subo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        $texto_img = " La imagen se ha subido correctamente.";
                    }
                    else{
                        $texto_img = " Hubo un problema al subir la imagen.";
                    }
                }

                //Mensaje y redirección
                ($consulta > 0) ?
                    $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$titulo</strong> se creado correctamente.".$texto_img) :
                    $this->view->redireccionConMensaje("admin/noticias","red","Hubo un error al guardar en la base de datos.");
            }
            else{

                //Actualizo la noticia
                $this->db->exec("UPDATE noticias SET 
                    titulo='$titulo',entradilla='$entradilla',autor='$autor',
                    fecha='$fecha',texto='$texto',slug='$slug' WHERE id='$id'");

                //Subo y actualizo la imagen
                if ($imagen){
                    if (is_uploaded_file($imagen_recibida['tmp_name']) && move_uploaded_file($imagen_recibida['tmp_name'], $imagen_subida)){
                        $texto_img = " La imagen se ha subido correctamente.";
                        $this->db->exec("UPDATE noticias SET imagen='$imagen' WHERE id='$id'");
                    }
                    else{
                        $texto_img = " Hubo un problema al subir la imagen.";
                    }
                }

                //Mensaje y redirección
                $this->view->redireccionConMensaje("admin/noticias","green","La noticia <strong>$titulo</strong> se guardado correctamente.".$texto_img);

            }
        }

        //Si no, obtengo noticia y muestro la ventana de edición
        else{

            //Obtengo la noticia
            $rowset = $this->db->query("SELECT * FROM noticias WHERE id='$id' LIMIT 1");
            $row = $rowset->fetch(\PDO::FETCH_OBJ);
            $noticia = new Noticia($row);

            //Llamo a la ventana de edición
            $this->view->vista("admin","noticias/editar", $noticia);
        }

    }

}