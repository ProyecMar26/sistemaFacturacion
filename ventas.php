
<?php
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $cantidad = $_POST["cantidad"];

    // Crear la consulta SQL para buscar el ID del producto y su precio por su nombre
    $sql = "SELECT id_productos, precio FROM producto WHERE nombre = '$nombre'";
    
    // Ejecutar la consulta SQL
    $resultado = $conexion->query($sql);
    
    // Verificar si se encontró el producto
    if ($resultado && $resultado->num_rows > 0) {
        // Obtener el ID del producto y su precio
        $fila_producto = $resultado->fetch_assoc();
        $id_productos = $fila_producto["id_productos"]; // Corregido
        $precio = $fila_producto["precio"];
        $fecha = date('Y-m-d');

        // Calcular el total
        $total = $precio * $cantidad;

        // Crear la consulta SQL para insertar la venta en la base de datos
        $sql_insertar = "INSERT INTO ventas (id_productos, fecha, cantidad, precio, total) VALUES ('$id_productos', '$fecha','$cantidad','$precio','$total')";

        // Ejecutar la consulta SQL para insertar la venta
        if ($conexion->query($sql_insertar) === TRUE) {
            echo "Venta guardada exitosamente.";
        } else {
            echo "Error al guardar la venta: " . $conexion->error;
        }
    } else {
        echo "No se encontró ningún producto con el nombre \"$nombre\"";
    }
}

// Obtener todas las ventas de la tabla ventas
$sql_ventas = "SELECT * FROM ventas";
$resultado_ventas = $conexion->query($sql_ventas);

// Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "id" (para eliminar una venta)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID de la venta a eliminar
    $id = $_GET["id"];
    // Crear la consulta SQL para eliminar la venta con el ID especificado
    $sql = "DELETE FROM ventas WHERE id_ventas=$id";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql) === TRUE) {
        // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
    } else {
        // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
    }
}


// Crear la consulta SQL para seleccionar todas las ventas de la tabla "ventas"
$sql = "SELECT id_ventas, id_productos, precio, cantidad, fecha FROM ventas";
// Ejecutar la consulta SQL
$resultado = $conexion->query($sql);




?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Ventas</title>
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
                <h1 class="titleprinci">Ventas</h1>
                <form method="post" class="ventas_metodo">  
                <label for="nombre">Producto</label>
                <input type="text" name="nombre">
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad">
                <button type="submit">Guardar Venta</button><br><br>
                <?php
    
                if ($resultado && $resultado->num_rows > 0) {
                    // Si hay ventas, imprimir una tabla HTML con los datos de las ventas
                    echo "<table border='1'>";
                    echo "<tr>
                    <th>id_ventas</th>
                    <th>id_producto</th>
                    <th>precio</th>
                    <th>cantidad</th>
                    <th>fecha</th>
                    <th>acción</th>
                    </tr>";
                    // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $fila["id_ventas"] . "</td>";
                        echo "<td><span id='id_productos_" . $fila["id_ventas"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_ventas"] . ")'>" . $fila["id_productos"] . "</span></td>";
                        echo "<td>" . $fila["precio"] . "</td>";
                        echo "<td><span id='cantidad_" . $fila["id_ventas"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_ventas"] . ")'>" . $fila["cantidad"] . "</span></td>";

                        echo "<td>" . $fila["fecha"] . "</td>";
                        echo "<td><a href='?id=" . $fila["id_ventas"] . "'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    // Si no hay ventas registradas, imprimir un mensaje indicando que no hay ventas
                    echo "No hay ventas registradas";
                }
    
    
                ?>
            </form>
            </div>
            <div class="caja2_ventas_muestra">
                <form method="get" name="nombre"> <br>
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre">
                    <button type="submit">Buscar</button>
                    <?php
                    // Establecer conexión a la base de datos
                    $conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');
                    
                    // Verificar si hay un error de conexión
                    if ($conexion->connect_error) {
                        // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
                        die("Error de conexión: " . $conexion->connect_error);
                    }
                    
                    // Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "nombre" (para buscar un  producto)
                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["nombre"])) {
                        // Obtener el nombre del producto enviado por el formulario
                        $nombre = $_GET['nombre'];
                        
                        // Crear la consulta SQL para buscar el ID del producto por su nombre
                        $sql = "SELECT id_productos, precio FROM producto WHERE nombre = '$nombre'";
                        
                        // Ejecutar la consulta SQL
                        $resultado = $conexion->query($sql);
                        
                        // Verificar si se encontró el producto
                        if ($resultado && $resultado->num_rows > 0) {
                            echo "<h2>Productos Encontrados:</h2>";
                            echo "<table border='1'>";
                            echo "<tr><th>ID</th><th>Nombre</th><th>Precio de Venta</th></tr>";
                            // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                        while ($fila = $resultado->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $fila["id_productos"] . "</td>";
                            echo "<td>" . $nombre . "</td>";
                            echo "<td>" . $fila["precio"] . "</td>";
                            echo "</tr>";
                        }
                            echo "</table>";
                        } else {
                            echo "No se encontró ningún producto con el nombre \"$nombre\"";
                            }
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
            var id_ventas = id; // Modificar según la lógica de tu aplicación
            var id_productos = document.getElementById('id_productos_' + id).innerText;
            var cantidad = document.getElementById('cantidad_' + id).innerText;

            // Verificar los valores antes de enviar la solicitud AJAX
            console.log("ID de ventas:", id_ventas);
            console.log("ID del producto:", id_productos);
            console.log("Cantidad:", cantidad);

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
            xhr.send('id_ventas=' + id_ventas + '&id_productos=' + id_productos + '&cantidad=' + cantidad);

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