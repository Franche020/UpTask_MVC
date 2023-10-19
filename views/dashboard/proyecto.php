<?php include_once __DIR__.'/header-dashboard.php' ?>

<div class="contenedor-sm">
    <div class="contenedor-nueva-tarea">
        <button type="button" class="agregar-tarea" id="agregar-tarea">&#43; Nueva Tarea</button>
    </div>
    <div id="filtros" class="filtros">
        <h2>Filtros</h2>
        <div class="filtros-input">
            <div class="campos">
                <label for="todas">Todas las tareas</label>
                <input type="radio" name="filtro" id="todas" value="" checked>
            </div>
            <div class="campos">
                <label for="pendientes">Pendientes</label>
                <input type="radio" name="filtro" id="pendientes" value="0">
            </div>
            <div class="campos">
                <label for="en-proceso">En proceso</label>
                <input type="radio" name="filtro" id="en-proceso" value="1">
            </div>
            <div class="campos">
                <label for="completadas">Completadas</label>
                <input type="radio" name="filtro" id="completadas" value="2">
            </div>
        </div>
    </div>
    <ul id="listado-tareas" class="listado-tareas">
</div>

</ul>

<?php include_once __DIR__.'/footer-dashboard.php' ?>


<?php
$script = '
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/build/js/tareas.js"></script>
';
?>