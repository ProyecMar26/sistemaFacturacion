<?php
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se envió el formulario para agregar una nueva categoría
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $categoria = $_POST['categoria'];

    // Crear la consulta SQL para verificar si la categoría ya existe
    $sql_verificar_categoria = "SELECT * FROM categoria WHERE categoria = '$categoria'";
    $resultado_verificacion = $conexion->query($sql_verificar_categoria);

    if ($resultado_verificacion && $resultado_verificacion->num_rows > 0) {
        // Si la categoría ya existe, mostrar un mensaje de error
        echo "Error: La categoría \"$categoria\" ya existe en la base de datos.";
    } else {
        // Crear la consulta SQL para insertar la nueva categoría en la base de datos
        $sql_insertar = "INSERT INTO categoria (categoria) VALUES ('$categoria')";

        // Ejecutar la consulta SQL para insertar la nueva categoría
        if ($conexion->query($sql_insertar) === TRUE) {
            echo "Categoría guardada exitosamente.";
        } else {
            echo "Error al guardar la categoría: " . $conexion->error;
        }
    }
}

    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    // Obtener el ID del producto a eliminar
    $id = $_GET["id"];
    // Crear la consulta SQL para eliminar el producto con el ID especificado
    $sql_eliminar = "DELETE FROM categoria WHERE id_categoria=$id";

    // Ejecutar la consulta SQL
    if ($conexion->query($sql_eliminar) === TRUE) {
        // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
        echo "Producto eliminado correctamente.";
    } else {
        // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
        echo "Error al eliminar el producto: " . $conexion->error;
    }
}

// Obtener todas las categorías de la tabla categoría
$sql_categoria = "SELECT * FROM categoria";
$resultado_categoria = $conexion->query($sql_categoria);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Categoria</title>
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
            <h1 class="titleprinci">Categoria</h1>
            <form method="post" class="ventas_metodo">
            <label for="categoria">Categoria</label>
            <input type="text" name="categoria"> 
            <button type="submit">Guardar categoria</button><br><br>
        </form>
        </div>
        <div class="caja2_ventas_muestra">
            <form class="tabla_categoria">
            <?php

            if ($resultado_categoria && $resultado_categoria->num_rows > 0) {
                // Si hay categorias, imprimir una tabla HTML con los datos de las categorias
                echo "<table border='1'>";
                echo "<tr>
                <th>id_categoria</th>
                <th>categoria</th>
                <th>acción</th>
                </tr>";
                // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                while ($fila = $resultado_categoria->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["id_categoria"] . "</td>";
                    echo "<td>" . $fila["categoria"] . "</td>";
                    echo "<td><a href='?id=" . $fila["id_categoria"] . "'>Eliminar</a></td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // Si no hay categorias registradas, imprimir un mensaje indicando que no hay categorias
                echo "No hay categorias registradas";
            }
            ?>
        </form>
        </div>
    </header>
    <script src="script.js"></script>
    <?php
    // Cerrar la conexión a la base de datos
    $conexion->close();
    ?>
</body>
</html>
