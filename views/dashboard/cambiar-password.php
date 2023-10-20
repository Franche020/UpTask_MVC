<?php include_once __DIR__.'/header-dashboard.php' ?>

<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>
    <a class="enlace" href="/perfil">Volver al Perfil</a>
    <form action="/cambiar-password" method="post" class="formulario">
        <div class="campo">
            <label for="password_actual">Password Actual</label>
            <input type="password" name="password_actual" id="password_actual" placeholder="Tu Password Actual">
        </div>
        <div class="campo">
            <label for="password_nuevo">Nuevo Password</label>
            <input type="password" name="password_nuevo" id="password_nuevo" placeholder="Tu Nuevo Password">
        </div>
        <div class="campo">
            <label for="password2">Repetir Password</label>
            <input type="password" name="password2" id="password2" placeholder="Vuelve a escribir tu Nuevo Password">
        </div>

        <input type="submit" value="Actualizar">
    </form>
</div>





<?php include_once __DIR__.'/footer-dashboard.php' ?>