(function (){ //IIEF
    // Botón para mostrar el modal de agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);

    function mostrarFormulario() {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
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

        setTimeout(() => {
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
            modal.classList.add('animar');
        }, 5);


        //* EVEN LISTENERS


        modal.addEventListener('click', function(e){
            e.preventDefault();

            if(e.target.classList.contains('cerrar-modal')){
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                modal.classList.add('cerrar');
                
                setTimeout(() => {
                    modal.remove();
                }, 500);
            }

            if(e.target.classList.contains('submit-nueva-tarea')){
                submitFormularioNuevaTarea();
            }
        })

        document.querySelector('.dashboard').appendChild(modal);
    }

    function submitFormularioNuevaTarea() {
        const tarea = document.querySelector('#tarea').value.trim();

        if(tarea === ''){
            // Mostrar una alerta de error
            mostrarAlerta('El Nombre de la Tarea es Obligatorio', 'error', document.querySelector('.formulario-legend'));
            mostrarAlerta

            return;
        }
            console.log(tarea);
    }

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



})();