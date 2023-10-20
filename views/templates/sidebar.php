<aside class="sidebar">
    <div class="contenedor-sidebar">
        <h2>UpTask</h2>

        <div class="cerrar-menu" id="cerrar-menu">
            <img src="build/img/cerrar.svg" alt="Imagen menu">
        </div>

    </div>

    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === 'Proyectos')? 'activo' :''; ?>" href="/dashboard">Proyectos</a>
        <a class="<?php echo ($titulo === 'Crear Proyecto')? 'activo' :''; ?>" href="/crear-proyecto">Crear Proyecto</a>
        <a class="<?php echo ($titulo === 'Perfil')? 'activo' :''; ?>" href="/perfil">Perfil</a>
    </nav>
    <div class="cerrar-sesion-movil">
        <a href="/logout" class="cerrar-sesion">Cerrar Sesion</a>
    </div>
</aside>