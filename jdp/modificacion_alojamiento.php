<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: alojamientos.php");
    exit();
}

if (!isset($_GET['id_alojamiento'])) {
    echo "ID de alojamiento no especificado.";
    exit();
}

$id_alojamiento = intval($_GET['id_alojamiento']);

$resultado = mysqli_query($conexion, "SELECT * FROM alojamiento WHERE id = $id_alojamiento");
$alojamiento = mysqli_fetch_assoc($resultado);

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

    $sql = "UPDATE alojamiento SET 
        nombre = '$nombre',
        imagen = '$imagen',
        mapalink = '$mapalink',
        capacidad = '$capacidad',
        direccion = '$direccion',
        fecha_ingreso = '$fecha_ingreso',
        fecha_salida = '$fecha_salida',
        seguro = '$seguro',
        precio = '$precio',
        calificacion = '$calificacion',
        estrellas = '$estrellas'
        WHERE id = $id_alojamiento";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Alojamiento modificado correctamente'); window.location='alojamientos.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar alojamiento</title>
</head>
<body>
<h2 style="text-align:center; color:#3f0071;">Modificar alojamiento</h2>

<form method="POST">

    <label>Nombre</label>
    <input type="text" name="nombre" value="<?php echo $alojamiento['nombre']; ?>" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" value="<?php echo $alojamiento['imagen']; ?>" required>

    <label>Mapa link</label>
    <input type="text" name="mapalink" value="<?php echo $alojamiento['mapalink']; ?>" required>

    <label>Capacidad</label>
    <select name="capacidad">
    <option value="<?php echo $alojamiento['capacidad']; ?>"><?php echo $alojamiento['capacidad']; ?></option>
    <?php if ($alojamiento['capacidad'] !=='individual'): ?>
    <option value="individual">individual</option><?php endif;?>
    <?php if ($alojamiento['capacidad'] !=='2 personas'): ?>
    <option value="2 personas">2 personas</option><?php endif;?>
    <?php if ($alojamiento['capacidad'] !=='4 personas'): ?>
    <option value="4 personas">4 personas</option><?php endif;?>
    </select>

    <label>Duracion</label>
    <input type="text" name="duracion" value="<?php echo $alojamiento['duracion']; ?>" required>

    <label>Dirección</label>
    <input type="text" name="direccion" value="<?php echo $alojamiento['direccion']; ?>" required>

    <label>Fecha de ingreso</label>
    <input type="date" name="fecha_ingreso" value="<?php echo $alojamiento['fecha_ingreso']; ?>" required>

    <label>Fecha de salida</label>
    <input type="date" name="fecha_salida" value="<?php echo $alojamiento['fecha_salida']; ?>" required>

    <label>Seguro</label>
    <input type="text" name="seguro" value="<?php echo $alojamiento['seguro']; ?>" required>

    <label>Precio</label>
    <input type="text" name="precio" value="<?php echo $alojamiento['precio']; ?>" required>

    <label>Calificación</label>
    <input type="number" step="0.1" min="0" max="5" name="calificacion" value="<?php echo $alojamiento['calificacion']; ?>" required>

    <label>Estrellas</label>
    <input type="number" min="1" max="5" name="estrellas" value="<?php echo $alojamiento['estrellas']; ?>" required>

    <button type="submit">Modificar alojamiento</button>
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
    select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-top: 5px;
    background-color: white;
    font-family: inherit;
    font-size: 14px;
    appearance: none;       /* Quita el estilo nativo */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='gray' class='bi bi-caret-down-fill' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658A.5.5 0 0 1 2.88 5h10.24a.5.5 0 0 1 .428.758l-4.796 5.482a.5.5 0 0 1-.752 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 16px;
    padding-right: 40px; /* espacio para la flecha */
}
</style>
