<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: alojamientos.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $mapalink = $_POST['mapalink'];
    $direccion = $_POST['direccion'];
    $duracion = $_POST['duracion'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_salida = $_POST['fecha_salida'];
    $capacidad = $_POST['capacidad'];
    $seguro = $_POST['seguro'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];
    $estrellas = $_POST['estrellas'];

    // Primero insertamos en productos sin especificar el id
    $sqlProductos = "INSERT INTO productos (nombre, precio) VALUES ('$nombre', '$precio')";

    if ( mysqli_query($conexion, $sqlProductos) ) {
        // Obtenemos el id autoincrementado de productos
        $id_producto = mysqli_insert_id($conexion);

        // Después insertamos en alojamiento utilizando el id del producto
        $sqlAlojamientos = "INSERT INTO alojamiento (id, nombre, imagen, mapalink, direccion, duracion, fecha_ingreso, fecha_salida, capacidad, seguro, precio, calificacion, estrellas) 
        VALUES ($id_producto, '$nombre', '$imagen', '$mapalink', '$direccion', '$duracion', '$fecha_ingreso', '$fecha_salida', '$capacidad', '$seguro', '$precio', '$calificacion', '$estrellas')";

        if ( mysqli_query($conexion, $sqlAlojamientos) ) {

            // Actualizamos el producto para que tenga el id_alojamientos
            $update = "UPDATE productos SET id_alojamiento=$id_producto WHERE id=$id_producto";

            if ( mysqli_query($conexion, $update) ) {
                echo "<script>alert('Registro agregado correctamente'); window.location='alojamientos.php'</script>";
            } else {
                echo "Error al guardar el producto: " . mysqli_error($conexion);
            }

        } else {
            echo "Error al guardar el alojamiento: " . mysqli_error($conexion);
        }
    } else {
        echo "Error al guardar el producto: " . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar alojamiento</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Agregar nuevo alojamiento</h2>

<form method="POST" action="">
    <label>Nombre</label>
    <input type="text" name="nombre" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" required>

    <label>Link del mapa</label>
    <input type="text" name="mapalink" required>

    <label>Dirección</label>
    <input type="text" name="direccion" required>

    <label>Duración (ej: 13 días / 12 noches)</label>
    <input type="text" name="duracion" required>

    <label>Fecha de ingreso</label>
    <input type="date" name="fecha_ingreso" required>

    <label>Fecha de salida</label>
    <input type="date" name="fecha_salida" required>

    <label>Capacidad (ej: 2, 4, 6)</label>
    <input type="text" name="capacidad" required>

    <label>Seguro</label>
    <input type="text" name="seguro" required>

    <label>Precio</label>
    <input type="text" name="precio" required>

    <label>Calificación (ej: 4.3)</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" required>

    <label>Estrellas (ej: 3)</label>
    <input type="number" min="1" max="5" name="estrellas" required>

    <button type="submit">Agregar Alojamiento</button>
</form>


</body>
</html>

<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: #f4f4f4;
        padding: 40px;
    }
    h2 {
        margin-bottom: 30px;
    }
    form {
        background: white;
        max-width: 600px;
        margin: auto;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 0 15px rgb(0,0,0,0.1);
    }
    label {
        display: block;
        margin-top: 15px;
        font-weight: bold;
        color: #333;
    }
    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-top: 5px;
    }
    button {
        margin-top: 25px;
        padding: 12px;
        background-color: #3f0071;
        color: #fff;
        font-size: 16px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: 0.3s ease;
    }
    button:hover {
        background-color: #5b0cbf;
    }
</style>
