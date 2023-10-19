!function(){!async function(){try{const t="api/tareas?id="+s(),a=await fetch(t),o=await a.json();e=o.tareas,n()}catch(e){console.log(e)}}();let e=[],t=[],a="";document.querySelector("#agregar-tarea").addEventListener("click",(function(){r()}));function o(e){a=e.target.value,console.log(a),n()}function n(){let o=!1;""!==a?(t=e.filter(e=>e.estado===a),o=!0):(t=[],o=!1),console.log(o),function(a=!1){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}();const o=a?t:e,c=document.querySelector("#listado-tareas");if(0===o.length){const e=document.createElement("LI"),t=document.createElement("P");return t.textContent="No hay tareas",t.classList.add("no-tareas"),e.appendChild(t),void c.appendChild(e)}const i={0:"Pendiente",1:"En Proceso",2:"Terminado"};o.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const o=document.createElement("P");o.textContent=t.nombre,o.ondblclick=function(){r(t,!0)};const l=document.createElement("DIV");l.classList.add("opciones");const u=document.createElement("BUTTON");u.classList.add("estado-tarea"),u.classList.add(""+i[t.estado].replace(" ","-").toLowerCase()),u.textContent=i[t.estado],u.dataset.estadoTarea=t.estado,u.ondblclick=function(){!function(e){switch(e.estado){case"0":e.estado="1";break;case"1":e.estado="2";break;case"2":e.estado="0"}d(e)}({...t})};const m=document.createElement("BUTTON");m.classList.add("eliminar-tarea"),m.dataset.idTarea=t.id,m.textContent="Eliminar",m.ondblclick=function(){!function(t){Swal.fire({title:"¿Estas Seguro?",text:"!No podrás volver atrás!",icon:"warning",showCancelButton:!0,confirmButtonColor:"#3085d6",cancelButtonColor:"#d33",confirmButtonText:"!Sí, Eliminalo!"}).then(async a=>{if(a.isConfirmed){await async function(e){const t=new FormData;t.append("id",e.id),t.append("proyectoId",e.proyectoId),t.append("url",s());try{const e="/api/tarea/eliminar",a=await fetch(e,{method:"POST",body:t});return!!(await a.json()).respuesta}catch(e){return console.log(e),!1}}(t)?(Swal.fire("!Eliminado!","La tarea se ha eliminado","success"),e=e.filter(e=>e.id!==t.id),n()):Swal.fire("!Error!","La tarea no se ha eliminado","error")}})}({...t})},l.appendChild(u),l.appendChild(m),a.appendChild(o),a.appendChild(l),c.appendChild(a)})}(o)}function r(t,a=!1){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n            <form class="formulario nueva-tarea">\n                <legend class="formulario-legend">${a?"Editar tarea":"Añadir nueva tarea"}</legend>\n                    <div class="campo">\n                        <label for="tarea">Tarea</label>\n                        <input type="text" id="tarea" name="tarea" placeholder="${a?"Introduce un nuevo nombre":"Añadir tarea al proyecto actual"}" value="${a?""+t.nombre:""}"/>\n                    </div>\n                    <div class="opciones">\n                        <input type="submit" class="submit-nueva-tarea" value="${a?"Editar tarea":"Añadir nueva tarea"}" />\n                        <input type="button" class="cerrar-modal" value="Cancelar">\n                    </div>\n            </form>\n        `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar"),o.classList.add("animar")},5),o.addEventListener("click",(function(r){if(r.preventDefault(),r.target.classList.contains("cerrar-modal")&&c(o),r.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===t)return void i("El Nombre de la Tarea es Obligatorio","error",document.querySelector(".formulario-legend"));a?(t.nombre=o,d(t)):async function(t){const a=new FormData;a.append("nombre",t),a.append("proyectoUrl",s());try{const o="/api/tarea/crear",r=await fetch(o,{method:"POST",body:a}),d=await r.json();if(i(d.mensaje,d.tipo,document.querySelector(".formulario-legend")),"exito"===d.tipo){const e=document.querySelector(".modal");setTimeout(()=>{c(e)},1e3)}const s={id:String(d.id),nombre:t,estado:"0",proyectoId:d.proyectoId};e=[...e,s],console.log(e),n()}catch(e){console.log(e)}}(o),function(){const e=document.querySelector(".alerta");e&&e.remove()}()}})),document.querySelector(".dashboard").appendChild(o)}function c(e){document.querySelector(".formulario").classList.add("cerrar"),e.classList.add("cerrar"),setTimeout(()=>{e.remove()},500)}function i(e,t,a){const o=document.querySelector(".alerta");o&&o.remove();const n=document.createElement("DIV"),r=document.createElement("P");n.classList.add("alerta",t),r.textContent=e,n.appendChild(r),a.parentElement.insertBefore(n,a.nextElementSibling),setTimeout(()=>{n.remove()},5e3)}async function d(t){const{estado:a,id:o,nombre:r,proyectoId:i}=t;datos=new FormData,datos.append("estado",a),datos.append("id",o),datos.append("nombre",r),datos.append("url",s());try{const a="/api/tarea/actualizar",r=await fetch(a,{method:"post",body:datos}),i=await r.json();if("exito"===i.tipo){e=e.map(e=>(e.id===o&&(e=t),e)),n();const a=document.querySelector(".modal");let r;a&&setTimeout(()=>{c(a)},1e3),Swal.fire({title:i.mensaje,html:"",timer:1e3,timerProgressBar:!1,didOpen:()=>{Swal.showLoading(),r=setInterval(()=>{},100)},willClose:()=>{clearInterval(r)}})}}catch(e){console.log(e)}}function s(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('input[type="radio"]').forEach(e=>{e.addEventListener("click",o)})}();