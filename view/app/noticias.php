<h3>
    <a href="<?php echo $_SESSION['home'] ?>" title="Inicio">Inicio</a> <span>| Noticias</span>
</h3>
<div class="row">
    <?php foreach ($datos as $row){ ?>
        <article class="col m12 l6">
            <div class="card horizontal small">
                <div class="card-image">
                    <img src="<?php echo $_SESSION['public']."img/".$row->imagen ?>" alt="<?php echo $row->titulo ?>">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h4><?php echo $row->titulo ?></h4>
                        <p><?php echo $row->entradilla ?></p>
                    </div>
                    <div class="card-info">
                        <p><?php echo date("d/m/Y", strtotime($row->fecha)) ?></p>
                    </div>
                    <div class="card-action">
                        <a href="<?php echo $_SESSION['home']."noticia/".$row->slug ?>">Más información</a>
                    </div>
                </div>
            </div>
        </article>
    <?php } ?>
</div>
