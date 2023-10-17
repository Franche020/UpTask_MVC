<?php include_once __DIR__ . '/header-dashboard.php' ?>



<?php if (count($proyectos) === 0) {

?>

    <p class="no-proyectos">No hay Proyectos, para comenzar crea un <a href="/crear-proyecto">nuevo proyecto</a></p>

<?php } else { ?>
    <ul class="listado-proyectos">
        <?php foreach ($proyectos as $proyecto) : ?>
            <li>
                <a class="proyecto" href="/proyecto?id=<?php echo $proyecto->url ?>">
                    <p><?php echo $proyecto->proyecto ?></p>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
<?php } ?>

<?php include_once __DIR__ . '/footer-dashboard.php' ?>