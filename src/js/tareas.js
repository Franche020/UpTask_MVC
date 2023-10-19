(function (){ //IIEF
    

    obtenerTareas();
    let tareas = [];
    
    // Botón para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);
    
    // obtener las tareas de la API
    async function obtenerTareas() {
        try {
            // Genero la url para leer la API
            const id = obtenerProyecto();
            const url = `api/tareas?id=${id}`;
            
            // Fetch y json para API
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas;
            // Llamada a funcion para procesar tareas
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    }
    // Generar HTML para mostrar las tareas y eventos de los botones
    function mostrarTareas () {
        // Seleccion del contenedor de tareas
        const contenedorTareas = document.querySelector('#listado-tareas');
        if(tareas.length === 0) {
            // Si no hay tareas escritura de aviso
            const LiNoTareas = document.createElement('LI');
            const textoNoTareas = document.createElement('P');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            LiNoTareas.appendChild(textoNoTareas);
            contenedorTareas.appendChild(LiNoTareas);
            return;
        }

        const estados = {
            0:'Pendiente',
            1: 'En Proceso',
            2: 'Terminado'
        };

        
        tareas.forEach(tarea => {
            // Elemento lista
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id;
            contenedorTarea.classList.add('tarea');
            //Parrafo nombre
            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre;
            // Div Botones
            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');
            // Botones
                // boton Estado
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].replace(' ','-').toLowerCase()}`)
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;

            btnEstadoTarea.ondblclick = function(){
                cambiarEstadoTarea({...tarea})
            }
                // Boton eliminar
            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({...tarea})
            }

            // Añadir elementos
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);
            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);
            contenedorTareas.appendChild(contenedorTarea);

        });
    }
    // Limpieza del estado tareas
    function limpiarTareas () {
        const contenedorTareas = document.querySelector('#listado-tareas');
        while (contenedorTareas.firstChild){
            contenedorTareas.removeChild(contenedorTareas.firstChild);
        }
    }
    // Mostrar la ventana modal con el formulario de añadir tare, incluye event delegation
    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal'); //* Estilo y animacion
        modal.innerHTML= 
            `
            <form class="formulario nueva-tarea">
                <legend class="formulario-legend">Añade nueva tarea</legend>
                    <div class="campo">
                        <label for="tarea">Tarea</label>
                        <input type="text" id="tarea" name="tarea" placeholder="Añadir tarea al proyecto actual"/>
                    </div>
                    <div class="opciones">
                        <input type="submit" class="submit-nueva-tarea" value="Añadir Tarea" />
                        <input type="button" class="cerrar-modal" value="Cancelar">
                    </div>
            </form>
        `;
        
            //* Estilo y animacion
        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
            modal.classList.add('animar');
        }, 5);


    //* .............EVEN LISTENERS........................

        modal.addEventListener('click', function(e){
            e.preventDefault();

                
            if(e.target.classList.contains('cerrar-modal')){
                cerrarModal(modal);
            }

            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }
    // Cierra el mmodal pasandole la referencia
    function cerrarModal(modal) {
            //* Estilo y animacion
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('cerrar');
            modal.classList.add('cerrar');
            
            setTimeout(() => {
                modal.remove();
            }, 500);
    }
    // Submiting del form de la ventana modal
    function submitFormularioNuevaTarea() {
        const tarea = document.querySelector('#tarea').value.trim();

        if(tarea === ''){
            // Mostrar una alerta de error
            mostrarAlerta('El Nombre de la Tarea es Obligatorio', 'error', document.querySelector('.formulario-legend'));
            return;
        }
            agregarTarea(tarea);
            quitarAlerta();
    }
    // Funcion de alertas de errores en la ventana modal
    function mostrarAlerta(mensaje, tipo, referencia) {

        // Prevenir aparicion multiples alertas
        const divAlertaPrevia = document.querySelector('.alerta');
        if(divAlertaPrevia){
            divAlertaPrevia.remove();
        }

        const divAlerta = document.createElement('DIV');
        const alerta = document.createElement('P');
        divAlerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        
        divAlerta.appendChild(alerta);
        referencia.parentElement.insertBefore(divAlerta, referencia.nextElementSibling);

        //Eliminar la alerta a los 5 sec
        setTimeout(() => {
            divAlerta.remove();
        }, 5000);
    }
    // Quita alertas con la clase alerta
    function quitarAlerta () {
        const divAlertaPrevia = document.querySelector('.alerta');
        if(divAlertaPrevia){
            divAlertaPrevia.remove();
        }
    }
    // Funcion para Agregar las tareas a la API
    async function agregarTarea (tarea) {
        // Construir la peticion
        const datos = new FormData();
        datos.append('nombre',tarea)
        datos.append('proyectoUrl', obtenerProyecto());
        
       
        try {
            const url = '/api/tarea/crear';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            }); 
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector('.formulario-legend'));

            if (resultado.tipo === 'exito'){
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    cerrarModal(modal);
                }, 1000);
            }
            const tareaObj = {
                id: String(resultado.id),
                nombre: tarea,
                estado: "0",
                proyectoId: resultado.proyectoId
            }
            tareas = [...tareas, tareaObj];
            console.log(tareas)
            limpiarTareas();
            mostrarTareas();
 
        } catch (error) {
            console.log(error)
        }

    }
    // Actualiza el estado de la tarea
    function cambiarEstadoTarea(tarea){
        
        switch(tarea.estado){
            case '0':
            tarea.estado = '1'
            break;
            case '1':
            tarea.estado = '2'
            break;
            case '2':
            tarea.estado = '0'
            break;
        }
        actualizarTarea(tarea);
    }
    // Envia la actualizacion al servidor
    async function actualizarTarea(tarea){
        const {estado, id, nombre, proyectoId} = tarea;
        datos = new FormData();
        datos.append('estado', estado);
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('url', obtenerProyecto());

        try {
            const url = '/api/tarea/actualizar';

            const respuesta = await fetch (url, {
                method: 'post',
                body: datos
            })
            const resultado = await respuesta.json();
            if(resultado.tipo === 'exito') {
                console.log(resultado.mensaje);
                
                tareas = tareas.map(tareaMemoria => {
                    if(tareaMemoria.id === id){
                        tareaMemoria = tarea;
                    }
                    return tareaMemoria;
                })
                
                limpiarTareas();
                mostrarTareas();
                
                mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector(`[data-tarea-id="${tarea.id}"]`));

            }
        } catch (error) {
            console.log(error);
        }
    }
    // Muestra la alerta para confirmar la eliminacion de la tarea
    function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Estas Seguro?',
            text: "!No podrás volver atrás!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '!Sí, Eliminalo!'
          }).then(async (result) => {
            if (result.isConfirmed) {
                const resultado = await eliminarTarea(tarea);
               
                if (resultado) {
                    Swal.fire(
                      '!Eliminado!',
                      'La tarea se ha eliminado',
                      'success'
                      )
                      
                    tareas = tareas.filter( tareaMemoria => tareaMemoria.id !== tarea.id);
                    
                    limpiarTareas();
                    mostrarTareas();
                } else {
                    Swal.fire(
                        '!Error!',
                        'La tarea no se ha eliminado',
                        'error'
                        )
                }
            }
          })
    }
    // Funcion que conecta con la API para eliminar la tarea
    async function eliminarTarea(tarea){
        const datos = new FormData();
        datos.append('id',tarea.id);
        datos.append('proyectoId',tarea.proyectoId);
        datos.append('url',obtenerProyecto());

        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url,{
                method: 'POST',
                body: datos
            })
            const resultado = await respuesta.json();
            if(resultado.respuesta) {
                return true;
            }
            return false;
        } catch (error) {
            console.log(error);
            return false;
        }
    }
    // obtiene el url de proyecto
    function obtenerProyecto (){
        // Obtener los parametros que vienen de la url
        const proyectoParams = new URLSearchParams(window.location.search);
        // crear un objeto con ellos
        const proyecto = Object.fromEntries(proyectoParams.entries());
        
        return proyecto.id;
    }

})();