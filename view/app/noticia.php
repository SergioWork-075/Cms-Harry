<h3>
    <a href="<?php echo $_SESSION['home'] ?>" title="Inicio">Inicio</a> <span>| </span>
    <a href="<?php echo $_SESSION['home'] ?>noticias" title="Noticias">Noticias</a> <span>| </span>
    <span><?php echo $datos->titulo ?></span>
</h3>
<div class="row">
    <article class="col s12">
        <div class="card horizontal large noticia">
            <div class="card-image">
                <img src="<?php echo $_SESSION['public']."img/".$datos->imagen ?>" alt="<?php echo $datos->titulo ?>">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <h4><?php echo $datos->titulo ?></h4>
                    <p><?php echo $datos->entradilla ?></p>
                    <p><?php echo $datos->texto ?></p>
                    <br>
                    <p>
                        <strong>Fecha</strong>: <?php echo date("d/m/Y", strtotime($datos->fecha)) ?><br>
                        <strong>Autor</strong>: <?php echo $datos->autor ?>
                    </p>
                </div>
            </div>
        </div>
    </article>
</div>
