<?php
include 'conexion.php';
session_start();
  
// Verificar sesión
if (!isset($_SESSION['usuario'])) {
  header('Location: login.php');
  exit();
}
if (!isset($_SESSION['carrito'])) {
  $_SESSION['carrito'] = [];
}
// Obtener el ID del usuario si está logueado
$id_usuario = null;
if (isset($_SESSION['usuario'])) {
  $usuario = $_SESSION['usuario'];
  // Consulta para obtener el ID del usuario a partir del nombre de usuario en sesión
  $res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario'");
  if ($fila = mysqli_fetch_assoc($res)) {
    $id_usuario = $fila['id'];
  }
}
$contador_carrito = 0;
if ($id_usuario) {
  // Sumar cantidades en detalle_carrito para el carrito activo del usuario
  $q = "SELECT SUM(cantidad) AS total FROM detalle_carrito dc 
        INNER JOIN carrito c ON c.id = dc.id_carrito
        WHERE c.id_usuario = $id_usuario AND c.estado = 'activo'";
  $res = mysqli_query($conexion, $q);
  if ($fila = mysqli_fetch_assoc($res)) {
    $contador_carrito = $fila['total'] ?? 0;
  }
} else {
  // Para usuarios no logueados, sumar cantidades del carrito en sesión
  $contador_carrito = array_sum($_SESSION['carrito']);
}
$id_usuario = $_SESSION['id_usuario']; // Debes tener esto en la sesión cuando el usuario inicia sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Compras</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
 <nav>
    <div class="navbar">
      <div class="navbar-left">
        <!-- Mostrar usuario logueado -->
        <?php if (isset($_SESSION['usuario'])): ?>
          <span class="nav-item user-info"><i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?></span>
        <?php endif; ?>
      </div>

      <!-- Enlaces de navegación -->
      <div class="nav-links">
        <a href="index.php" class="nav-item"><i class="fas fa-house"></i>Inicio</a>
        <a href="vuelos.php" class="nav-item active"><i class="fas fa-plane"></i>Vuelos</a>
        <a href="alojamientos.php" class="nav-item"><i class="fas fa-hotel"></i>Alojamientos</a>
        <a href="paquetes.php" class="nav-item"><i class="fas fa-suitcase-rolling"></i>Paquetes</a>
        <a href="autos.php" class="nav-item"><i class="fas fa-car"></i>Autos</a>
        <?php
  if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
      echo '<a href="detalles_ventas.php" class="nav-item"><i class="fas fa-chart-line"></i> Ventas</a>';
    } elseif ($_SESSION['rol'] === 'cliente') {
      echo '<a href="mis_compras.php" class="nav-item"><i class="fas fa-receipt"></i> Mis Compras</a>';
    }
  }
?>
      </div>

      <!-- Login/logout y carrito -->
      <div class="navbar-right">
        <?php if (isset($_SESSION['usuario'])): ?>
          <a href="cerrar_sesion.php" class="nav-item"><i class="fas fa-right-from-bracket"></i>Cerrar sesión</a>
        <?php else: ?>
          <a href="inicio_sesion.php" class="nav-item"><i class="fas fa-right-to-bracket"></i>Iniciar sesión</a>
          <a href="crear_cuenta.php" class="nav-item"><i class="fas fa-user-plus"></i>Registrarse</a>
        <?php endif; ?>
        <!-- Contador carrito -->
        <a href="carrito.php" class="nav-item cart"><i
            class="fas fa-shopping-cart"></i>Carrito(<?php echo $contador_carrito; ?>)</a>
      </div>
    </div>
  </nav>

<body>

<h2>🛍️ Historial de Mis Compras</h2>

<table>
  <tr>
    <th>ID Compra</th>
    <th>Producto</th>
    <th>Tipo</th>
    <th>Cantidad</th>
    <th>Precio Unitario</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Estado</th>
  </tr>

<?php
$sql = "SELECT 
          c.id AS id_compra,
          dc.id_producto,
          dc.tipo_producto,
          dc.cantidad,
          dc.precio_unitario,
          dc.estado,
          dc.id AS id_detalle,
          c.fecha AS fecha_compra
        FROM compras c
        JOIN detalle_compra dc ON c.id = dc.id_compra
        WHERE c.id_usuario = $id_usuario
        ORDER BY c.fecha DESC";

$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
  while($row = $resultado->fetch_assoc()) {
    $id_prod = intval($row['id_producto']);
    $tipo = $row['tipo_producto'];
    $nombre_producto = 'Producto';

    // Obtener nombre y tipo real
    $res_prod = $conexion->query("SELECT * FROM productos WHERE id = $id_prod");
    if ($res_prod && $producto = $res_prod->fetch_assoc()) {
      $nombre_producto = $producto['nombre'] ?? 'Producto';

      if (!is_null($producto['id_viaje'])) $tipo = 'pasaje';
      elseif (!is_null($producto['id_alojamiento'])) $tipo = 'alojamiento';
      elseif (!is_null($producto['id_paquetes'])) $tipo = 'paquete';
      elseif (!is_null($producto['id_autos'])) $tipo = 'auto';
      else $tipo = 'producto';
    }

    $total = $row['cantidad'] * $row['precio_unitario'];
    $estado = $row['estado'];
    $color = 'gris';
    if ($estado === 'aceptado') $color = 'verde';
    elseif ($estado === 'rechazado') $color = 'rojo';

    echo "<tr>
            <td>{$row['id_compra']}</td>
            <td>$nombre_producto</td>
            <td>$tipo</td>
            <td>{$row['cantidad']}</td>
            <td>\${$row['precio_unitario']}</td>
            <td>\$$total</td>
            <td>{$row['fecha_compra']}</td>
            <td><span class='estado-$color'>" . ucfirst($estado) . "</span></td>
          </tr>";
  }
} else {
  echo "<tr><td colspan='8'>No has realizado compras todavía.</td></tr>";
}
$conexion->close();
?>
</table>

<style>
  table {
    width: 95%;
    margin: 30px auto;
    border-collapse: collapse;
  }
  th, td {
    padding: 10px;
    border: 1px solid #ccc;
    text-align: center;
  }
  th {
    background-color: #eee;
  }
  h2 {
    text-align: center;
    margin-top: 20px;
  }

  .estado-verde {
    color: #4CAF50;
    font-weight: bold;
  }

  .estado-rojo {
    color: #f44336;
    font-weight: bold;
  }

  .estado-gris {
    color: #888;
    font-weight: bold;
  }
</style>

</body>
</html>
