<div class="contenedor confirmar">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
<?php include_once __DIR__ . '/../templates/alertas.php' ?>
    <div class="contenedor-sm">
        <?php if(empty($alertas['error'])){ ?>
        <div class="acciones">
                <a href="/">Iniciar Sesion</a>
        </div>
        <?php } ?>
    </div>
</div>