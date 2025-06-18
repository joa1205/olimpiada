<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_bd = "olimpiada"; 
    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";

    $conexion = mysqli_connect($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    $usuarioing = mysqli_real_escape_string($conexion, $_POST["usuarioing"]);
    $contraseñaing = $_POST["contraseñaing"];

    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuarioing'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $registro = mysqli_fetch_assoc($resultado);

        if (password_verify($contraseñaing, $registro['contraseña'])) {
            $_SESSION['usuario'] = $registro['usuario'];
            $_SESSION['rol'] = $registro['rol'];
            $_SESSION['id_usuario'] = $registro['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "❌ Contraseña incorrecta.";
        }
    } else {
        $error = "❌ Usuario no encontrado.";
    }

    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>
<body>

<h2 style="text-align:center; color:#3f0071;">Iniciar sesión</h2>

<form method="post" action="">
    <label>Usuario</label>
    <input type="text" name="usuarioing" required>

    <label>Contraseña</label>
    <input type="password" name="contraseñaing" required>

    <button type="submit">Ingresar</button>

    <?php if (isset($error)): ?>
        <p style="color:red; text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>
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
        max-width: 400px;
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
        width: 100%;
    }

    button:hover {
        background-color: #5b0cbf;
    }

    p {
        margin-top: 20px;
    }
</style>
