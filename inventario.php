<?php
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para borrar todos los registros existentes en la tabla 'inventario'
$sql_borrar_inventario = "DELETE FROM inventario";

// Ejecutar la consulta SQL para borrar los registros existentes
if ($conexion->query($sql_borrar_inventario) === TRUE) {
    
} else {
    
}

// Consulta para obtener la cantidad total de cada producto
$sql_cantidad_total = "SELECT id_productos, SUM(cantidad) AS cantidad_total FROM (SELECT id_productos, cantidad FROM compras UNION ALL SELECT id_productos, -cantidad FROM ventas) AS t GROUP BY id_productos";

// Ejecutar la consulta SQL
$resultado_cantidad_total = $conexion->query($sql_cantidad_total);

// Verificar si se obtuvieron resultados
if ($resultado_cantidad_total && $resultado_cantidad_total->num_rows > 0) {
    // Recorrer los resultados y actualizar el inventario
    while ($fila = $resultado_cantidad_total->fetch_assoc()) {
        // Obtener el ID del producto y la cantidad total
        $id_producto = $fila["id_productos"];
        $cantidad_total = $fila["cantidad_total"];
        
        // Consulta para obtener el nombre del producto
        $sql_nombre_producto = "SELECT nombre FROM producto WHERE id_productos = '$id_producto'";
        $resultado_nombre_producto = $conexion->query($sql_nombre_producto);
        
        // Verificar si se obtuvo el nombre del producto
        if ($resultado_nombre_producto && $resultado_nombre_producto->num_rows > 0) {
            // Obtener el nombre del producto
            $nombre_producto = $resultado_nombre_producto->fetch_assoc()["nombre"];
            
            // Insertar los datos en la tabla inventario
            $sql_insertar = "INSERT INTO inventario (id_productos, nombre, cantidad) VALUES ('$id_producto', '$nombre_producto', '$cantidad_total')";
            
            // Ejecutar la consulta de inserción
            if ($conexion->query($sql_insertar) === TRUE) {
            
            } else {

            }
        } else {
           
        }
    }
} else {

}

// Consulta para obtener los productos más vendidos en los últimos 30 días
$sql_mas_vendidos = "SELECT p.nombre AS nombre_producto, SUM(v.cantidad) AS total_ventas 
                     FROM ventas v
                     INNER JOIN producto p ON v.id_productos = p.id_productos
                     WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                     GROUP BY v.id_productos 
                     ORDER BY total_ventas DESC LIMIT 1";

$resultado_mas_vendidos = $conexion->query($sql_mas_vendidos);

// Consulta para obtener los productos menos vendidos en los últimos 30 días
$sql_menos_vendidos = "SELECT p.nombre AS nombre_producto, SUM(v.cantidad) AS total_ventas 
                       FROM ventas v
                       INNER JOIN producto p ON v.id_productos = p.id_productos
                       WHERE v.fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                       GROUP BY v.id_productos 
                       ORDER BY total_ventas ASC LIMIT 1";

$resultado_menos_vendidos = $conexion->query($sql_menos_vendidos);

// Consulta para obtener el total de ventas mensual
$sql_total_ventas_mensual = "SELECT SUM(total_ventas) AS total_mensual 
                             FROM (SELECT SUM(precio * cantidad) AS total_ventas 
                                   FROM ventas 
                                   WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) 
                                   GROUP BY DATE(fecha)) AS ventas_por_dia";

$resultado_total_ventas_mensual = $conexion->query($sql_total_ventas_mensual);

// Consulta para obtener el total de ventas semanal
$sql_total_ventas_semanal = "SELECT SUM(total_ventas) AS total_semanal 
                             FROM (SELECT SUM(precio * cantidad) AS total_ventas 
                                   FROM ventas 
                                   WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                                   GROUP BY WEEK(fecha)) AS ventas_por_semana";

$resultado_total_ventas_semanal = $conexion->query($sql_total_ventas_semanal);

// Consulta para obtener el total de ventas anual
$sql_total_ventas_anual = "SELECT SUM(total_ventas) AS total_anual 
                           FROM (SELECT SUM(precio * cantidad) AS total_ventas 
                                 FROM ventas 
                                 WHERE fecha >= DATE_SUB(CURDATE(), INTERVAL 365 DAY) 
                                 GROUP BY MONTH(fecha)) AS ventas_por_mes";

$resultado_total_ventas_anual = $conexion->query($sql_total_ventas_anual);


// Consulta para obtener los productos con poca cantidad en inventario
$sql_productos_poca_cantidad = "SELECT nombre, cantidad 
                                FROM inventario 
                                WHERE cantidad < 15";

$resultado_productos_poca_cantidad = $conexion->query($sql_productos_poca_cantidad);
// Cerrar la conexión a la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/0e589f85bf.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;400;500;600;700&display=swap">
    <title>Inventario</title>

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
            <h1 class="titleprinci">Inventario</h1>
            <?php
            // Establecer conexión a la base de datos
            $conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');
            
            // Verificar si hay un error de conexión
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }
            // Consulta para obtener todos los registros de la tabla 'inventario'
            $sql_inventario_completo = "SELECT id_productos, nombre, cantidad FROM inventario";
            
            // Ejecutar la consulta SQL
            $resultado_inventario_completo = $conexion->query($sql_inventario_completo);
            
            // Verificar si se obtuvieron resultados
            if ($resultado_inventario_completo && $resultado_inventario_completo->num_rows > 0) {
                // Mostrar la tabla de inventario
                echo "<table>";
                echo "<tr>
                        <th>ID Producto</th>
                        <th>Nombre</th>
                        <th>Cantidad</th>
                      </tr>";
                while ($fila = $resultado_inventario_completo->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["id_productos"] . "</td>";
                    echo "<td>" . $fila["nombre"] . "</td>";
                    echo "<td>" . $fila["cantidad"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                // Mostrar un mensaje si no hay productos en el inventario
                echo "No se encontraron productos en el inventario.";
            }
            ?>
        </div>
        <div class="caja2_inventario_muestra">
            <form method="get" name="nombre"> <br>
                <label for="nombre">Nombre del Producto:</label>
                <input type="text" id="nombre" name="nombre">
                <button type="submit">Buscar</button><br><br>
            </form>
        
            <?php
            // Establecer conexión a la base de datos
            $conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');
        
            // Verificar si hay un error de conexión
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }
        
            // Verificar si la solicitud HTTP es de tipo GET y si se recibió el parámetro "nombre" (para buscar         un producto)
        
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["nombre"])) {
                // Obtener el nombre del producto enviado por el formulario
                $nombre = $_GET['nombre'];
        
                // Crear la consulta SQL para buscar el producto en el inventario
                $sql = "SELECT id_productos, nombre, cantidad FROM inventario WHERE nombre = '$nombre'";
        
                // Ejecutar la consulta SQL
                $resultado = $conexion->query($sql);
        
                // Verificar si se encontró el producto
                if ($resultado && $resultado->num_rows > 0) {
                    echo "<br>";
                    echo "<table class='tabla_general1'>";
                    echo "<tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cantidad</th>
                        </tr>";
                    // Iterar sobre cada fila de resultados y mostrar los datos en la tabla
                    while ($fila = $resultado->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $fila["id_productos"] . "</td>";
                        echo "<td>" . $fila["nombre"] . "</td>";
                        echo "<td>" . $fila["cantidad"] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "No se encontró ningún producto con el nombre \"$nombre\"";
                }
            }
            ?>
            <form method="post" name="analisis"> <br>
            <button type="submit" name="submit_analisis">Análisis de productos</button>
            <br><br>
            </form>

            <?php
            // Establecer conexión a la base de datos
            $conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');
            
            // Verificar si hay un error de conexión
            if ($conexion->connect_error) {
                die("Error de conexión: " . $conexion->connect_error);
            }
            
            // Verificar si se envió el formulario de análisis de productos
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_analisis"])) {

            //tabla numero 1    
                echo "<h3 class='texto1'>Producto mas vendido</h3>";
                echo "<br>";
                echo "<table class ='tabla1'>";
                echo "<tr>
                        <th>Nombre Producto</th>
                        <th>Total Ventas</th>
                    </tr>";
            while ($fila = $resultado_mas_vendidos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["nombre_producto"] . "</td>";
                echo "<td>" . $fila["total_ventas"] . "</td>";
                echo "</tr>";
            }
                echo "</table><br>";

            //tabla numero dos    
                echo "<h3 class='texto2'>Producto menos vendido</h3>";
                echo "<br>";
                echo "<table class ='tabla2'>";
                echo "<tr>
                        <th>Nombre Producto</th>
                        <th>Total Ventas</th>
                    </tr>";
            while ($fila = $resultado_menos_vendidos->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $fila["nombre_producto"] . "</td>";
                echo "<td>" . $fila["total_ventas"] . "</td>";
                echo "</tr>";
            }
                echo "</table><br>";

            //la tabla 3
                echo "<h3 class='texto3'>Ventas</h3>";
                echo "<br>";
                echo "<table class ='tabla3'>";
                echo "<tr>
                        <th>Ventas Semanales</th>
                        <th>Ventas Mensuales</th>
                        <th>Ventas Anuales</th>
                    </tr>";
                echo "<tr>";
                // Promedio Semanal
                    echo "<td>";
                    if ($resultado_total_ventas_semanal && $resultado_total_ventas_semanal->num_rows > 0) {
                        $fila_semanal = $resultado_total_ventas_semanal->fetch_assoc();
                        echo $fila_semanal["total_semanal"];
                    } else {
                        echo "No disponible";
                    }
                    echo "</td>";
                // Promedio Mensual
                    echo "<td>";
                    if ($resultado_total_ventas_mensual && $resultado_total_ventas_mensual->num_rows > 0) {
                        $fila_mensual = $resultado_total_ventas_mensual->fetch_assoc();
                        echo $fila_mensual["total_mensual"];
                    } else {
                        echo "No disponible";
                    }
                    echo "</td>";
                // Promedio Anual
                    echo "<td>";
                    if ($resultado_total_ventas_anual && $resultado_total_ventas_anual->num_rows > 0) {
                        $fila_anual = $resultado_total_ventas_anual->fetch_assoc();
                        echo $fila_anual["total_anual"];
                    } else {
                        echo "No disponible";
                    }
                    echo "</td>";
                echo "</tr>";
                echo "</table><br>";

            //tabla 4
                echo "<h3 class='texto4'>Productos con poca cantidad</h3>";
                echo "<br>";
                echo "<table class ='tabla4'>";
                echo "<tr>
                        <th>Nombre Producto</th>
                        <th>Cantidad</th>
                    </tr>";
                while ($fila = $resultado_productos_poca_cantidad->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $fila["nombre"] . "</td>";
                    echo "<td>" . $fila["cantidad"] . "</td>";
                    echo "</tr>";
                }   
                echo "</table><br>";
     
    }
            // Cerrar la conexión a la base de datos
            $conexion->close();
            ?>

        </div>
    </header>
    <script src="script.js"></script>
</body>
</html>