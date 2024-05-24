<?php
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Inicializar la variable $resultado_personas
$resultado_personas = null;
// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $empresa = $_POST['empresa'];
    $documento = $_POST['documento'];

    $sql_verificar_nombre = "SELECT * FROM persona WHERE documento = '$documento'";
    $resultado_verificacion = $conexion->query($sql_verificar_nombre);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        // Si el producto ya existe, mostrar un mensaje de error
        echo "Error: El documento \"$documento\" ya existe en la base de datos.";
        } else {

        // Crear la consulta SQL para insertar la compra en la base de datos
        $sql_insertar = "INSERT INTO persona (documento,nombre, direccion, telefono, empresa) VALUES ('$documento','$nombre', '$direccion', '$telefono', '$empresa')";

        // Ejecutar la consulta SQL para insertar la compra
        if ($conexion->query($sql_insertar) === TRUE) {
            echo "Persona guardada exitosamente.";
        } else {
            echo "Error al guardar la persona: " . $conexion->error;
        }
    }   
}

// Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "id" (para eliminar una persona)
// Verificar si se recibió el parámetro "id_persona" en la URL para eliminar una categoría
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID de la categoría a eliminar
    $id_persona = $_GET["id"];
    // Crear la consulta SQL para eliminar la categoría con el ID especificado
    $sql_eliminar = "DELETE FROM persona WHERE id_persona=$id_persona";

    // Ejecutar la consulta SQL para eliminar la categoría
    if ($conexion->query($sql_eliminar) === TRUE) {
        echo "Categoría eliminada correctamente.";
    } else {
        echo "Error al eliminar la categoría: " . $conexion->error;
    }
}

// Obtener todas las personas de la tabla personas
$sql_personas = "SELECT * FROM persona";
$resultado_personas = $conexion->query($sql_personas);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Personas</title>
</head>
<body>
    <header>
        <nav>
            <div class="navbar">
                <div class="barnavizq">
                    <i class="fa-solid fa-house"></i>
                    <a href="inicio_sesion.html" class="incio">Inicio</a>
                    <i class="fa-solid fa-arrow-left"></i>
                    <a href="javascript: history.go(-1)" class="atras">Volver atrás</a>
                </div>
                <div class="navbarder">
                    <i class="fa-regular fa-circle-question"></i>
                    <a href="ayuda.html" class="ayuda">Ayuda</a>
                    <a href="#" onclick="minimizeWindow()" class="mini">Minimizar</a>
                    <a href="#" onclick="maximizeWindow()" class="maxi">Maximizar</a>
                    <a href="#" onclick="closeWindow()" class="cerrar">Cerrar</a>
                </div>

            </div>
        </nav>
        <nav>
            <div>
                <div class="navbargender">
                    <a href="inventario.php" class="text_inventario">Inventario</a>
                    <a href="categoria.php" class="text_inventario">Categoria</a>
                </div>
                <div class="navbargeniz">
                    <div id="fecha-hora"> <script src="fecha_hora.js"></script> </div>
                <div class="menu">
                    <button class="boton_menu" onclick="toggleMenu()">Menú</button>
                    <ul class="lista-menu" id="lista-menu">
                        <div class="textmenu">
                            <li><a href="configuracion.html"><h4>Configuracion</h4></a></li>
                            <li><a href="inicio_sesion.html"><h4>Cerrar Sesion</h4></a></li>
                        </div>
                    </ul>
                </div>
            </div>
        </nav>
        <nav>
            <div class="navbariz">
                <i class="fa-solid fa-coins" id="icono_caja2_1"></i>
                <a href="ventas.php" class="text_ventas"><h3>Ventas </h3></a>
                <i class="fa-solid fa-cart-shopping" id="icono_caja2_1"></i>
                <a href="compras.php" class="text_compras"><h3>Compras </h3></a>
                <i class="fa-solid fa-bag-shopping" id="icono_caja2_1"></i>
                <a href="productos.php" class="text_productos"><h3>Productos </h3></a>
                <i class="fa-solid fa-user" id="icono_caja2_1"></i>
                <a href="personas.php" class="text_personas"><h3>Personas </h3></a>
            </div>
        <nav>
        <div class="caja2_ventas">
            <h1 class="titleprinci">Personas</h1>
            <form method="post" class="ventas_metodo">
            <label for="documento">Documento</label>
            <input type="number" name="documento">  
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre">
            <label for="direccion">Direccion</label>
            <input type="text" name="direccion">
            <label for="telefono">Telefono</label>
            <input type="number" name="telefono">
            <label for="empresa">Empresa</label>
            <input type="text" name="empresa">
            <button type="submit">Guardar persona</button><br><br>
            </form>
        </div>
        <div class="caja2_ventas_muestra">
            <form class="tabla_compras">
            <?php

            if ($resultado_personas && $resultado_personas->num_rows > 0) {
                // Si hay ventas, imprimir una tabla HTML con los datos de las ventas
                echo "<table border='1'>";
                echo "<tr>
                <th>id_persona</th>
                <th>documento</th>
                <th>nombre</th>
                <th>direccion</th>
                <th>telefono</th>
                <th>empresa</th>
                <th>acción</th>
                </tr>";
                // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                while ($fila = $resultado_personas->fetch_assoc()) {

                    echo "<tr>";
                    echo "<td>" . $fila["id_persona"] . "</td>";
                    echo "<td><span id='documento_" . $fila["id_persona"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_persona"] . ")'>" . $fila["documento"] . "</span></td>";
                    echo "<td><span id='nombre_" . $fila["id_persona"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_persona"] . ")'>" . $fila["nombre"] . "</span></td>";
                    echo "<td><span id='direccion_" . $fila["id_persona"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_persona"] . ")'>" . $fila["direccion"] . "</span></td>";
                    echo "<td><span id='telefono_" . $fila["id_persona"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_persona"] . ")'>" . $fila["telefono"] . "</span></td>";
                    echo "<td><span id='empresa_" . $fila["id_persona"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_persona"] . ")'>" . $fila["empresa"] . "</span></td>";
                    echo "<td><a href='?id=" . $fila["id_persona"] . "'>Eliminar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // Si no hay compras registradas, imprimir un mensaje indicando que no hay ventas
                echo "No personas registradas";
            }
            ?>
        </form>
        </div>
    </header>
    <script>
    // Función para manejar la pulsación de tecla "Enter" en los campos editables
    function enter(event, id) {
        if (event.key === 'Enter') {
            // Obtener los nuevos valores de los campos editables
            var id_persona = id; // Modificar según la lógica de tu aplicación
            var documento = document.getElementById('documento_' + id).innerText;
            var nombre = document.getElementById('nombre_' + id).innerText;
            var direccion = document.getElementById('direccion_' + id).innerText;
            var telefono = document.getElementById('telefono_' + id).innerText;
            var empresa = document.getElementById('empresa_' + id).innerText;

            // Verificar los valores antes de enviar la solicitud AJAX
            console.log("ID de persona:", id_persona);
            console.log("Documento:", documento);
            console.log("Nombre:", nombre);
            console.log("Direccion:", direccion);
            console.log("Telefono:", telefono);
            console.log("Empresa:", empresa);
            
            // Enviar una solicitud AJAX al servidor PHP para actualizar los datos en la base de datos
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_data.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Manejar la respuesta del servidor si es necesario
                    console.log(xhr.responseText);
                }
            };
            xhr.send('id_persona=' + id_persona + '&documento=' + documento + '&nombre=' + nombre + '&direccion=' + direccion + '&telefono=' + telefono + '&empresa=' + empresa);

            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();
        }
    }
    </script>


<script src="script.js"></script>
<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
</body>
</html>