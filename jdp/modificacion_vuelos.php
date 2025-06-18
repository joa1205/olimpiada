<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: vuelos.php");
    exit();
}

if (!isset($_GET['id_vuelo'])) {
    echo "ID de vuelo no especificado.";
    exit();
}

$id_vuelo = $_GET['id_vuelo'];

// Obtener los datos actuales del vuelo
$resultado = mysqli_query($conexion, "SELECT * FROM pasaje WHERE id = $id_vuelo");
$vuelo = mysqli_fetch_assoc($resultado);

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

    $sql = "UPDATE pasaje SET 
        lugar_de_salida = '$lugar_salida',
        lugar_de_llegada = '$lugar_llegada',
        imagen = '$imagen',
        fecha_ida = '$fecha_ida',
        fecha_vuelta = '$fecha_vuelta',
        duracion = '$duracion',
        metodo_de_transporte = '$transporte',
        paquete = '$paquete',
        PRECIO = '$precio',
        calificacion = '$calificacion',
        estrellas = '$estrellas'
        WHERE id = $id_vuelo";

    if (mysqli_query($conexion, $sql)) {
        echo "<script>alert('Vuelo modificado correctamente'); window.location='vuelos.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conexion);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Vuelo</title>
</head>
<body>
<h2 style="text-align:center; color:#3f0071;">Modificar vuelo</h2>

<form method="POST">
    <label>Lugar de salida</label>
    <input type="text" name="lugar_salida" value="<?php echo $vuelo['lugar_de_salida']; ?>" required>

    <label>Lugar de llegada</label>
    <input type="text" name="lugar_llegada" value="<?php echo $vuelo['lugar_de_llegada']; ?>" required>

    <label>URL de imagen</label>
    <input type="text" name="imagen" value="<?php echo $vuelo['imagen']; ?>" required>

    <label>Fecha de ida</label>
    <input type="date" name="fecha_ida" value="<?php echo $vuelo['fecha_ida']; ?>" required>

    <label>Fecha de vuelta</label>
    <input type="date" name="fecha_vuelta" value="<?php echo $vuelo['fecha_vuelta']; ?>" required>

    <label>Duración</label>
    <input type="text" name="duracion" value="<?php echo $vuelo['duracion']; ?>" required>

    <label>Método de transporte</label>
    <select name="transporte">
    <option value="avion" <?php if ($vuelo['metodo_de_transporte'] =='avion'):?>selected<?php endif?> >avion</option>
    <option value="barco"     <?php if ($vuelo['metodo_de_transporte'] =='barco'):?>selected <?php endif ?>>barco</option>
    <option value="colectivo"  <?php if ($vuelo['metodo_de_transporte'] =='colectivo'):?>selected <?php endif ?>>colectivo</option>
    </select>


    <label>Paquete</label>
    <select name="paquete">
    <option value="individual" <?php if ($vuelo['paquete'] =='individual'):?>selected<?php endif?> >individual</option>
    <option value="grupo"     <?php if ($vuelo['paquete'] =='grupo'):?>selected <?php endif ?>>grupal</option>
    <option value="familia"  <?php if ($vuelo['paquete'] =='familia'):?>selected <?php endif ?>>familiar</option>
    </select>

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