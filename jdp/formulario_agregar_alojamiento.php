<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: alojamiento.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $direccion = $_POST['direccion'];
    $duracion = $_POST['duracion'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $fecha_salida = $_POST['fecha_salida'];
    $capacidad = $_POST['capacidad'];
    $seguro = $_POST['seguro'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];
    $estrellas = $_POST['estrellas'];

    $sql = "INSERT INTO alojamiento (
        nombre, imagen, direccion, duracion, fecha_ingreso,
        fecha_salida, capacidad, seguro, precio, calificacion, estrellas
    ) VALUES (
        '$nombre', '$imagen', '$direccion', '$duracion', '$fecha_ingreso',
        '$fecha_salida', '$capacidad', '$seguro', '$precio', '$calificacion', '$estrellas'
    )";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('alojamiento agregado correctamente'); window.location='alojamiento.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar alojamiento</title>
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Agregar nuevo alojamiento</h2>

<form method="POST" action="">
    <label>nombre</label>
    <input type="text" name="nombre" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" required>

    <label>direccion</label>
    <input type="text" name="direccion" required>

    <label>Duración (ej: 13 días / 12 noches)</label>
    <input type="text" name="duracion" required>

    <label>fecha ingreso</label>
    <input type="date" name="fecha_ingreso" required>

    <label>fecha_salida</label>
    <input type="date" name="fecha_salida" required>

    <label>capacidad (ej:individual, 2 personas, 4 personas)</label>
    <input type="text" name="capacidad" required>

    <label>seguro</label>
    <input type="text" name="seguro" required>

    <label>Precio</label>
    <input type="text" name="precio" required>

    <label>Calificación (ej: 4.3)</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" required>

    <label>Estrellas (ej: 3)</label>
    <input type="number" min="1" max="5" name="estrellas" required>

    <button type="submit">Agregar alojamieto</button>
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
