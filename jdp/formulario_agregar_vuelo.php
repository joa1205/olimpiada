<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: vuelos.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lugar_salida = $_POST['lugar_salida'];
    $lugar_llegada = $_POST['lugar_llegada'];
    $imagen = $_POST['imagen'];
    $fecha_ida = $_POST['fecha_ida'];
    $fecha_vuelta = $_POST['fecha_vuelta'];
    $duracion = $_POST['duracion'];
    $transporte = $_POST['transporte'];
    $paquete = $_POST['paquete'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];
    $estrellas = $_POST['estrellas'];
    $sql = "INSERT INTO pasaje (
        lugar_de_salida, lugar_de_llegada, imagen, fecha_ida, fecha_vuelta,
        duracion, metodo_de_transporte, paquete, PRECIO, calificacion, estrellas
    ) VALUES (
        '$lugar_salida', '$lugar_llegada', '$imagen', '$fecha_ida', '$fecha_vuelta',
        '$duracion', '$transporte', '$paquete', '$precio', '$calificacion', '$estrellas'
    )";

    if (mysqli_query($conexion, $sql)) {
    // Obtener el ID del vuelo recién insertado
    $id_viaje = mysqli_insert_id($conexion);

    // Insertar en productos con ese ID

    $sql2 = "INSERT INTO productos (nombre, precio, id_viaje) VALUES ('$lugar_salida', '$precio', '$id_viaje')";
    mysqli_query($conexion, $sql2);

    echo "<script>alert('Vuelo agregado correctamente'); window.location='vuelos.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
}

}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Vuelo</title>
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Agregar nuevo vuelo</h2>

<form method="POST" action="">
    <label>Lugar de salida</label>
    <input type="text" name="lugar_salida" required>

    <label>Lugar de llegada</label>
    <input type="text" name="lugar_llegada" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" required>

    <label>Fecha de ida</label>
    <input type="date" name="fecha_ida" required>

    <label>Fecha de vuelta</label>
    <input type="date" name="fecha_vuelta" required>

    <label>Duración (ej: 13 días / 12 noches)</label>
    <input type="text" name="duracion" required>

    <label>Método de transporte</label>
    <input type="text" name="transporte" required>

    <label>Paquete</label>
    <input type="text" name="paquete" required>

    <label>Precio</label>
    <input type="text" name="precio" required>

    <label>Calificación (ej: 4.3)</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" required>

    <label>Estrellas (ej: 3)</label>
    <input type="number" min="1" max="5" name="estrellas" required>

    <button type="submit">Agregar Vuelo</button>
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
