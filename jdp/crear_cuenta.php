<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_bd = "olimpiada";
    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";

    $conexion = mysqli_connect($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);

    if (!$conexion) {
        die("❌ Conexión fallida: " . mysqli_connect_error());
    }

    // Validación para evitar errores por campos faltantes
    $campos = [
        "dni", "nombre_y_apellido", "usuario", "contraseña", "fecha_de_nacimiento",
        "sexo", "gmail", "numero_telefonico", "pais", "provincia", "localidad", "domicilio"
    ];

    foreach ($campos as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            die("❌ El campo '$campo' es obligatorio.");
        }
    }

    // Limpiar y preparar datos
    $dni = mysqli_real_escape_string($conexion, $_POST["dni"]);
    $nombre = mysqli_real_escape_string($conexion, $_POST["nombre_y_apellido"]);
    $usuario = mysqli_real_escape_string($conexion, $_POST["usuario"]);
    $contraseña = password_hash($_POST["contraseña"], PASSWORD_DEFAULT);
    $fecha = mysqli_real_escape_string($conexion, $_POST["fecha_de_nacimiento"]);
    $sexo = mysqli_real_escape_string($conexion, $_POST["sexo"]);
    $gmail = mysqli_real_escape_string($conexion, $_POST["gmail"]);
    $telefono = mysqli_real_escape_string($conexion, $_POST["numero_telefonico"]);
    $pais = mysqli_real_escape_string($conexion, $_POST["pais"]);
    $provincia = mysqli_real_escape_string($conexion, $_POST["provincia"]);
    $localidad = mysqli_real_escape_string($conexion, $_POST["localidad"]);
    $domicilio = mysqli_real_escape_string($conexion, $_POST["domicilio"]);

    // Verificar si el usuario ya existe
    $sql_check = "SELECT * FROM clientes WHERE usuario = '$usuario'";
    $resultado = mysqli_query($conexion, $sql_check);

    if (mysqli_num_rows($resultado) > 0) {
        echo "❌ El usuario ya existe. Por favor, elige otro nombre de usuario.";
    } else {
        // Insertar nuevo usuario
        $sql_insert = "INSERT INTO clientes (
            dni, nombre_y_apellido, usuario, contraseña,
            fecha_de_nacimiento, sexo, gmail, numero_telefonico,
            pais, provincia, localidad, domicilio
        ) VALUES (
            '$dni', '$nombre', '$usuario', '$contraseña',
            '$fecha', '$sexo', '$gmail', '$telefono',
            '$pais', '$provincia', '$localidad', '$domicilio'
        )";

        if (mysqli_query($conexion, $sql_insert)) {
            echo "✅ Cuenta creada exitosamente.";
        } else {
            echo "❌ Error al crear la cuenta: " . mysqli_error($conexion);
        }
    }

    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta</title>
</head>
<body>
    <h2>Formulario de Registro</h2>
    <form method="post" action="">
        <label>DNI: <input type="text" name="dni" required></label><br>
        <label>Nombre y Apellido: <input type="text" name="nombre_y_apellido" required></label><br>
        <label>Usuario: <input type="text" name="usuario" required></label><br>
        <label>Contraseña: <input type="password" name="contraseña" required></label><br>
        <label>Fecha de nacimiento: <input type="date" name="fecha_de_nacimiento" required></label><br>
        <label>Sexo:
            <select name="sexo" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
            </select>
        </label><br>
        <label>Gmail: <input type="email" name="gmail" required></label><br>
        <label>Teléfono: <input type="text" name="numero_telefonico" required></label><br>
        <label>País: <input type="text" name="pais" required></label><br>
        <label>Provincia: <input type="text" name="provincia" required></label><br>
        <label>Localidad: <input type="text" name="localidad" required></label><br>
        <label>Domicilio: <input type="text" name="domicilio" required></label><br>
        <input type="submit" value="Crear Cuenta">
    </form>
</body>
</html>

