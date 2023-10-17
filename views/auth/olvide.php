<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombre-sitio.php' ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar tu acceso a UpTask</p>
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>
        <form action="/olvide" class="formulario" method="post">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Tu Email">
            </div>
            <input type="submit" value="Enviar Instrucciones" class="boton">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una Cuenta? Iniciar Sesion</a>
            <a href="/crear">¿Aun no tienes una cuenta? Obtener una</a>
        </div>
    </div>
</div>