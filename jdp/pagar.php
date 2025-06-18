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
                $mensaje = "✅ Pago procesado correctamente. ¡Gracias por tu compra!";
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
    <style>
        body { font-family: Arial; background: #ecf0f1; padding: 20px; }
        .pago-container { background: #fff; max-width: 500px; margin: auto; padding: 20px; border-radius: 10px; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
        .btn { margin-top: 20px; background-color: #2ecc71; color: white; border: none; padding: 12px; border-radius: 6px; cursor: pointer; }
        .error { color: red; }
        .success { color: green; text-align: center; font-weight: bold; }
    </style>
</head>
<body>

<h2 style="text-align:center;">💳 Ingreso de datos de pago</h2>
<div class="pago-container">
    <?php if ($mensaje): ?>
        <p class="success"><?= $mensaje ?></p>
    <?php else: ?>
        <?php if ($errores): ?>
            <ul class="error">
                <?php foreach ($errores as $e): ?>
                    <li><?= $e ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post">
            <label for="numero">Número de tarjeta</label>
            <input type="text" name="numero" id="numero" required>

            <label for="nombre">Nombre del titular</label>
            <input type="text" name="nombre" id="nombre" required>

            <label for="vencimiento">Fecha de vencimiento (MM/AA)</label>
            <input type="text" name="vencimiento" id="vencimiento" required>

            <label for="cvv">CVV</label>
            <input type="text" name="cvv" id="cvv" required>

            <button class="btn">Confirmar pago</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>
