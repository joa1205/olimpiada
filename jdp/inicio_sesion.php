<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_bd = "olimpiada"; 
    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";

    $conexion = mysqli_connect($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    if (!$conexion) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    // NOTA: Los nombres de los campos deben coincidir con los del formulario
    $usuarioing = mysqli_real_escape_string($conexion, $_POST["usuarioing"]);
    $contraseñaing = $_POST["contraseñaing"];

    $sql = "SELECT usuario, contraseña FROM clientes WHERE usuario = '$usuarioing'";
    $resultado = mysqli_query($conexion, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $registro = mysqli_fetch_assoc($resultado);
        
        if (password_verify($contraseñaing, hash: $registro['contraseña'])) {
            header("Location: index.php");
            die();
        } else {
            echo "❌ Contraseña incorrecta.";
        }
    } else {
        echo "❌ Usuario no encontrado.";
    }

    mysqli_close($conexion);
}
?>
<form method="post" action="">
    <label>
        Usuario:
        <input type="text" name="usuarioing" required>
    </label><br>
    <label>
        Contraseña:
        <input type="password" name="contraseñaing" required>
    </label><br>
    <input type="submit" value="Enviar">
</form>
