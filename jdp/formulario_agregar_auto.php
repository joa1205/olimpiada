<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: autos.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $capacidad = $_POST['capacidad'];
    $fecha_deposito = $_POST['fecha_deposito'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];º
    $estrellas = $_POST['estrellas'];

    $sql = "INSERT INTO autos (
         imagen, nombre, capacidad, fecha_deposito, fecha_devolucion, precio, calificacion, estrellas
    ) VALUES (
        '$imagen', '$nombre', '$capacidad', '$fecha_deposito', '$fecha_devolucion', '$precio', '$calificacion', '$estrellas'
    )";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('vehiuculo agregado correctamente'); window.location='autos.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar vehiculos</title>
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Agregar nuevo vehiculo</h2>

<form method="POST" action="">

    <label>Nombre</label>
    <input type="text" name="nombre" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" required>

    <label>capacidad (ej: 2, 4 o mas personas)</label>
    <input type="text" name="capacidad" required>

    <label>Fecha del deposito del vehiculo</label>
    <input type="date" name="fecha_deposito" required>

    <label>Fecha de devolucion del vehiculo</label>
    <input type="date" name="fecha_devolucion" required>

    <label>Precio el dia</label>
    <input type="text" name="precio" required>

    <label>Calificación (ej: 4.3)</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" required>

    <label>Estrellas (ej: 3)</label>
    <input type="number" min="1" max="5" name="estrellas" required>

    <button type="submit">Agregar vehiculo</button>
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
