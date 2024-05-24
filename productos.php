<?php

// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("<span style='color:red;'>Error de conexión: </span>" . $conexion->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre_producto = $_POST['nombre'];
    $categoria = $_POST['categoria'];
    $precio_compra = $_POST['precio_compra'];


    $sql_verificar_nombre = "SELECT * FROM producto WHERE nombre = '$nombre_producto'";
    $resultado_verificacion = $conexion->query($sql_verificar_nombre);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        // Si el producto ya existe, mostrar un mensaje de error
        echo "Error: El producto \"$nombre_producto\" ya existe en la base de datos.";
    } else {

    // Crear la consulta SQL para buscar el ID de la categoría por su nombre
    $sql_categoria = "SELECT id_categoria FROM categoria WHERE LOWER(categoria) = '$categoria'";
    
    // Ejecutar la consulta SQL para obtener el ID de la categoría
    $resultado_categoria = $conexion->query($sql_categoria);
    
    // Verificar si se encontró la categoría
    if ($resultado_categoria && $resultado_categoria->num_rows > 0) {
        // Obtener el ID de la categoría
        $fila_categoria = $resultado_categoria->fetch_assoc();
        $id_categoria = $fila_categoria["id_categoria"];

        $porcentaje_aumento = 20; // Porcentaje de aumento del precio
        $precio = $precio_compra * (1 + $porcentaje_aumento / 100);

        // Crear la consulta SQL para insertar el producto en la base de datos
        $sql_insertar = "INSERT INTO producto (id_categoria, nombre, precio_compra, precio) 
                         VALUES ('$id_categoria', '$nombre_producto', '$precio_compra', '$precio')";

        // Ejecutar la consulta SQL para insertar el producto
        if ($conexion->query($sql_insertar) === TRUE) {
            echo "Compra guardada exitosamente.";
        } else {
            echo "Error al guardar la compra: " . $conexion->error;
        }
    } else {
        echo "No se encontró ninguna categoria con el nombre \"$categoria\"";
    }
}}

// Obtener todas las categorías de la tabla categoría
$sql_categorias = "SELECT * FROM categoria";
$resultado_categorias = $conexion->query($sql_categorias);

// Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "id" (para eliminar un producto)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID del producto a eliminar
    $id = $_GET["id"];
    // Crear la consulta SQL para eliminar el producto con el ID especificado
    $sql_eliminar = "DELETE FROM producto WHERE id_productos=$id";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql_eliminar) === TRUE) {
        // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
        echo "Producto eliminado correctamente.";
    } else {
        // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
        echo "Error al eliminar el producto: " . $conexion->error;
    }
}

// Obtener todos los productos de la tabla producto
$sql_productos = "SELECT * FROM producto";
$resultado_productos = $conexion->query($sql_productos);

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Productos</title>
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
        <main>
            <div class="caja2_ventas">
                <h1 class="titleprinci">Productos</h1>
                <form method="post" class="ventas_metodo">  
                <label for="nombre">Producto</label>
                <input type="text" name="nombre">
                <label for="precio_compra">Precio compra</label>
                <input type="text" name="precio_compra">
                <label for="categoria">Categoría</label>
                <select name="categoria" required>
                <?php
                // Mostrar opciones de categorías
                if ($resultado_categorias && $resultado_categorias->num_rows > 0) {
                    while ($fila = $resultado_categorias->fetch_assoc()) {
                        echo "<option value=\"" . $fila["categoria"] . "\">" . $fila["categoria"] . "</option>";
                    }
                } else {
                    echo "<option value=\"\">No hay categorías disponibles</option>";
                }
                ?>
            </select><br><br>
                <button type="submit">Guardar Producto</button><br><br>
            </form>
            </div>
            <div class="caja2_ventas_muestra">
                <button class="ver_ventas"><a href="inventario.php">INVENTARIO</a></button>
                <form class="tabla_compras">
                <?php

                    if ($resultado_productos && $resultado_productos->num_rows > 0) {
                    // Si hay ventas, imprimir una tabla HTML con los datos de las ventas
                    echo "<table border='1'>";
                    echo "<tr>
                    <th>id_producto</th>
                    <th>id_categoria</th>
                    <th>nombre</th>
                    <th>precio_compra</th>
                    <th>precio</th>
                    <th>acción</th>
                    </tr>";
                    // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                    while ($fila = $resultado_productos->fetch_assoc()) {

                        echo "<tr>";
                        echo "<td>" . $fila["id_productos"] . "</td>";
                        echo "<td><span id='id_categoria_" . $fila["id_productos"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_productos"] . ")'>" . $fila["id_categoria"] . "</span></td>";
                        echo "<td><span id='nombre_" . $fila["id_productos"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_productos"] . ")'>" . $fila["nombre"] . "</span></td>";
                        echo "<td><span id='precio_compra_" . $fila["id_productos"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_productos"] . ")'>" . $fila["precio_compra"] . "</span></td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td><a href='?id=" . $fila["id_productos"] . "'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    // Si no hay compras registradas, imprimir un mensaje indicando que no hay ventas
                    echo "No hay productos registrados";
                }
                ?>
            </form>
            </div>
        </main>
    </header>
    <script>
    // Función para manejar la pulsación de tecla "Enter" en los campos editables
    function enter(event, id) {
        if (event.key === 'Enter') {
            // Obtener los nuevos valores de los campos editables
            var id_productos = id; // Modificar según la lógica de tu aplicación
            var id_categoria = document.getElementById('id_categoria_' + id).innerText;
            var nombre = document.getElementById('nombre_' + id).innerText;
            var precio_compra = document.getElementById('precio_compra_' + id).innerText;

            // Verificar los valores antes de enviar la solicitud AJAX
            console.log("ID de producto:", id_productos);
            console.log("ID de la categoria:", id_categoria);
            console.log("Nombre:", nombre);
            console.log("precio_compra:", precio_compra);
            
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
            xhr.send('id_productos=' + id_productos + '&id_categoria=' + id_categoria + '&nombre=' + nombre + '&precio_compra=' + precio_compra);

            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();
        }
    }
    </script>


<script src="script.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>
