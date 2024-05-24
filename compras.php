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
    $nombre_producto = $_POST['nombre'];
    $cantidad = $_POST["cantidad"];
    $precio_compra = $_POST['precio_compra'];
    $persona = $_POST["persona"];

    // Crear la consulta SQL para buscar el ID del producto y su precio por su nombre
    $sql_producto = "SELECT id_productos, precio_compra FROM producto WHERE nombre = '$nombre_producto'";
    
    // Ejecutar la consulta SQL
    $resultado_producto = $conexion->query($sql_producto);
    
    // Verificar si se encontró el producto
    if ($resultado_producto && $resultado_producto->num_rows > 0) {
        // Obtener el ID del producto y su precio de compra actual
        $fila_producto = $resultado_producto->fetch_assoc();
        $id_producto = $fila_producto["id_productos"];
        $precio_compra_actual = $fila_producto["precio_compra"];

        // Crear la consulta SQL para buscar el ID de la persona por su nombre
        $sql_persona = "SELECT id_persona FROM persona WHERE nombre = '$persona'";
        
        // Ejecutar la consulta SQL
        $resultado_persona = $conexion->query($sql_persona);
        
        // Verificar si se encontró la persona
        if ($resultado_persona && $resultado_persona->num_rows > 0) {
            // Obtener el ID de la persona
            $fila_persona = $resultado_persona->fetch_assoc();
            $id_persona = $fila_persona["id_persona"];

            // Obtener la fecha actual
            $fecha = date('Y-m-d');

            // Crear la consulta SQL para insertar la compra en la base de datos
            $sql_insertar = "INSERT INTO compras (id_productos, id_persona, cantidad, precio_compra, fecha) 
                             VALUES ('$id_producto', '$id_persona', '$cantidad', '$precio_compra', '$fecha')";

            // Ejecutar la consulta SQL para insertar la compra
            if ($conexion->query($sql_insertar) === TRUE) {
                
                // Obtener el precio de compra de la tabla compras
            $sql_precio_compra = "SELECT precio_compra FROM compras WHERE id_productos = '$id_producto' AND         id_persona = '$id_persona' AND cantidad = '$cantidad' AND fecha = '$fecha'";
        
            // Ejecutar la consulta SQL para obtener el precio de compra
            $resultado_precio_compra = $conexion->query($sql_precio_compra);
            
            if ($resultado_precio_compra && $resultado_precio_compra->num_rows > 0) {
                // Obtener el precio de compra de la fila resultante
                $fila_precio_compra = $resultado_precio_compra->fetch_assoc();
                $precio_compra_nuevo = $fila_precio_compra["precio_compra"];

                // Actualizar el precio de compra en la tabla de productos
                $nuevo_precio_compra = $precio_compra_nuevo; // El nuevo precio de compra es igual al precio actual
                $nuevo_precio = $nuevo_precio_compra * 1.2; // Calcular el nuevo precio

                // Crear la consulta SQL para actualizar el precio de compra en la tabla de productos
                $sql_actualizar_precio = "UPDATE producto SET precio_compra = $nuevo_precio_compra, precio = $nuevo_precio WHERE id_productos = $id_producto";

                // Ejecutar la consulta SQL para actualizar el precio de compra
                if ($conexion->query($sql_actualizar_precio) === TRUE) {
                    
                } else {
                    echo "Error al actualizar el precio de compra en la tabla de productos: " . $conexion->error;
                }
            } else {
                echo "Error al guardar la compra: " . $conexion->error;
            }
        } else {
            echo "No se encontró ninguna persona con el nombre \"$persona\"";
        }
    } else {
        echo "No se encontró ningún producto con el nombre \"$nombre_producto\"";
    }
}}

// Obtener todas las compras de la tabla compras
$sql_compras = "SELECT * FROM compras";
$resultado_compras = $conexion->query($sql_compras);

// Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "id" (para eliminar una compra)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID de la compra a eliminar
    $id = $_GET["id"];
    // Crear la consulta SQL para eliminar la compra con el ID especificado
    $sql_eliminar = "DELETE FROM compras WHERE id_compras=$id";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql_eliminar) === TRUE) {
        // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
        
    } else {
        // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
        echo "Error al eliminar la compra: " . $conexion->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Compras</title>
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
                <h1 class="titleprinci">Compras</h1>
                <form method="post" class="ventas_metodo">  
                <label for="nombre">Producto</label>
                <input type="text" name="nombre">
                <label for="cantidad">Cantidad</label>
                <input type="number" name="cantidad">
                <label for="precio_compra" id="precio_compra" oninput="actualizarPrecioCompra(this.value)">Precio compra</label>
                <input type="number" name="precio_compra">
                <label for="persona">Persona</label>
                <input type="text" name="persona">
                <button type="submit">Guardar Venta</button><br><br>
            </form>
            </div>
            <div class="caja2_ventas_muestra">
                <button class="ver_ventas"><a href="ventas.php">Ver ventas</a></button>
                <form class="tabla_compras">
                <?php
    
                if ($resultado_compras && $resultado_compras->num_rows > 0) {
                    // Si hay ventas, imprimir una tabla HTML con los datos de las ventas
                    echo "<table border='1'>";
                    echo "<tr>
                    <th>id_compras</th>
                    <th>id_producto</th>
                    <th>precio</th>
                    <th>cantidad</th>
                    <th>fecha</th>
                    <th>acción</th>
                    </tr>";
                    // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                    while ($fila = $resultado_compras->fetch_assoc()) {
    
                        echo "<tr>";
                        echo "<td>" . $fila["id_compras"] . "</td>";
                        echo "<td><span id='id_productos_" . $fila["id_compras"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_compras"] . ")'>" . $fila["id_productos"] . "</span></td>";
                        echo "<td><span id='precio_" . $fila["id_compras"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_compras"] . ")'>" . $fila["precio_compra"] . "</span></td>";
                        echo "<td><span id='cantidad_" . $fila["id_compras"] . "' class='editable' contenteditable='true' onkeypress='enter(event, " . $fila["id_compras"] . ")'>" . $fila["cantidad"] . "</span></td>";

                        echo "<td>" . $fila["fecha"] . "</td>";
                        echo "<td><a href='?id=" . $fila["id_compras"] . "'>Eliminar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    // Si no hay compras registradas, imprimir un mensaje indicando que no hay ventas
                    echo "No hay compras registradas";
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
            var id_compras = id; // Modificar según la lógica de tu aplicación
            var id_productos = document.getElementById('id_productos_' + id).innerText;
            var cantidad = document.getElementById('cantidad_' + id).innerText;

            // Verificar los valores antes de enviar la solicitud AJAX
            console.log("ID de compras:", id_compras);
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
            xhr.send('id_compras=' + id_compras + '&id_productos=' + id_productos + '&cantidad=' + cantidad);

            // Evitar que el formulario se envíe automáticamente
            event.preventDefault();
        }
    }

    function actualizarPrecioCompra(nuevoPrecioCompra) {
    // Enviar el nuevo precio de compra al servidor
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'productos.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            console.log(xhr.responseText); // Manejar la respuesta del servidor si es necesario
        }
    };
    xhr.send('nuevo_precio_compra=' + nuevoPrecioCompra);
}

    </script>




<script src="script.js"></script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conexion->close();
?>