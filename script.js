function redireccionar() {
  window.location.href = 'pagina_principal';
}
// fechaHora.js

// Función para obtener la fecha y hora actual
function obtenerFechaHora() {
    // Crear un objeto de fecha
    var fechaHora = new Date();

    // Obtener los componentes de la fecha y hora
    var dia = fechaHora.getDate();
    var mes = fechaHora.getMonth() + 1; // Se suma 1 porque los meses van de 0 a 11
    var año = fechaHora.getFullYear();
    var horas = fechaHora.getHours();
    var minutos = fechaHora.getMinutes();
    var segundos = fechaHora.getSeconds();

    // Formatear la salida (agregar ceros a la izquierda si es necesario)
    if (mes < 10) {
        mes = '0' + mes;
    }
    if (dia < 10) {
        dia = '0' + dia;
    }
    if (horas < 10) {
        horas = '0' + horas;
    }
    if (minutos < 10) {
        minutos = '0' + minutos;
    }
    if (segundos < 10) {
        segundos = '0' + segundos;
    }

    // Construir la cadena de fecha y hora
    var fechaHoraActual = dia + '/' + mes + '/' + año + ' ' + horas + ':' + minutos + ':' + segundos;

    // Devolver la cadena
    return fechaHoraActual;
}

// Actualizar la fecha y hora en el elemento con id "fecha-hora"
function actualizarFechaHora() {
    var fechaHoraElemento = document.getElementById('fecha-hora');
    fechaHoraElemento.textContent = obtenerFechaHora();
}

// Llamar a la función una vez para mostrar la fecha y hora inicial
actualizarFechaHora();

// Actualizar la fecha y hora cada segundo
setInterval(actualizarFechaHora, 1000);



// Función para cambiar entre temas claro y oscuro
function toggleTheme() {
  document.body.classList.toggle('dark-theme'); // Alternar la clase dark-theme en el body
}

// Event listener para el botón de cambio de tema
document.getElementById('theme-toggle').addEventListener('click', toggleTheme);





// Obtener referencia al elemento de texto
var textoElement = document.getElementById('texto');

// Función para aumentar el tamaño de la fuente
function aumentarTamaño() {
  // Obtener el tamaño actual de la fuente
  var fontSize = window.getComputedStyle(textoElement).fontSize;
  // Convertir el tamaño de la fuente a un número entero
  var currentSize = parseFloat(fontSize);
  // Aumentar el tamaño de la fuente
  textoElement.style.fontSize = (currentSize + 2) + 'px';
}

// Función para disminuir el tamaño de la fuente
function disminuirTamaño() {
  // Obtener el tamaño actual de la fuente
  var fontSize = window.getComputedStyle(textoElement).fontSize;
  // Convertir el tamaño de la fuente a un número entero
  var currentSize = parseFloat(fontSize);
  // Disminuir el tamaño de la fuente
  textoElement.style.fontSize = (currentSize - 2) + 'px';
}

// Agregar manejadores de eventos a los botones
document.getElementById('aumentar').addEventListener('click', aumentarTamaño);
document.getElementById('disminuir').addEventListener('click', disminuirTamaño);

function toggleMenu() {
  var listaMenu = document.getElementById("lista-menu");
  if (listaMenu.style.display === "block") {
    listaMenu.style.display = "none";
  } else {
    listaMenu.style.display = "block";
  }
}





document.getElementById("modoOscuroBtn").addEventListener("click", function() {
    document.body.classList.toggle("dark-mode");
});






const inputFrutas = document.getElementById("fuentes");
    const datalist = document.getElementById("tipo_fuentes");

    inputtipo_fuente.addEventListener("input", function() {
        const valorInput = inputtipo_fuente.value.toLowerCase();
        const opciones = datalist.querySelectorAll("option");
        
        opciones.forEach(function(opcion) {
            const valorOpcion = opcion.value.toLowerCase();
            if (valorOpcion.includes(valorInput)) {
                opcion.style.display = "block";
            } else {
                opcion.style.display = "none";
            }
        });
    });

function agregarFila() {
    var table = document.getElementById("miTabla");
    var rowCount = table.rows.length;
    var newRow = table.insertRow(rowCount);

    // Autoincrementar ID
    var id = rowCount;
    newRow.dataset.id = id;

    // Insertar celdas editables
    var cell1 = newRow.insertCell(0);
    var cell2 = newRow.insertCell(1);
    var cell3 = newRow.insertCell(2);
    var cell4 = newRow.insertCell(3);
    var cell5 = newRow.insertCell(4);

    cell1.innerHTML = id;
    cell2.contentEditable = "true";
    cell3.contentEditable = "true";
    cell4.contentEditable = "true";
    cell5.contentEditable = "true";

}


    // function agregarFila() {
    //var contadorID = 1;
    //var Producto = document.getElementById("Producto").value;
    //var Precio = document.getElementById("Precio").value;
    //var Cantidad = document.getElementById("Cantidad").value;
    //var Fecha = document.getElementById("Fecha").value;


    //var table = document.getElementById("miTabla");
    //var row = table.insertRow();

    //var cell1 = row.insertCell(0);
    //var cell2 = row.insertCell(1);
    //var cell3 = row.insertCell(2);
    //var cell4= row.insertCell(3);
    //var cell5 = row.insertCell(4);

    //cell1.innerHTML = contadorID++;
    //cell2.innerHTML = Producto;
    //cell3.innerHTML = Precio;
    //cell4.innerHTML = Cantidad;
    //}
