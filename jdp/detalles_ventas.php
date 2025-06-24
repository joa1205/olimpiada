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
  $res = mysqli_query($conexion, "SELECT id FROM usuarios WHERE usuario = '$usuario'");
  if ($fila = mysqli_fetch_assoc($res)) {
    $id_usuario = $fila['id'];
  }
}

$contador_carrito = 0;
if ($id_usuario) {
  $q = "SELECT SUM(cantidad) AS total FROM detalle_carrito dc 
        INNER JOIN carrito c ON c.id = dc.id_carrito
        WHERE c.id_usuario = $id_usuario AND c.estado = 'activo'";
  $res = mysqli_query($conexion, $q);
  if ($fila = mysqli_fetch_assoc($res)) {
    $contador_carrito = $fila['total'] ?? 0;
  }
} else {
  $contador_carrito = array_sum($_SESSION['carrito']);
}

// Procesar cambios de estado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'], $_POST['id_detalle'])) {
  $id_detalle = intval($_POST['id_detalle']);
  $accion = $_POST['accion'];

  if (in_array($accion, ['aceptado', 'rechazado'])) {
    $conexion->query("UPDATE detalle_compra SET estado = '$accion' WHERE id = $id_detalle");
  }
}

// Inicializar filtros
$filtro_estado = $_GET['estado'] ?? '';
$filtro_tipo = $_GET['tipo'] ?? '';
$filtro_usuario = $_GET['usuario'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de Ventas</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<nav>
  <div class="navbar">
    <div class="navbar-left">
      <?php if (isset($_SESSION['usuario'])): ?>
        <span class="nav-item user-info"><i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?></span>
      <?php endif; ?>
    </div>
    <div class="nav-links">
      <a href="index.php" class="nav-item"><i class="fas fa-house"></i>Inicio</a>
      <a href="vuelos.php" class="nav-item"><i class="fas fa-plane"></i>Vuelos</a>
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
    <div class="navbar-right">
      <?php if (isset($_SESSION['usuario'])): ?>
        <a href="cerrar_sesion.php" class="nav-item"><i class="fas fa-right-from-bracket"></i>Cerrar sesión</a>
      <?php else: ?>
        <a href="inicio_sesion.php" class="nav-item"><i class="fas fa-right-to-bracket"></i>Iniciar sesión</a>
        <a href="crear_cuenta.php" class="nav-item"><i class="fas fa-user-plus"></i>Registrarse</a>
      <?php endif; ?>
      <a href="carrito.php" class="nav-item cart"><i class="fas fa-shopping-cart"></i>Carrito(<?php echo $contador_carrito; ?>)</a>
    </div>
  </div>
</nav>

<h2>🧾 Detalle de todas las compras registradas</h2>
<form method="GET" style="display: flex; justify-content: center; gap: 20px; margin: 20px auto; flex-wrap: wrap;">
  <div>
    <label for="estado">Estado:</label><br>
    <select name="estado" id="estado">
      <option value="">Todos</option>
      <option value="En proceso" <?= $filtro_estado === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
      <option value="aceptado" <?= $filtro_estado === 'aceptado' ? 'selected' : '' ?>>Aceptado</option>
      <option value="rechazado" <?= $filtro_estado === 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
    </select>
  </div>

  <div>
    <label for="tipo">Tipo:</label><br>
    <select name="tipo" id="tipo">
      <option value="">Todos</option>
      <option value="vuelo" <?= $filtro_tipo === 'vuelo' ? 'selected' : '' ?>>Vuelo</option>
      <option value="alojamiento" <?= $filtro_tipo === 'alojamiento' ? 'selected' : '' ?>>Alojamiento</option>
      <option value="paquete" <?= $filtro_tipo === 'paquete' ? 'selected' : '' ?>>Paquete</option>
      <option value="auto" <?= $filtro_tipo === 'auto' ? 'selected' : '' ?>>Auto</option>
    </select>
  </div>

  <div style="display: flex; flex-direction: column;">
    <label for="usuario">Usuario:</label>
    <input type="text" name="usuario" id="usuario" placeholder="Nombre usuario" value="<?= htmlspecialchars($filtro_usuario) ?>">
  </div>

  <div style="display: flex; align-items: end; gap: 10px;">
    <button type="submit">Filtrar</button>
    <a href="detalles_ventas.php" style="padding: 5px 10px; background-color: #ccc; text-decoration: none; border-radius: 4px; color: black;">Reiniciar</a>
  </div>
</form>



<table>
  <tr>
    <th>ID Compra</th>
    <th>Usuario</th>
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
          u.usuario AS nombre_usuario,
          dc.id_producto,
          dc.tipo_producto,
          dc.cantidad,
          dc.precio_unitario,
          dc.id AS id_detalle,
          dc.estado,
          c.fecha AS fecha_compra
        FROM compras c
        JOIN usuarios u ON c.id_usuario = u.id
        JOIN detalle_compra dc ON c.id = dc.id_compra
        WHERE 1=1";

if (!empty($filtro_estado)) {
  $estado_escapado = $conexion->real_escape_string($filtro_estado);
  $sql .= " AND dc.estado = '$estado_escapado'";
}
if (!empty($filtro_usuario)) {
  $usuario_escapado = $conexion->real_escape_string($filtro_usuario);
  $sql .= " AND u.usuario LIKE '%$usuario_escapado%'";
}

$sql .= " ORDER BY c.fecha DESC";

$resultado = $conexion->query($sql);

if ($resultado && $resultado->num_rows > 0) {
  while($row = $resultado->fetch_assoc()) {
    $id_prod = intval($row['id_producto']);
    $tipo = $row['tipo_producto'];

    $res_prod = $conexion->query("SELECT * FROM productos WHERE id = $id_prod");
    if ($res_prod && $producto = $res_prod->fetch_assoc()) {
      if (!is_null($producto['id_viaje'])) $tipo = 'vuelo';
      elseif (!is_null($producto['id_alojamiento'])) $tipo = 'alojamiento';
      elseif (!is_null($producto['id_paquetes'])) $tipo = 'paquete';
      elseif (!is_null($producto['id_autos'])) $tipo = 'auto';
      
    }

    // Filtrar por tipo (en PHP)
    if (!empty($filtro_tipo) && $filtro_tipo !== $tipo) continue;

    $total = $row['cantidad'] * $row['precio_unitario'];
    $estado = $row['estado'];
    $id_detalle = $row['id_detalle'];

    echo "<tr>
            <td>{$row['id_compra']}</td>
            <td>{$row['nombre_usuario']}</td>
            <td>$tipo</td>
            <td>{$row['cantidad']}</td>
            <td>\${$row['precio_unitario']}</td>
            <td>\$$total</td>
            <td>{$row['fecha_compra']}</td>
            <td>";

    if ($_SESSION['rol'] === 'admin') {
      $color = 'gris';
      if ($estado === 'aceptado') $color = 'verde';
      elseif ($estado === 'rechazado') $color = 'rojo';

      echo "<span class='estado-$color'>" . ucfirst($estado) . "</span><br>";
      echo "<button class='modificar-btn' onclick='mostrarOpciones($id_detalle)'>Modificar</button>
            <div id='opciones-$id_detalle' class='opciones-estado'>
              <form method='POST' style='display:inline;'>
                <input type='hidden' name='id_detalle' value='$id_detalle'>
                <input type='hidden' name='accion' value='aceptado'>
                <button class='btn-verde' type='submit'>Aceptar</button>
              </form>
              <form method='POST' style='display:inline;'>
                <input type='hidden' name='id_detalle' value='$id_detalle'>
                <input type='hidden' name='accion' value='rechazado'>
                <button class='btn-rojo' type='submit'>Rechazar</button>
              </form>
            </div>";
    } else {
      echo ucfirst($estado);
    }

    echo "</td></tr>";
  }
} else {
  echo "<tr><td colspan='8'>No hay compras registradas aún.</td></tr>";
}
$conexion->close();
?>
</table>

<script>
  function mostrarOpciones(id) {
    document.getElementById('opciones-' + id).style.display = 'inline-block';
  }
</script>

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

  .modificar-btn {
    background-color: #888;
    color: white;
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
  }

  .btn-verde {
    background-color: #4CAF50;
    color: white;
    padding: 5px 10px;
    margin-right: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .btn-rojo {
    background-color: #f44336;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .estado-verde {
    color: #4CAF50;
    font-weight: bold;
  }

  .estado-rojo {
    color: #f44336;
    font-weight: bold;
  }

  .opciones-estado {
    display: none;
    margin-top: 5px;
  }

form label {
  font-weight: bold;
  margin-bottom: 5px;
}

form select[type="text"] {
  padding: 5px;
  border-radius: 4px;
  border: 1px solid #ccc;
  }
form input[type="text"] {
  padding: 5px;
  border-radius: 4px;
  border: 1px solid #ccc;
}
button {
  background-color: #f44336;
    color: white;
    padding: 5px 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}



</style>

</body>
</html>
