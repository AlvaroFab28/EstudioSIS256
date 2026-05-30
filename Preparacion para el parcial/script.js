// Mostrar mensaje de inicializado
document.addEventListener("DOMContentLoaded", function() {
    var msg = document.getElementById("mensaje");
    if (msg) {
        msg.innerHTML = "Listo para interactuar.";
    }
});

// Cargar contenido normal en el div principal (Pregunta 3, Pregunta 4)
function cargarContenido(abrir) {
    var contenedor = document.getElementById('principal');
    if (!contenedor) {
        // En caso de que se haya destruido, buscar #contenido
        contenedor = document.getElementById('contenido');
    }
    
    fetch(abrir)
        .then(response => response.text())
        .then(data => {
            contenedor.innerHTML = data;
            // Si cargamos temas.html, restauramos los manejadores o estados si es necesario
        })
        .catch(err => {
            console.error("Error al cargar contenido: ", err);
        });
}

// Cargar contenido de tabla filtrada (Pregunta 3)
function cargarContenido2(abrir) {
    var contenedor = document.querySelector('.tabla');
    if (!contenedor) return;
    
    fetch(abrir)
        .then(response => response.text())
        .then(data => {
            contenedor.innerHTML = data;
        })
        .catch(err => {
            console.error("Error al filtrar tabla: ", err);
        });
}

// Cargar modal de creación de recetas (Pregunta 2)
function cargarModal(abrir) {
    var contenedor = document.getElementById('espacio-modal');
    if (!contenedor) return;

    fetch(abrir)
        .then(response => response.text())
        .then(data => {
            contenedor.innerHTML = data;
            cargarTipos();
            var modal = document.getElementById('modal-crear');
            if (modal) {
                modal.style.visibility = "visible";
            }
        })
        .catch(err => {
            console.error("Error al abrir modal: ", err);
        });
}

// Cerrar modal de creación
function cerrarModal() {
    var modal = document.getElementById('modal-crear');
    if (modal) {
        modal.style.visibility = "hidden";
    }
    var contenedor = document.getElementById('espacio-modal');
    if (contenedor) {
        contenedor.innerHTML = "";
    }
}

// Cargar select dinámico desde tipos_receta.php (Pregunta 2)
function cargarTipos() {
    var contenedor = document.getElementById('idtiporeceta');
    if (!contenedor) return;

    fetch('tipos_receta.php')
        .then(response => response.text())
        .then(data => {
            var tiposrecetas = JSON.parse(data);
            var html = "";
            tiposrecetas.forEach(tiporeceta => {
                html += `
                    <option value="${tiporeceta.id}">
                        ${tiporeceta.tiporeceta}
                    </option>
                `;
            });
            contenedor.innerHTML = html;
        })
        .catch(err => {
            console.error("Error al cargar tipos de receta: ", err);
        });
}

// Registrar receta vía AJAX POST (Pregunta 2)
function registrarReceta() {
    var forminsertar = document.getElementById('form-receta');
    if (!forminsertar) return;
    
    var datos = new FormData(forminsertar);
    
    fetch("guardar_receta.php", {
        method: "POST",
        body: datos
    })
    .then(response => response.text())
    .then(data => {
        // Mostrar mensaje en la barra inferior
        var msg = document.getElementById('mensaje');
        if (msg) {
            msg.innerHTML = "Respuesta del servidor: " + data;
        }
        
        // Cerrar modal
        cerrarModal();
        
        // Recargar la galería automáticamente mediante AJAX (Pregunta 1)
        cargarPregunta1();
    })
    .catch(err => {
        console.error("Error al guardar receta: ", err);
    });
}

// Cargar la galería usando AJAX XMLHttpRequest nativo (Pregunta 1)
function cargarPregunta1() {
    var contenedor = document.getElementById('principal');
    if (!contenedor) {
        contenedor = document.getElementById('contenido');
    }
    
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "galeria.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                contenedor.innerHTML = xhr.responseText;
            } else {
                console.error("Error cargando galería: " + xhr.status);
            }
        }
    };
    xhr.send();
}

// Mostrar el detalle en el modal usando AJAX XMLHttpRequest nativo (Pregunta 1)
function mostrarDetalle(id) {
    var contenedor = document.getElementById('espacio-modal');
    if (!contenedor) return;

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "detalle_receta.php?id=" + id, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                contenedor.innerHTML = xhr.responseText;
                var modal = document.getElementById('modal-detalle');
                if (modal) {
                    modal.style.visibility = "visible";
                }
            } else {
                console.error("Error cargando detalles de la receta: " + xhr.status);
            }
        }
    };
    xhr.send();
}

// Cerrar modal de detalles
function cerrarDetalle() {
    var modal = document.getElementById('modal-detalle');
    if (modal) {
        modal.style.visibility = "hidden";
    }
    var contenedor = document.getElementById('espacio-modal');
    if (contenedor) {
        contenedor.innerHTML = "";
    }
}

// Agregar tarjeta de color en temas (Pregunta 4)
function aumentar() {
    var colorInput = document.getElementById("color");
    var temaSelect = document.getElementById("tema");
    var contenedor = document.getElementById("contenedor-colores");
    
    if (!colorInput || !temaSelect || !contenedor) return;
    
    var color = colorInput.value;
    var tema = temaSelect.value;
    
    var html = `
        <div class="tarjeta-color" style="background-color: ${color};"> 
            ${tema}<br>${color}
        </div>
    `;
    contenedor.innerHTML += html;
}

// Cambiar el tema del menú en tiempo real (Pregunta 4)
function cambiarTemaMenu(tema) {
    var menu = document.getElementById('menu');
    if (!menu) return;
    
    // Remover clases de tema anteriores
    menu.classList.remove('tema-claro', 'tema-oscuro', 'tema-contraste', 'tema-pastel');
    
    // Agregar la nueva clase de tema
    menu.classList.add('tema-' + tema);
    
    var msg = document.getElementById('mensaje');
    if (msg) {
        msg.innerHTML = "Tema cambiado a: " + tema;
    }
}
