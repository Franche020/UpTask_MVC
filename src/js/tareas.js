(function (){ //IIEF
    
    // Botón para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

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
 
        } catch (error) {
            console.log(error)
        }

    }

    function obtenerProyecto (){
        // Obtener los parametros que vienen de la url
        const proyectoParams = new URLSearchParams(window.location.search);
        // crear un objeto con ellos
        const proyecto = Object.fromEntries(proyectoParams.entries());
        
        return proyecto.id;
    }

})();