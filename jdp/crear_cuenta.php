<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Configuración de la base de datos
    $nombre_bd = "olimpiada";
    $servidor = "localhost";
    $usuario_bd = "root";
    $contraseña_bd = "";

    // Conexión a la base de datos
    $conexion = mysqli_connect($servidor, $usuario_bd, $contraseña_bd, $nombre_bd);
    if (!$conexion) {
        die("❌ Conexión fallida: " . mysqli_connect_error());
    }

    // Validación de campos obligatorios
    $campos = [
        "dni", "nombre_y_apellido", "usuario", "contraseña", "fecha_de_nacimiento",
        "sexo", "gmail", "numero_telefonico", "pais", "provincia", "localidad", "domicilio"
    ];
    foreach ($campos as $campo) {
        if (empty(trim($_POST[$campo]))) {
            die("❌ El campo '$campo' es obligatorio.");
        }
    }

    // Limpieza y preparación de datos
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
    $sql_check = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado = mysqli_query($conexion, $sql_check);
    if (mysqli_num_rows($resultado) > 0) {
        echo "❌ El usuario '$usuario' ya existe. Por favor, elige otro nombre de usuario.";
    } else {
        // Insertar nuevo usuario
        $sql_insert1 = "INSERT INTO usuarios (usuario, contraseña, rol) VALUES ('$usuario', '$contraseña', 'cliente')";
        if (mysqli_query($conexion, $sql_insert1)) {
            // Obtener el ID del usuario recién insertado
            $id_usuario = mysqli_insert_id($conexion);

            // Insertar datos en la tabla clientes
            $sql_insert2 = "INSERT INTO clientes (
                dni, nombre_y_apellido, fecha_de_nacimiento, sexo, gmail, numero_telefonico,
                pais, provincia, localidad, domicilio, id_usuario
            ) VALUES (
                '$dni', '$nombre', '$fecha', '$sexo', '$gmail', '$telefono',
                '$pais', '$provincia', '$localidad', '$domicilio', '$id_usuario'
            )";

            if (mysqli_query($conexion, $sql_insert2)) {
                header("Location: index.php?registro=exitoso");
                exit();
            } else {
                echo "❌ Error al insertar en la tabla 'clientes': " . mysqli_error($conexion);
            }
        } else {
            echo "❌ Error al insertar en la tabla 'usuarios': " . mysqli_error($conexion);
        }
    }

    // Cerrar la conexión
    mysqli_close($conexion);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Cuenta</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-container">
        <h2>Formulario de Registro</h2>
        <form method="post" action="">
            <div class="form-group">
                <label>DNI:</label>
                <input type="text" name="dni" required>
            </div>
            <div class="form-group">
                <label>Nombre y Apellido:</label>
                <input type="text" name="nombre_y_apellido" required>
            </div>
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" name="usuario" required>
            </div>
            <div class="form-group">
                <label>Contraseña:</label>
                <input type="password" name="contraseña" required>
            </div>
            <div class="form-group">
                <label>Fecha de nacimiento:</label>
                <input type="date" name="fecha_de_nacimiento" required>
            </div>
            <div class="form-group">
                <label>Sexo:</label>
                <select name="sexo" required>
                    <option value="Masculino">Masculino</option>
                    <option value="Femenino">Femenino</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gmail:</label>
                <input type="email" name="gmail" required>
            </div>
            <div class="form-group">
                <label>Teléfono:</label>
                <input type="text" name="numero_telefonico" required>
            </div>
            <div class="form-group">
                <label>País:</label>
                <input type="text" name="pais" required>
            </div>
            <div class="form-group">
                <label>Provincia:</label>
                <input type="text" name="provincia" required>
            </div>
            <div class="form-group">
                <label>Localidad:</label>
                <input type="text" name="localidad" required>
            </div>
            <div class="form-group">
                <label>Domicilio:</label>
                <input type="text" name="domicilio" required>
            </div>
            <input type="submit" value="Crear Cuenta">
        </form>
    </div>

   