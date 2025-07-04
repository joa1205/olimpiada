<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

$id_usuario_logueado = null;
if (isset($_SESSION['usuario'])) {
    $nombre_usuario = $_SESSION['usuario'];
    $consulta_usuario = "SELECT id FROM usuarios WHERE usuario = '$nombre_usuario' LIMIT 1";
    $resultado_usuario = mysqli_query($conexion, $consulta_usuario);
    if ($fila = mysqli_fetch_assoc($resultado_usuario)) {
        $id_usuario_logueado = $fila['id'];
    }
}

$id_usuario = $id_usuario_logueado;

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['modificar_cantidad'], $_POST['producto_id'], $_POST['accion'])
) {
    $producto_id = intval($_POST['producto_id']);
    $accion = $_POST['accion'];

    if ($id_usuario) {
        $consulta_carrito = "SELECT c.id FROM carrito c WHERE c.id_usuario = $id_usuario AND c.estado = 'activo' LIMIT 1";
        $resultado_carrito = mysqli_query($conexion, $consulta_carrito);
        if (mysqli_num_rows($resultado_carrito) > 0) {
            $id_carrito_activo = mysqli_fetch_assoc($resultado_carrito)['id'];
            if ($accion === 'sumar') {
                $conexion->query("UPDATE detalle_carrito SET cantidad = cantidad + 1 WHERE id_carrito = $id_carrito_activo AND id_producto = $producto_id");
            } elseif ($accion === 'restar') {
                $conexion->query("UPDATE detalle_carrito SET cantidad = GREATEST(cantidad - 1, 1) WHERE id_carrito = $id_carrito_activo AND id_producto = $producto_id");
            }
        }
    } else {
        if (isset($_SESSION['carrito'][$producto_id])) {
            if ($accion === 'sumar') {
                $_SESSION['carrito'][$producto_id]++;
            } elseif ($accion === 'restar' && $_SESSION['carrito'][$producto_id] > 1) {
                $_SESSION['carrito'][$producto_id]--;
            }
        }
    }

    header("Location: carrito.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar'])) {
    $id_producto_eliminar = intval($_POST['eliminar']);

    if ($id_usuario) {
        $sql_eliminar = "DELETE dc FROM detalle_carrito dc JOIN carrito c ON dc.id_carrito = c.id WHERE dc.id_producto = $id_producto_eliminar AND c.id_usuario = $id_usuario AND c.estado = 'activo'";
        mysqli_query($conexion, $sql_eliminar);
    } else {
        unset($_SESSION['carrito'][$id_producto_eliminar]);
    }

    header("Location: carrito.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaciar'])) {
    if ($id_usuario) {
        $sql_vaciar = "DELETE dc FROM detalle_carrito dc JOIN carrito c ON dc.id_carrito = c.id WHERE c.id_usuario = $id_usuario AND c.estado = 'activo'";
        mysqli_query($conexion, $sql_vaciar);
    } else {
        $_SESSION['carrito'] = [];
    }

    header("Location: carrito.php");
    exit;
}

$productos = [];
$total = 0;

if ($id_usuario) {
    $consulta_carrito_activo = "SELECT c.id FROM carrito c WHERE c.id_usuario = $id_usuario AND c.estado = 'activo' LIMIT 1";
    $resultado_carrito_activo = mysqli_query($conexion, $consulta_carrito_activo);

    if (mysqli_num_rows($resultado_carrito_activo) > 0) {
        $id_carrito = mysqli_fetch_assoc($resultado_carrito_activo)['id'];

        $sql_productos = "
        SELECT 
          dc.id_producto, dc.cantidad, dc.precio_unitario, dc.tipo_producto,
          p.nombre AS nombre_producto, 
          v.lugar_de_llegada, v.duracion AS duracion_vuelo,
          a.nombre AS nombre_auto, a.fecha_deposito, a.fecha_devolucion,
          al.nombre AS nombre_alojamiento, al.fecha_ingreso, al.fecha_salida,
          pa.nombre AS nombre_paquete, pa.fecha_ida, pa.fecha_vuelta
        FROM detalle_carrito dc
        LEFT JOIN productos p ON dc.id_producto = p.id
        LEFT JOIN pasaje v ON dc.tipo_producto = 'vuelo' AND p.id_viaje = v.id
        LEFT JOIN autos a ON dc.tipo_producto = 'auto' AND p.id_autos = a.id
        LEFT JOIN alojamiento al ON dc.tipo_producto = 'alojamiento' AND p.id_alojamiento = al.id
        LEFT JOIN paquetes pa ON dc.tipo_producto = 'paquete' AND p.id_paquetes = pa.id
        WHERE dc.id_carrito = $id_carrito";

        $resultado_productos = mysqli_query($conexion, $sql_productos);
        $productos = mysqli_fetch_all($resultado_productos, MYSQLI_ASSOC);

        foreach ($productos as $item) {
            $total += $item['precio_unitario'] * $item['cantidad'];
        }
    }
} else {
    $carrito_sesion = $_SESSION['carrito'];
    if (!empty($carrito_sesion)) {
        $ids = implode(",", array_map('intval', array_keys($carrito_sesion)));
        $sql = "SELECT p.id AS id_producto, p.precio AS precio_producto, v.*, 'producto' AS tipo_producto FROM productos p LEFT JOIN pasaje v ON p.id_viaje = v.id WHERE p.id IN ($ids)";
        $resultado = mysqli_query($conexion, $sql);
        $productos_sesion = mysqli_fetch_all($resultado, MYSQLI_ASSOC);

        foreach ($productos_sesion as $item) {
            $cantidad = $carrito_sesion[$item['id_producto']];
            $item['cantidad'] = $cantidad;
            $item['precio_unitario'] = $item['precio_producto'];
            $productos[] = $item;
            $total += $item['precio_unitario'] * $cantidad;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { font-family: Arial; background: #f7f7f7; padding: 30px; }
        .carrito-container { background: white; padding: 20px; border-radius: 10px; max-width: 800px; margin: auto; }
        .producto-item { display: flex; justify-content: space-between; border-bottom: 1px solid #ccc; padding: 15px 0; }
        .acciones { text-align: center; margin-top: 20px; }
        .btn { padding: 8px 16px; border: none; border-radius: 6px; font-weight: bold; cursor: pointer; }
        .btn-eliminar { background-color: #e74c3c; color: white; }
        .btn-vaciar { background-color: #999; color: white; }
        .btn:hover { opacity: 0.9; }
        .total { font-size: 18px; font-weight: bold; text-align: right; margin-top: 15px; }
        .volver { display: block; margin-top: 25px; text-align: center; color: #333; text-decoration: none; }
    </style>
</head>
<body>
<?php if (isset($_GET['comprado'])): ?>
    <p style="color:green; text-align:center;">¡Compra realizada con éxito! ✅</p>
<?php endif; ?>

<h1 style="text-align:center;">🛒 Carrito de Compras</h1>
<div class="carrito-container">
    <?php if (empty($productos)): ?>
        <p style="text-align:center;">No hay productos en el carrito.</p>
    <?php else: ?>
        <?php foreach ($productos as $item): ?>
            <div class="producto-item">
                <div>
                    <strong>
                    <?php
                    switch ($item['tipo_producto']) {
                        case 'vuelo':
                            echo $item['lugar_de_llegada'] ?? 'Vuelo';
                            break;
                        case 'auto':
                            echo $item['nombre_auto'] ?? 'Auto';
                            break;
                        case 'alojamiento':
                            echo $item['nombre_alojamiento'] ?? 'Alojamiento';
                            break;
                        case 'paquete':
                            echo $item['nombre_paquete'] ?? 'Paquete';
                            break;
                        default:
                            echo $item['nombre_producto'] ?? 'Producto';
                    }
                    ?>
                    </strong><br>
                    <?php
                    if (!empty($item['duracion_vuelo'])) {
                        echo $item['duracion_vuelo'] . "<br>";
                    } elseif (!empty($item['fecha_ingreso']) && !empty($item['fecha_salida'])) {
                        echo "Del " . $item['fecha_ingreso'] . " al " . $item['fecha_salida'] . "<br>";
                    } elseif (!empty($item['fecha_ida']) && !empty($item['fecha_vuelta'])) {
                        echo "Del " . $item['fecha_ida'] . " al " . $item['fecha_vuelta'] . "<br>";
                    } elseif (!empty($item['fecha_deposito']) && !empty($item['fecha_devolucion'])) {
                        echo "Del " . $item['fecha_deposito'] . " al " . $item['fecha_devolucion'] . "<br>";
                    }
                    ?>
                    $<?= number_format($item['precio_unitario'], 2); ?> x <?= $item['cantidad']; ?>
                </div>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="producto_id" value="<?= $item['id_producto']; ?>">
                    <input type="hidden" name="modificar_cantidad" value="1">
                    <button class="btn" name="accion" value="restar">➖</button>
                    <button class="btn" name="accion" value="sumar">➕</button>
                </form>

                <form method="post" style="display:inline;">
                    <input type="hidden" name="eliminar" value="<?= $item['id_producto']; ?>">
                    <button class="btn btn-eliminar">Eliminar</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div class="total">Total: $<?= number_format($total, 2); ?></div>

        <div class="acciones">
            <form method="post" style="display:inline;">
                <input type="hidden" name="vaciar" value="1">
                <button class="btn btn-vaciar">Vaciar carrito 🗑️</button>
            </form>

            <a href="pagar.php" class="btn" style="background-color:#2ecc71; color:white; text-decoration:none;">Pagar ✅</a>
        </div>
    <?php endif; ?>
</div>
<a href="index.php" class="volver">← Seguir comprando</a>

</body>
</html>