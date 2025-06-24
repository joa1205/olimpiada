<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario' LIMIT 1");
$row = mysqli_fetch_assoc($res);
$id_usuario = $row['id'];

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = trim($_POST['numero'] ?? '');
    $nombre = trim($_POST['nombre'] ?? '');
    $vencimiento = trim($_POST['vencimiento'] ?? '');
    $cvv = trim($_POST['cvv'] ?? '');

    if (strlen($numero) < 12 || !is_numeric($numero)) $errores[] = "Número de tarjeta inválido.";
    if (empty($nombre)) $errores[] = "Nombre del titular requerido.";
    if (empty($vencimiento)) $errores[] = "Fecha de vencimiento requerida.";
    if (strlen($cvv) !== 3 || !is_numeric($cvv)) $errores[] = "CVV inválido.";

    if (empty($errores)) {
        $res_carrito = mysqli_query($conexion, "SELECT id FROM carrito WHERE id_usuario = $id_usuario AND estado = 'activo' LIMIT 1");
        if ($carrito = mysqli_fetch_assoc($res_carrito)) {
            $id_carrito = $carrito['id'];
            $res_items = mysqli_query($conexion, "SELECT * FROM detalle_carrito WHERE id_carrito = $id_carrito");
            $items = mysqli_fetch_all($res_items, MYSQLI_ASSOC);

            if (!empty($items)) {
                $total = 0;
                foreach ($items as $item) {
                    $total += $item['precio_unitario'] * $item['cantidad'];
                }

                mysqli_query($conexion, "INSERT INTO compras (id_usuario, total) VALUES ($id_usuario, $total)");
                $id_compra = mysqli_insert_id($conexion);

                foreach ($items as $item) {
                    $id_prod = $item['id_producto'];
                    $cant = $item['cantidad'];
                    $precio = $item['precio_unitario'];
                    $tipo = $item['tipo_producto'];
                    mysqli_query($conexion, "INSERT INTO detalle_compra (id_compra, id_producto, tipo_producto, cantidad, precio_unitario)
                        VALUES ($id_compra, $id_prod, '$tipo', $cant, $precio)");
                }

                mysqli_query($conexion, "UPDATE carrito SET estado = 'comprado' WHERE id = $id_carrito");

                header("Location: index.php?pago=exitoso");
exit;

            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pago</title>
</head>
<body>

<div class="pago-container">
    <h2>💳 Datos de pago</h2>

    <?php if ($mensaje): ?>
        <p class="success"><?= $mensaje ?></p>
        <div style="text-align:center;">
            <a href="index.php" style="text-decoration: none;">
                <button class="btn volver">⬅ Volver al inicio</button>
            </a>
        </div>
        <script>
            // Redirigir a index.php después de 4 segundos
            setTimeout(function () {
                window.location.href = 'index.php';
            }, 4000);
        </script>
    <?php else: ?>
        <?php if ($errores): ?>
            <div class="error">
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($errores as $e): ?>
                        <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" novalidate>
            <label for="numero">Número de tarjeta</label>
            <input type="text" name="numero" id="numero" maxlength="16" required>

            <label for="nombre">Nombre del titular</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="vencimiento">Fecha de vencimiento (MM/AA)</label>
            <input type="text" name="vencimiento" id="vencimiento" placeholder="MM/AA" required>

            <label for="cvv">CVV</label>
            <input type="text" name="cvv" id="cvv" maxlength="3" required>

            <button class="btn">Confirmar pago</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>

<style>
    body {
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #2c3e50, #3498db);
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pago-container {
        background: #ffffff;
        width: 100%;
        max-width: 500px;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h2 {
        text-align: center;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    label {
        font-weight: 600;
        color: #34495e;
        margin-top: 15px;
        display: block;
    }

    input {
        width: 95%;
        padding: 12px;
        margin-top: 5px;
        border: 1px solid #bdc3c7;
        border-radius: 8px;
        font-size: 14px;
        transition: border 0.3s ease;
    }

    input:focus {
        border-color: #3498db;
        outline: none;
        box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    }

    .btn {
        margin-top: 25px;
        background-color: #27ae60;
        color: white;
        border: none;
        padding: 14px;
        width: 100%;
        border-radius: 10px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn:hover {
        background-color: #219150;
    }

    .error {
        background: #fce4e4;
        border: 1px solid #e74c3c;
        color: #e74c3c;
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .success {
        background: #e9f7ef;
        border: 1px solid #2ecc71;
        color: #2ecc71;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }
</style>
