<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: paquetes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $lugar_de_salida = $_POST['lugar_de_salida'];
    $lugar_de_llegada = $_POST['lugar_de_llegada'];
    $imagen = $_POST['imagen'];
    $fecha_ida = $_POST['fecha_ida'];
    $fecha_vuelta = $_POST['fecha_vuelta'];
    $direccion = $_POST['direccion'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_salida = $_POST['fecha_salida'];
    $paquete = $_POST['paquete'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];
    $estrellas = $_POST['estrellas'];
    $sql = "INSERT INTO paquetes (
        nombre, lugar_de_salida, lugar_de_llegada, imagen, fecha_ida, fecha_vuelta,
        direccion, fecha_ingreso, fecha_salida, paquete, precio, calificacion, estrellas
    ) VALUES (
        '$nombre', '$lugar_de_salida', '$lugar_de_llegada', '$imagen', '$fecha_ida', '$fecha_vuelta',
        '$direccion', '$fecha_ingreso','$fecha_salida', '$paquete', '$precio', '$calificacion', '$estrellas'
    )";

    if (mysqli_query($conexion, $sql)) {
    // Obtener el ID del vuelo recién insertado
    $id_paquete = mysqli_insert_id($conexion);

    // Insertar en productos con ese ID

    $id_paquete = mysqli_insert_id($conexion);
    $sql2 = "INSERT INTO productos (nombre, precio, id) VALUES ('$nombre', '$precio', '$id')";
    mysqli_query($conexion, $sql2);


    echo "<script>alert('paquete agregado correctamente'); window.location='paquetes.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
}

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar paquete de viaje</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Agregar nuevo paquete</h2>

<form method="POST" action="">

    <label>nombre</label>
    <input type="text" name="nombre" required>

    <label>Lugar de salida</label>
    <input type="text" name="lugar_de_salida" required>

    <label>Lugar de llegada</label>
    <input type="text" name="lugar_de_llegada" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" required>

    <label>Fecha de ida</label>
    <input type="date" name="fecha_ida" required>

    <label>Fecha de vuelta</label>
    <input type="date" name="fecha_vuelta" required>

    <label>Direccion del hotel</label>
    <input type="text" name="direccion" required>

    <label>fecha de ingreso del hotel</label>
    <input type="date" name="fecha_ingreso" required>

    <label>fecha de salida del hotel</label>
    <input type="date" name="fecha_salida" required>

    <label>Paquete(individual, grupal, familiar)</label>
    <input type="text" name="paquete" required>

    <label>Precio</label>
    <input type="text" name="precio" required>

    <label>Calificación (ej: 4.3)</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" required>

    <label>Estrellas (ej: 3)</label>
    <input type="number" min="1" max="5" name="estrellas" required>

    <button type="submit">Agregar paquete</button>
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
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
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
        color: white;
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