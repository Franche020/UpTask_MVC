const mobilMenuBtn = document.querySelector('#mobile-menu');
const cerrarMenuBtn = document.querySelector('#cerrar-menu');
const sidebar = document.querySelector('.sidebar');


if(mobilMenuBtn){
    mobilMenuBtn.addEventListener('click', function(){
        sidebar.classList.remove('ocultar');
        sidebar.classList.add('mostrar');
    });
}
if(cerrarMenuBtn){
    cerrarMenuBtn.addEventListener('click', function(){
        sidebar.classList.add('ocultar');

        setTimeout(()=>{
            sidebar.classList.remove('mostrar');

        },480);
    });
}


window.addEventListener('resize', function (){
    const anchoPantalla = document.body.clientWidth;
    if (anchoPantalla > 768) {
        sidebar.classList.remove('mostrar');
    }
})