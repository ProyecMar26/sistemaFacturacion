<?php
//modificacion ventas
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos enviados por POST
$id_ventas = $_POST["id_ventas"];
$id_productos = $_POST["id_productos"];
$cantidad = $_POST["cantidad"];

// Crear la consulta SQL para actualizar los datos en la tabla "ventas"
$sql_ventas = "UPDATE ventas SET id_productos = '$id_productos', cantidad = '$cantidad' WHERE id_ventas = $id_ventas";

// Ejecutar la consulta SQL
if ($conexion->query($sql_ventas) === TRUE) {
    // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
    echo "Datos actualizados correctamente";
} else {
    // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
    echo "Error al actualizar los datos: " . $conexion->error;
}

$conexion->close();
?>



<?php
//modificacion compras

// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos enviados por POST
$id_compras = $_POST["id_compras"];
$id_productos = $_POST["id_productos"];
$cantidad = $_POST["cantidad"];

// Crear la consulta SQL para actualizar los datos en la tabla "ventas"
$sql_compras = "UPDATE compras SET id_productos = '$id_productos', cantidad = '$cantidad' WHERE id_compras = $id_compras";

// Ejecutar la consulta SQL
if ($conexion->query($sql_compras) === TRUE) {
    // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
    echo "Datos actualizados correctamente";
} else {
    // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
    echo "Error al actualizar los datos: " . $conexion->error;
}

$conexion->close();
?>


<?php
//modificacion productos
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos enviados por POST
$id_productos = $_POST["id_productos"];
$id_categoria = $_POST["id_categoria"];
$nombre = $_POST["nombre"];
$precio_compra = $_POST["precio_compra"];


// Crear la consulta SQL para actualizar los datos en la tabla "ventas"
$sql_producto = "UPDATE producto SET id_categoria = '$id_categoria', nombre = '$nombre', precio_compra = '$precio_compra' WHERE id_productos = $id_productos";

// Ejecutar la consulta SQL
if ($conexion->query($sql_producto) === TRUE) {
    // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
    echo "Datos actualizados correctamente";
    $sql_precio_compra = "SELECT precio_compra FROM producto WHERE id_categoria = '$id_categoria' AND         nombre = '$nombre'";
        
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
                $sql_actualizar_precio = "UPDATE producto SET precio = $nuevo_precio WHERE id_productos = $id_productos";

                // Ejecutar la consulta SQL para actualizar el precio de compra
                if ($conexion->query($sql_actualizar_precio) === TRUE) {
                    
                } else {
                    echo "Error al actualizar el precio de compra en la tabla de productos: " . $conexion->error;
                }

} else {
    // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
    echo "Error al actualizar los datos: " . $conexion->error;
}
}

$conexion->close();
?>




<?php
//modificacion personas
// Establecer conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'sistema_facturacion');

// Verificar si hay un error de conexión
if ($conexion->connect_error) {
    // Si hay un error, imprimir un mensaje de error y terminar la ejecución del script
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener los datos enviados por POST
$id_persona = $_POST["id_persona"];
$documento = $_POST["documento"];
$nombre = $_POST["nombre"];
$direccion = $_POST["direccion"];
$telefono = $_POST["telefono"];
$empresa = $_POST["empresa"];

// Crear la consulta SQL para actualizar los datos en la tabla "ventas"
$sql_persona = "UPDATE persona SET documento = '$documento', nombre = '$nombre', direccion = '$direccion', telefono = '$telefono', empresa = '$empresa' WHERE id_persona = $id_persona";

// Ejecutar la consulta SQL
if ($conexion->query($sql_persona) === TRUE) {
    // Si la consulta se ejecutó correctamente, imprimir un mensaje de éxito
    echo "Datos actualizados correctamente";
} else {
    // Si ocurrió un error al ejecutar la consulta, imprimir un mensaje de error
    echo "Error al actualizar los datos: " . $conexion->error;
}

$conexion->close();
?>
