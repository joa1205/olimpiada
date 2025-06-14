<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: autos.php");
    exit();
}

if (!isset($_GET['id_autos'])) {
    echo "ID de auto no especificado.";
    exit();
}

$id_vuelo = $_GET['id_autos'];

// Obtener los datos actuales del vuelo
$resultado = mysqli_query($conexion, "SELECT * FROM autos WHERE id = $id_autos");
$autos = mysqli_fetch_assoc($resultado);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $capacidad = $_POST['capacidad'];
    $fecha_deposito = $_POST['fecha_deposito'];
    $fecha_devolucion = $_POST['fecha_devolucion'];
    $precio = $_POST['precio'];
    $calificacion = $_POST['calificacion'];
    $estrellas = $_POST['estrellas'];

    $sql = "UPDATE autos SET 
        nombre = '$nombre',
        imagen = '$imagen',
        fecha_deposito = '$fecha_deposito',
        fecha_devolucion = '$fecha_devolucion',
        capacidad = '$duracion',
        PRECIO = '$precio',
        calificacion = '$calificacion',
        estrellas = '$estrellas'
        WHERE id = $id_autos";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('vehiculo modificado correctamente'); window.location='autos.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar vehiculo</title>
</head>
<body>
<h2 style="text-align:center; color:#3f0071;">Modificar vehiculos</h2>

<form method="POST">

    <label>nombre</label>
    <input type="text" name="nombre" value="<?php echo $autos['nombre']; ?>" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" value="<?php echo $vuelo['imagen']; ?>" required>

    <label>Fecha de deposito del vehiculo</label>
    <input type="date" name="fecha_deposito" value="<?php echo $vuelo['fecha_deposito']; ?>" required>

    <label>Fecha de devolucion del vehiculo</label>
    <input type="date" name="fecha_devolucion" value="<?php echo $vuelo['fecha_devolucion']; ?>" required>

    <label>capacidad</label>
    <input type="text" name="capacidad" value="<?php echo $vuelo['capacidad']; ?>" required>

    <label>Precio</label>
    <input type="text" name="precio" value="<?php echo $vuelo['PRECIO']; ?>" required>

    <label>Calificación</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" value="<?php echo $vuelo['calificacion']; ?>" required>

    <label>Estrellas</label>
    <input type="number" min="1" max="5" name="estrellas" value="<?php echo $vuelo['estrellas']; ?>" required>

    <button type="submit">Modificar Vuelo</button>
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
